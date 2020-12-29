<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationCreateRequest;
use App\Http\Requests\ReservationUpdateRequest;
use App\Http\Resources\ReservationResource;
use App\Models\CashRegisterMovement;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\ReservationGuest;
use App\Models\ReservationPayment;
use App\Models\ReservationRoom;
use App\Models\TurnChange;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $state = request()->query('state');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $reservations = Reservation::orderBy('id', 'DESC');

        if($state){
            $reservations->where('reservation_state_id', '=', $state);
        }
        
        $reservations = $reservations->paginate($paginate);

        return ReservationResource::collection($reservations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ReservationCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationCreateRequest $request)
    {
        try{

            $turnChange = TurnChange::where('status_active', '=', true)->first();
            
            DB::beginTransaction();

            $data = $request->all();
            $data['turn_change_id'] = $turnChange->id;
            $data['reservation_state_id'] = 1;
            $data['start_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date);
            $data['end_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->end_date);
            
            if($request->client_id){
                $client = Client::firstOrCreate(['people_id' => $request->client_id]);
                $data['client_id'] = $client->id;
            }
            
            $reservation = Reservation::create($data);

            foreach($request->rooms as $room){
                ReservationRoom::create([
                    'reservation_id' => $reservation->id,
                    'room_id' => $room['id'],
                    'currency_id' => $room['currency_id'],
                    'price_type' => $room['price_type'],
                    'price_value' => $room['price_value'],
                    'total_price' => $room['total_price'],
                ]);
            }
            
            foreach($request->guests as $guest){
                ReservationGuest::create([
                    'reservation_id' => $reservation->id,
                    'guest_id' => $guest,
                ]);
            }

            $reservation->update(['token_for_observer' => Str::random(10)]);
            
            $this->saveUserLog($reservation);
            $this->notifyReservationCreated($reservation);

            DB::commit();
            
            return $this->successResponse(new ReservationResource($reservation), Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getMessage().$e->getLine(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        return $this->successResponse(new ReservationResource($reservation));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ReservationUpdateRequest  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {
        try{
            $turnChange = TurnChange::where('status_active', '=', true)->first();
            
            DB::beginTransaction();

            $data = $request->all();
            if(\in_array('start_date', $data)){
                $data['start_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date);
            }
            if(\in_array('end_date', $data)){
                $data['end_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->end_date);
            }
            
            if($request->client_id){
                $client = Client::firstOrCreate(['people_id' => $request->client_id]);
                $data['client_id'] = $client->id;
            }
            
            $reservation->update($data);
            
            $this->saveUserLog($reservation, 'update');

            if(isset($request->reservation_state_id) && $request->reservation_state_id != '4'){
                $this->notifyReservationUpdated($reservation);
            }else{
                $payments = ReservationPayment::where('reservation_id', '=', $reservation->id)->get();

                foreach($payments as $payment){

                    $cashRegisterMovement = CashRegisterMovement::findOrFail($payment->cash_register_movement_id);

                    CashRegisterMovement::create([
                        'currency_id' => $payment->currency_id,
                        'cash_register_movement_type_id' => 9,
                        'cash_register_id' => $cashRegisterMovement->cash_register_id,
                        'turn_change_id' => $turnChange->id,
                        'payment_method_id' => $payment->payment_method_id,
                        'user_id' => auth()->user()->id,
                        'amount' => $payment->total,
                        'description' => "Anulación de la reservación número: $reservation->id",
                    ]);
                }

                $this->notifyReservationDeleted($reservation);
            }

            DB::commit();
            
            return $this->successResponse(new ReservationResource($reservation), Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getMessage().$e->getLine(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
