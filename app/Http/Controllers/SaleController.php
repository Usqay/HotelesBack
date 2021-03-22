<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleStoreRequest;
use App\Http\Resources\SaleResource;
use App\Models\CashRegisterMovement;
use App\Models\Client;
use App\Models\People;
use App\Models\Reservation;
use App\Models\ReservationTotal;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\SaleService;
use App\Models\SaleTotal;
use App\Models\StoreHouse;
use App\Models\StoreHouseMovement;
use App\Models\TurnChange;
use App\Traits\Billing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
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
        $reservationId = request()->query('reservation');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $sales = Sale::orderBy('id', 'DESC');

        if(isset($reservationId)){
            $sales->where('reservation_id', '=', $reservationId);
        }

        $sales = $sales->paginate($paginate);

        return SaleResource::collection($sales);
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
     * @param  \App\Http\Requests\SaleStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleStoreRequest $request)
    {
        try{
            $turnChange = TurnChange::where('status_active', '=', true)->first();

            $storeHouse = StoreHouse::where('is_base', '=', true)->first();
            $peopleId = null;
            $datos =[];

            DB::beginTransaction();

            $storeHouseMovement = StoreHouseMovement::create([
                'store_house_id' => $storeHouse->id,
                'store_house_movement_type_id' => '7',
                'description' => 'Salida de productos por venta',
            ]);

            $sale = Sale::create([
                'token_for_observer' => Str::random(10),
                'sale_state_id' => 1,
                'turn_change_id' => $turnChange->id,
                'store_house_movement_id' => $storeHouseMovement->id
            ]);

            if($request->people != null &&  isset($request->people['document_number'])){
                $peopleData = $request->people;
                $peopleData['full_name'] = $peopleData['name'].' '.$peopleData['last_name'];
                $people = People::updateOrCreate([
                    'document_number' => $request->people['document_number']
                ], $peopleData);

                $client = Client::updateOrCreate(['people_id' => $people->id]);

                $sale->client_id = $client->id;
                $peopleId = $people->id;
                $sale->update();
            }

            foreach($request->products as $item){
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'currency_id' => $item['currency_id'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'rate_value' => $item['rate_value'],
                ]);
            }

            foreach($request->services as $item){
                SaleService::create([
                    'sale_id' => $sale->id,
                    'service_id' => $item['service_id'],
                    'currency_id' => $item['currency_id'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'rate_value' => $item['rate_value'],
                ]);
            }

            foreach($request->totals as $item){
                SaleTotal::create([
                    'sale_id' => $sale->id,
                    'currency_id' => $item['currency_id'],
                    'total' => $item['total'],
                ]);
            }

            if(isset($request->reservation_id)){
                $reservation = Reservation::findOrFail($request->reservation_id);
                $sale->update([
                    'reservation_id' => $reservation->id,
                    'client_id' => $reservation->client_id,
                    'room_id' => $request->room_id
                ]);

                $reservation->update(['token_for_observer' => Str::random(10)]);
            }else{

                $requestPayment = $request->payments[0];

                $cashRegisterMovement = CashRegisterMovement::create([
                    'currency_id' => $requestPayment['currency_id'],
                    'cash_register_movement_type_id' => 3,
                    'cash_register_id' => $requestPayment['cash_register_id'],
                    'turn_change_id' => $turnChange->id,
                    'payment_method_id' => $requestPayment['payment_method_id'],
                    'user_id' => auth()->user()->id,
                    'amount' => $requestPayment['total'],
                    'description' => $requestPayment['description'],
                ]);

                if($requestPayment['payment_back'] > 0){
                    $cashRegisterMovement = CashRegisterMovement::create([
                        'currency_id' => $requestPayment['currency_id'],
                        'cash_register_movement_type_id' => 8,
                        'cash_register_id' => $requestPayment['cash_register_id'],
                        'turn_change_id' => $turnChange->id,
                        'payment_method_id' => $requestPayment['payment_method_id'],
                        'user_id' => auth()->user()->id,
                        'amount' => $requestPayment['payment_back'],
                        'description' => 'Vuelto de venta',
                    ]);
                }

                $SalePayment = SalePayment::create([
                    'description' => $requestPayment['description'],
                    'sale_id' => $sale->id,
                    'currency_id' => $requestPayment['currency_id'],
                    'cash_register_movement_id' => $cashRegisterMovement->id,
                    'electronic_voucher_id' => null,
                    'payment_method_id' => $requestPayment['payment_method_id'],
                    'people_id' => $peopleId,
                    'total' => $requestPayment['total'],
                    'print_payment' => $requestPayment['print_payment'],
                    'document_type' => $requestPayment['document_type'],
                ]);

                $result = $this->billingFromSalePayment($SalePayment);

                $datos=$result['api_body'];
                if(!$result['success']){
                    $message = isset($result['api_result']['errors']) ? $result['api_result']['errors'] : 'No se pudo registrar el pago.';
                    DB::rollBack();
                    return $this->successResponse([
                        'success' => false,
                        'error' => $message

                    ]);
                }
            }

            $sale->update([
                'sale_state_id' => 3
            ]);

            $this->saveUserLog($sale);

            DB::commit();

            return isset($request->reservation_id) ? $this->successResponse(['success' => true,'imprimir' => ''], Response::HTTP_OK)
            : $this->successResponse(['success' => true,'imprimir' => \View::make('documents.note', compact('datos'))->render()], Response::HTTP_OK);

        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data ".$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return $this->successResponse(new SaleResource($sale));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
