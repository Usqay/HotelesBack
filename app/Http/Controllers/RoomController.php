<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Client;
use App\Models\Gender;
use App\Models\People;
use App\Traits\Billing;
use App\Models\Currency;
use App\Models\RoomPrice;
use App\Models\RoomStatus;
use App\Models\TurnChange;
use App\Models\Reservation;
use Illuminate\Support\Str;
use App\Models\DocumentType;
use App\Models\RoomCategory;
use App\Models\PaymentMethod;
use Illuminate\Http\Response;
use App\Models\ReservationRoom;
use App\Models\ReservationGuest;
use App\Models\ReservationPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoomResource;
use App\Models\CashRegisterMovement;
use App\Http\Requests\RoomCreateRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Http\Requests\RoomReserveRequest;

class RoomController extends Controller
{
    use Billing;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $roomStatusId = request()->query('status');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $rooms = Room::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%")
            ->orWhere("description", "like", "%$q%");
        });

        if(isset($roomStatusId)){
            $rooms->where('room_status_id', '=', $roomStatusId);
        }

        $rooms = $rooms->paginate($paginate);

        return RoomResource::collection($rooms);
    }
    public function list()
    {
        $id = request()->query('id');

        if($id == 0){
            $rooms = Room::get();
        }else{
             $rooms = Room::where('room_category_id',$id)->get();
        }


        return RoomResource::collection($rooms);
    }

    public function getCategoriesStatus()
    {
        $status = RoomStatus::whereNull('deleted_at')->get();
        $category=RoomCategory::whereNull('deleted_at')->get();
        return [
            'status' => $status,
            'category' => $category
        ];
    }

    public function getData(){
        $documentTypes = DocumentType::get();
        $genders = Gender::get();
        $paymentMethods = PaymentMethod::get();
        $currencies = Currency::get();
        return  compact('documentTypes','genders','paymentMethods','currencies');
    }

    public function getCurrencPaymentMethod(){

        $paymentMethods = PaymentMethod::get();
        $currencies = Currency::get();

        return  compact('paymentMethods','currencies');
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
     * @param  \App\http\Requests\RoomCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomCreateRequest $request)
    {
        try{

            DB::beginTransaction();

            $room = Room::create($request->toArray());

            $this->saveUserLog($room);

            DB::commit();

            return $this->successResponse(new RoomResource($room), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return $this->successResponse(new RoomResource($room));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $room
     * @return \Illuminate\Http\Response
     */
    public function update(RoomUpdateRequest $request, Room $room)
    {
        try{

            DB::beginTransaction();

            $room->fill($request->toArray());
            $room->save();

            if($request->room_prices){
                foreach($request->room_prices as $price){
                    RoomPrice::withTrashed()->updateOrCreate(
                        ['room_id' => $room->id, 'currency_id' => $price['currency_id']],
                        ['day_price' => $price['day_price'], 'hour_price' => $price['hour_price']]
                    );
                }
            }

            $this->saveUserLog($room, 'update');

            DB::commit();

            return $this->successResponse(new RoomResource($room));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy($room)
    {
        $room = Room::withTrashed()->findOrFail($room);

        if ($room->trashed()) {
            $room->restore();
            $this->saveUserLog($room, 'restore');
        } else {
            $room->delete();
            $this->saveUserLog($room, 'delete');
        }

        return $this->successResponse(new RoomResource($room));
    }

    /**
     * Store a newly reservation.
     *
     * @param  \App\Http\Requests\RoomReserveRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function reserve(RoomReserveRequest $request)
    {
        try{

            $turnChange = TurnChange::where('status_active', '=', true)->first();
            $baseCurrency = Currency::where('is_base', '=', true)->first();

            DB::beginTransaction();

            $data = $request->all();
            $data['turn_change_id'] = $turnChange->id;
            $data['reservation_state_id'] = 1;
            $data['start_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date);
            $data['end_date'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->end_date);

            $peopleFullName = isset($request->people_full_name) ? $request->people_full_name : $request->people_name.' '.$request->people_last_name;

            $people = People::firstOrCreate(['document_number' => $request->people_document_number],[
                'document_type_id' => $request->people_document_type_id,
                'full_name' => $peopleFullName,
                'gender_id' => $request->people_gender_id,
                'last_name' => $request->people_last_name,
                'name' => $request->people_name,
            ]);
            $client = Client::firstOrCreate(['people_id' => $people->id]);
            $guest = Guest::firstOrCreate(['people_id' => $people->id]);

            $data['client_id'] = $client->id;
            $data['reservation_origin_id'] = 1;
            $data['total_days'] = $request->days;
            $data['total_hours'] = $request->hours;

            $reservation = Reservation::create($data);

            $roomTotalPrice = 0;
            if($request->room_price_type == 'day'){
                $roomTotalPrice = $request->room_price_value * $request->days;
            }else{
                $roomTotalPrice = $request->room_price_value * $request->hours;
            }

            ReservationRoom::create([
                'reservation_id' => $reservation->id,
                'room_id' => $request->room_id,
                'currency_id' => $request->room_price_currency_id,
                'price_type' => $request->room_price_type,
                'price_value' => $request->room_price_value,
                'total_price' => $roomTotalPrice
            ]);

            ReservationGuest::create([
                'reservation_id' => $reservation->id,
                'guest_id' => $guest->id,
            ]);

            $reservationStateId = $request->mark_checking ? 2 : 1;

            $reservation->update(['token_for_observer' => Str::random(10), 'reservation_state_id' => $reservationStateId]);

            //Payment
            if($request->create_payment){

                if($request->payment_back > 0){
                    CashRegisterMovement::create([
                        'currency_id' => $baseCurrency->id,
                        'cash_register_movement_type_id' => 8,
                        'cash_register_id' => $request->cash_register_id,
                        'turn_change_id' => $turnChange->id,
                        'payment_method_id' => $request->payment_method_id,
                        'user_id' => auth()->user()->id,
                        'amount' => $request->payment_back,
                        'description' => "Vuelto de pago de reservación",
                    ]);
                }

                $cashRegisterMovement = CashRegisterMovement::create([
                    'currency_id' => $baseCurrency->id,
                    'cash_register_movement_type_id' => 2,
                    'cash_register_id' => $request->cash_register_id,
                    'turn_change_id' => $turnChange->id,
                    'payment_method_id' => $request->payment_method_id,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->payment_amount,
                    'description' => "Pago de alquiler de habitación",
                ]);

                $reservationPayment = ReservationPayment::create([
                    'description' => "Pago de alquiler de habitación",
                    'reservation_id' => $reservation->id,
                    'currency_id' => $baseCurrency->id,
                    'payment_method_id' => $request->payment_method_id,
                    'cash_register_movement_id' => $cashRegisterMovement->id,
                    'people_id' => $people->id,
                    'total' => $request->payment_amount,
                    'payment_by' => 0,
                    'print_payment' => $request->print_payment,
                    'document_type' => $request->payment_document_type,
                ]);

                $result = $this->billingFromReservationPayment($reservationPayment);

                if(!$result['success']){
                    $message = isset($result['api_result']['errors']) ? $result['api_result']['errors'] : 'No se pudo registrar el pago.';
                    DB::rollBack();
                    return $this->successResponse([
                        'success' => false,
                        'error' => $message
                    ]);
                }
            }

            $this->saveUserLog($reservation);
            $this->notifyReservationCreated($reservation);

            DB::commit();

            return $this->successResponse([
                'success' => true
            ], Response::HTTP_OK);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getFile().$e->getLine(), Response::HTTP_BAD_REQUEST);
        }
    }
}
