<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationPaymentStoreRequest;
use App\Http\Resources\ReservationPaymentResource;
use App\Models\CashRegister;
use App\Models\CashRegisterMovement;
use App\Models\Currency;
use App\Models\ReservationPayment;
use App\Models\TurnChange;
use App\Traits\Billing;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReservationPaymentController extends Controller
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
        $reservationId = request()->query('reservationId');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $reservationPayments = ReservationPayment::orderBy('id', 'DESC')
        ->where(function ($query) use ($q, $reservationId) {
            $query->where("reservation_id", "=", $reservationId);
        })
        ->paginate($paginate);

        return ReservationPaymentResource::collection($reservationPayments);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationPaymentStoreRequest $request)
    {
        $baseCurrency = Currency::where('is_base', '=', true)->first();

        try{

            DB::beginTransaction();

            $turnChange = TurnChange::where('status_active', '=', true)->first();

            $cash_register_movement_type_id = 12;

            if($request->payment_by == 0){
                $cash_register_movement_type_id = 2;
            }
            if($request->payment_by == 1){
                $cash_register_movement_type_id = 5;
            }
            if($request->payment_by == 2){
                $cash_register_movement_type_id = 12;
            }

            if($request->payment_back > 0){
                CashRegisterMovement::create([
                    'currency_id' => $baseCurrency->id,
                    'cash_register_movement_type_id' => 8,
                    'cash_register_id' => $request->cash_register_id,
                    'turn_change_id' => $turnChange->id,
                    'payment_method_id' => $request->payment_method_id,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->payment_back,
                    'description' => "Vuelto de pago de reservación / Consumo",
                ]);
            }

            $cashRegisterMovement = CashRegisterMovement::create([
                'currency_id' => $baseCurrency->id,
                'cash_register_movement_type_id' => $cash_register_movement_type_id,
                'cash_register_id' => $request->cash_register_id,
                'turn_change_id' => $turnChange->id,
                'payment_method_id' => $request->payment_method_id,
                'user_id' => auth()->user()->id,
                'amount' => $request->total,
                'description' => $request->description,
            ]);

            $reservationPayment = ReservationPayment::create([
                'description' => $request->description,
                'reservation_id' => $request->reservation_id,
                'currency_id' => $baseCurrency->id,
                'payment_method_id' => $request->payment_method_id,
                'cash_register_movement_id' => $cashRegisterMovement->id,
                'people_id' => $request->people_id,
                'total' => $request->total,
                'payment_by' => $request->payment_by,
                'print_payment' => $request->print_payment,
                'document_type' => $request->document_type,
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

            $this->saveUserLog($reservationPayment);

            DB::commit();
            //print_r($result);
            //return $this->successResponse($result, Response ::HTTP_CREATED);
            $datos=$result['api_body'];
            //poner datos correctos
            \QRCode::text('Laravel QR Code Generator!')
            ->setOutfile('./qr/'.$result['api_body']['numero'].'.png')
            ->png();

            return $this->successResponse([
                'success' => true,
                'data' => $result,
                'imprimir' => \View::make('documents.note', compact('datos'))->render()

            ]);

        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data".$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationPayment  $reservationPayment
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationPayment $reservationPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReservationPayment  $reservationPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservationPayment $reservationPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationPayment  $reservationPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationPayment $reservationPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationPayment  $reservationPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationPayment $reservationPayment)
    {
        //
    }
}
