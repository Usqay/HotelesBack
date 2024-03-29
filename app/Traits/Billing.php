<?php

namespace App\Traits;

use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\DocumentType;
use App\Models\People;
use App\Models\ElectronicVoucher;
use App\Models\Product;
use App\Models\ReservationPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\SaleService;
use App\Models\Service;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Http;
use Luecano\NumeroALetras\NumeroALetras;
trait Billing
{
    public $people_id = null;
    public $people = null;
    public $document_type = 'not';
    public $body = [
        "operacion"                         => "generar_comprobante",
        "tipo_de_comprobante"               => "",
        "serie"                             => "",
        "numero"                            =>  "",
        "sunat_transaction"                 => "1",
        "cliente_tipo_de_documento"         => "",
        "cliente_numero_de_documento"       => "",
        "cliente_denominacion"              => "",
        "cliente_direccion"                 => "",
        "cliente_email"                     => "",
        "cliente_email_1"                   => "",
        "cliente_email_2"                   => "",
        "fecha_de_emision"                  => "",
        "fecha_de_vencimiento"              => "",
        "moneda"                            => "1",
        "tipo_de_cambio"                    => "",
        "porcentaje_de_igv"                 => "18",
        "descuento_global"                  => "0",
        "total_descuento"                   => "0",
        "total_anticipo"                    => "",
        "total_gravada"                     => "",
        "total_inafecta"                    => "",
        "total_exonerada"                   => "",
        "total_igv"                         => "",
        "total_gratuita"                    => "",
        "total_otros_cargos"                => "",
        "total"                             => "",
        "percepcion_tipo"                   => "",
        "percepcion_base_imponible"         => "",
        "total_percepcion"                  => "",
        "total_incluido_percepcion"         => "",
        "detraccion"                        => "false",
        "observaciones"                     => "",
        "documento_que_se_modifica_tipo"    => "",
        "documento_que_se_modifica_serie"   => "",
        "documento_que_se_modifica_numero"  => "",
        "tipo_de_nota_de_credito"           => "",
        "tipo_de_nota_de_debito"            => "",
        "enviar_automaticamente_a_la_sunat" => "true",
        "enviar_automaticamente_al_cliente" => "",
        "codigo_unico"                      => "",
        "condiciones_de_pago"               => "",
        "medio_de_pago"                     => "",
        "placa_vehiculo"                    => "",
        "orden_compra_servicio"             => "",
        "tabla_personalizada_codigo"        => "",
        "formato_de_pdf"                    => "",
        "items" => [],
    ];

    public function makeClient($people_id){

        if(isset($people_id)){
            $this->people_id = $people_id;
            $this->people = People::findOrFail($this->people_id);
            $document_type = DocumentType::findOrFail($this->people->document_type_id);

            $this->body['cliente_tipo_de_documento'] = $document_type->code;
            $this->body['cliente_numero_de_documento'] = $this->people->document_number;
            $this->body['cliente_denominacion'] = $this->people->full_name;
            $this->body['cliente_direccion'] = $this->people->address;
            $this->body['cliente_email'] = $this->people->email;
        }else{
            $this->body['cliente_tipo_de_documento'] = '-';
            $this->body['cliente_numero_de_documento'] = '---';
            $this->body['cliente_denominacion'] = 'GENERICO';
            $this->body['cliente_direccion'] = '-';
            $this->body['cliente_email'] = '';
        }

    }

    public function makeItems($data){
        $total = 0;
        $tax = 0;
        $items = [];
        $formatter = new NumeroALetras();

        foreach($data as $d){
            $unitvalue = $d['unit_price'] / 1.18;
            $totalLine = $d['unit_price'] * $d['quantity'];
            $subtotalLine = $totalLine / 1.18;
            $taxLine = $totalLine - $subtotalLine;

            $total += $totalLine;
            $tax += $taxLine;

            $items[] = [
                "unidad_de_medida" => $d['measure_unit'],
                "codigo" => $d['id'],
                "codigo_producto_sunat" => $d['national_code'],
                "descripcion" => $d['description'],
                "cantidad" => \number_format($d['quantity']),
                "valor_unitario" => \number_format($unitvalue, 2),
                "precio_unitario" => \number_format($d['unit_price'], 2),
                "descuento" => $d['disccount'],
                "subtotal" => \number_format($subtotalLine, 2),
                "tipo_de_igv" => 1,
                "igv" => \number_format($taxLine, 2),
                "total" => \number_format($totalLine, 2),
                "anticipo_regularizacion" => false,
                "anticipo_documento_serie" => "",
                "anticipo_documento_numero" => "",
            ];
        }

        $this->body['items'] = $items;
        $this->body['total'] = \number_format($total, 2);
        $this->body['total_igv'] = \number_format($tax, 2);
        $this->body['total_gravada'] = \number_format($total - $tax, 2);
        $this->body['monto_letras'] = $formatter->toMoney($total, 2, 'SOLES', 'CENTIMOS');
    }

    public function senToApi(){
        $billing_route = SystemConfiguration::where('key', '=', 'billing_route')->first();
        $billing_token = SystemConfiguration::where('key', '=', 'billing_token')->first();
        $result = [
            'success' => true,
            'api_body' => $this->body,
            'api_result' => [],
        ];
     
        try{
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->withToken($billing_token->value)
            ->post($billing_route->value.'emitir_comprobante', $this->body);

            $apiResult = $response->json();
            //dd($apiResult);
            if(isset($apiResult['ok']) || isset($apiResult['errors'])){
                $result['success'] = false;
            }
            $result['api_result'] = $response->json();
        }catch(\Exception $e){
            $result = [
                'success' => false,
                'api_body' => $this->body,
                'api_result' => $e,
            ];
        }

        return $result;
    }

    public function senToApiDelete($data_json){
        
        $billing_route = SystemConfiguration::where('key', '=', 'billing_route')->first();
        $billing_token = SystemConfiguration::where('key', '=', 'billing_token')->first();      
     
        $respuesta = [
            'success' => true,
            'api_body' =>$data_json,
            'api_result' => [],
        ];

      
      
        try{
          
       
            //dd($data_json);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$billing_route->value.'anular_comprobante');
            /*curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Token token="'.$billing_token->value.'"',
                'Content-Type: application/json',
                )
            );*/
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                )
            );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp  = curl_exec($ch);
            curl_close($ch);        
           
            if ($resp != ""){

                $n = (array) json_decode(stripslashes($resp));   
               
                if( !isset($n["errors"])){
                    $respuesta['success'] = true;
                    $respuesta['message'] = "Operacion Exitosa";
                }else{
                    $respuesta['success'] = false;
                    $respuesta['message'] = $n["errors"];
                }

            }

            //return $respuesta;

        }catch(\Exception $e){
            $respuesta = [
                'success' => false,
                'api_body' => [],
                'api_result' => $e,
            ];
        }

        return $respuesta;
    }

    public function billingFromReservationPayment(ReservationPayment $ReservationPayment){
        $serie = null;
        $number = null;

        $currency = Currency::findOrFail($ReservationPayment->currency_id);
        $currencyRate = CurrencyRate::where('currency_id', '=', $currency->id)
        ->orderBy('rate_date', 'DESC')->first();
        $paymentDescription = 'Servicio de hospedaje';
        if($ReservationPayment->payment_by == '1'){
            $paymentDescription = 'Servicio de consumo';
        }else if($ReservationPayment->payment_by == '2'){
            $paymentDescription = 'Servicio de hospedaje y consumo';
        }
        $token =  SystemConfiguration::where('key', '=', 'billing_token')->first();
        $currencyRateValue = isset($currencyRate) ? $currencyRate->rate_value : 1;

        $electronic_voucher_type_id = 1;

        switch($ReservationPayment->document_type){
            case 'bol' :
                $electronic_voucher_type_id = 2;
                $ballot_series = SystemConfiguration::where('key', '=', 'ballot_series')->first();
                $ballot_auto_increment = SystemConfiguration::where('key', '=', 'ballot_auto_increment')->first();

                $number = $ballot_auto_increment->value + 1;
                $serie = $ballot_series->value;
                break;

            case 'not' :
                $electronic_voucher_type_id = 3;
                $lastDocument = ElectronicVoucher::where('electronic_voucher_type_id', '=', $electronic_voucher_type_id)->orderBy('number', 'desc')->first();

                $number = ($lastDocument != null) ? $lastDocument->number + 1 : 1;
                break;

            case 'fac' :
                $electronic_voucher_type_id = 1;
                $invoice_series = SystemConfiguration::where('key', '=', 'invoice_series')->first();
                $invoice_auto_increment = SystemConfiguration::where('key', '=', 'invoice_auto_increment')->first();

                $number = $invoice_auto_increment->value + 1;
                $serie = $invoice_series->value;
                break;
        }

        $this->body['tipo_de_comprobante'] = $electronic_voucher_type_id;
        $this->body['serie'] = $serie;
        $this->body['numero'] = $number;
        $this->body['fecha_de_emision'] = date('Y-m-d H:i:s');
        $this->body['moneda'] = '1';
        $this->body['tipo_de_cambio'] = '';
        $this->body['token'] = $token->value;

        $items = [[
            'unit_price' => $ReservationPayment->total * $currencyRateValue,
            'quantity' => 1,
            'measure_unit' => 'ZZ',
            'id' => $ReservationPayment->id,
            'national_code' => '90111500',
            'description' => $paymentDescription,
            'disccount' => 0,
        ]];

        $this->makeClient($ReservationPayment->people_id);
        $this->makeItems($items);
        $apiResult = [
            'success' => true,
            'api_body' => $this->body,
            'api_result' => [],
        ];

        if($ReservationPayment->document_type != 'not'){
            $apiResult = $this->senToApi();
        }
     
        $tipoVenta =2;
        $electronicVoucher = $this->storeElectronicVoucher($electronic_voucher_type_id, $number, $serie, $this->body, $ReservationPayment->print_payment, true, $apiResult['api_result'],[],$tipoVenta,$ReservationPayment->reservation_id);
        $ReservationPayment->update([
            'electronic_voucher_id' => $electronicVoucher->id
        ]);
        return $apiResult ;
    }

    public function billingFromSalePayment(SalePayment $salePayment){
        $serie = null;
        $number = null;

        $electronic_voucher_type_id = 1;
        $token =  SystemConfiguration::where('key', '=', 'billing_token')->first();

        switch($salePayment->document_type){
            case 'bol' :
                $electronic_voucher_type_id = 2;
                $ballot_series = SystemConfiguration::where('key', '=', 'ballot_series')->first();
                $ballot_auto_increment = SystemConfiguration::where('key', '=', 'ballot_auto_increment')->first();

                $number = $ballot_auto_increment->value + 1;
                $serie = $ballot_series->value;
                break;

            case 'not' :
                $electronic_voucher_type_id = 3;
                $lastDocument = ElectronicVoucher::where('electronic_voucher_type_id', '=', $electronic_voucher_type_id)->orderBy('number', 'desc')->first();

                $number = ($lastDocument != null) ? $lastDocument->number + 1 : 1;
                break;

            case 'fac' :
                $electronic_voucher_type_id = 1;
                $invoice_series = SystemConfiguration::where('key', '=', 'invoice_series')->first();
                $invoice_auto_increment = SystemConfiguration::where('key', '=', 'invoice_auto_increment')->first();

                $number = $invoice_auto_increment->value + 1;
                $serie = $invoice_series->value;
                break;
        }

        $this->body['tipo_de_comprobante'] = $electronic_voucher_type_id;
        $this->body['serie'] = $serie;
        $this->body['numero'] = $number;
        $this->body['fecha_de_emision'] = date('Y-m-d H:i:s');
        $this->body['moneda'] = '1'; //SOLES
        $this->body['tipo_de_cambio'] = '';
        $this->body['token'] = $token->value;


        $products = SaleProduct::where('sale_id', '=', $salePayment->sale_id)->get();
        $services = SaleService::where('sale_id', '=', $salePayment->sale_id)->get();

        $items = [];

        foreach($products as $item){
            $product = Product::findOrFail($item->product_id);
            $items[] = [
                'unit_price' => $item->unit_price * $item->rate_value,
                'quantity' => $item->quantity,
                'measure_unit' => 'NIU',
                'id' => $item->product_id,
                'national_code' => $product->sunat_code,
                'description' => $product->name,
                'disccount' => 0,
            ];
        }

        foreach($services as $item){
            $service = Service::findOrFail($item->service_id);
            $items[] = [
                'unit_price' => $item->unit_price * $item->rate_value,
                'quantity' => $item->quantity,
                'measure_unit' => 'ZZ',
                'id' => $item->service_id,
                'national_code' => $service->sunat_code,
                'description' => $service->name,
                'disccount' => 0,
            ];
        }

        $this->makeClient($salePayment->people_id);
        $this->makeItems($items);
        $apiResult = [
            'success' => true,
            'api_body' => $this->body,
            'api_result' => [],
        ];

        if($salePayment->document_type != 'not'){
            $apiResult = $this->senToApi();
        }
        $tipoVenta =1;
     
        $electronicVoucher = $this->storeElectronicVoucher($electronic_voucher_type_id, $number, $serie, $this->body, $salePayment->print_payment, true, $apiResult['api_result'],[],$tipoVenta,$salePayment->sale_id);
        $salePayment->update([
            'electronic_voucher_id' => $electronicVoucher->id
        ]);
        return $apiResult;
    }

    public function storeElectronicVoucher($electronic_voucher_type_id, $number, $serie, $apiBody, $print = true, $apiState = true, $apiResult = [], 
    $adittionalInfo = [],$sale_reservation,$sale_reservation_id,$tipoNC=0 ){

        $ballot_auto_increment = SystemConfiguration::where('key', '=', 'ballot_auto_increment')->first();
        $invoice_auto_increment = SystemConfiguration::where('key', '=', 'invoice_auto_increment')->first();
        $ncf_series = SystemConfiguration::where('key', '=', 'ncf_auto_increment')->first();
        $ncb_series = SystemConfiguration::where('key', '=', 'ncb_auto_increment')->first();
        //dd($apiResult);
        $electronicVoucher = ElectronicVoucher::create([
            'date_emitted' => date('Y-m-d H:i:s'),
            'electronic_voucher_type_id' => $electronic_voucher_type_id,
            'number' =>  $apiResult['numero'], //$electronic_voucher_type_id ==3 ? $number : $apiResult['numero'],
            'serie' => $apiResult['serie'],//$electronic_voucher_type_id ==3 ? $number : $apiResult['serie'],
            'print' => $print,
            'api_body' => \json_encode($apiBody),
            'api_response' => \json_encode($apiResult),
            'api_state' => $apiState,
            'adittional_info' => \json_encode($adittionalInfo),
            'sale_reservation' => $sale_reservation,
            'sale_reservation_id' => $sale_reservation_id,
        ]);

        if($electronic_voucher_type_id == 2){ //Boleta
            $ballot_auto_increment->value = $apiResult['numero'];
            $ballot_auto_increment->save();
        }else if($electronic_voucher_type_id == 1){ //Factura
            $invoice_auto_increment->value =$apiResult['numero'];
            $invoice_auto_increment->save();
        }
        
        if($tipoNC == 2){ //NC
            $ncf_series->value =$apiResult['numero'];
            $ncf_series->save();

        }else if($tipoNC == 1){ //NC

            $ncb_series->value =$apiResult['numero'];
            $ncb_series->save();

        }

        return $electronicVoucher;
    }

    public function creditNoteSale(Sale $sale){
        $serie = null;
        $number = null;

        $tipoNC=1;
     
        $electronic_voucher_type_id = 4;
        $token =  SystemConfiguration::where('key', '=', 'billing_token')->first();
        $salePayment= SalePayment::where('sale_id',$sale->id)->first();  
        $lastDocument = ElectronicVoucher::where('sale_reservation_id', '=', $sale->id)->orderBy('number', 'desc')->first();

        switch($salePayment->document_type){
            case 'bol' :
                $electronic_voucher_type_id = 4; // NC - ID documento de Nota de Crédito
                $ballot_series = SystemConfiguration::where('key', '=', 'ncb_series')->first();
                $ballot_auto_increment = SystemConfiguration::where('key', '=', 'ncb_auto_increment')->first();
                $tipoNC=1;
                $number = $ballot_auto_increment->value + 1;
                $serie = $ballot_series->value;
                break;
 
            case 'not' : //Ver que hacer aqui, no se hace NC a una nota de venta
                $electronic_voucher_type_id = 3;
                $lastDocument = ElectronicVoucher::where('electronic_voucher_type_id', '=', $electronic_voucher_type_id)->orderBy('number', 'desc')->first();

                $number = ($lastDocument != null) ? $lastDocument->number + 1 : 1;
                break;

            case 'fac' :
                $electronic_voucher_type_id = 4;
                $invoice_series = SystemConfiguration::where('key', '=', 'ncf_series')->first();
                $invoice_auto_increment = SystemConfiguration::where('key', '=', 'ncf_auto_increment')->first();
                $tipoNC=2;
                $number = $invoice_auto_increment->value + 1;
                $serie = $invoice_series->value;
                break;
        }

        $this->body['tipo_de_comprobante'] = 3;//NOTA DE CREDITO //$electronic_voucher_type_id;
        $this->body['serie'] = $serie;
        $this->body['numero'] = $number;
        $this->body['fecha_de_emision'] = date('Y-m-d H:i:s');
        $this->body['moneda'] = '1'; //SOLES
        $this->body['tipo_de_cambio'] = '';
        $this->body['token'] = $token->value;

        $this->body["documento_que_se_modifica_tipo"] =  $lastDocument->electronic_voucher_type_id; // 1 Factura - 2 Boleta
        $this->body["documento_que_se_modifica_serie"] = $lastDocument->serie;
        $this->body["documento_que_se_modifica_numero"] = str_pad($lastDocument->number, 8, "0", STR_PAD_LEFT);
        $this->body["tipo_de_nota_de_credito"] =1;
        //$this->body["tipo_de_nota_de_debito"] = $tipo_de_nota_de_debito;
        $this->body["enviar_automaticamente_a_la_sunat"] = "false";
        //$this->body["enviar_automaticamente_al_cliente"] = "true";
        $this->body["formato_de_pdf"] = "TICKET";


        $products = SaleProduct::where('sale_id', '=', $salePayment->sale_id)->get();
        $services = SaleService::where('sale_id', '=', $salePayment->sale_id)->get();

        $items = [];

        foreach($products as $item){
            $product = Product::findOrFail($item->product_id);
            $items[] = [
                'unit_price' => $item->unit_price * $item->rate_value,
                'quantity' => $item->quantity,
                'measure_unit' => 'NIU',
                'id' => $item->product_id,
                'national_code' => $product->sunat_code,
                'description' => $product->name,
                'disccount' => 0,
            ];
        }

        foreach($services as $item){
            $service = Service::findOrFail($item->service_id);
            $items[] = [
                'unit_price' => $item->unit_price * $item->rate_value,
                'quantity' => $item->quantity,
                'measure_unit' => 'ZZ',
                'id' => $item->service_id,
                'national_code' => $service->sunat_code,
                'description' => $service->name,
                'disccount' => 0,
            ];
        }

        $this->makeClient($salePayment->people_id);
        $this->makeItems($items);
        $apiResult = [
            'success' => true,
            'api_body' => $this->body,
            'api_result' => [],
        ];

        if($salePayment->document_type != 'not'){
            $apiResult = $this->senToApi();
            //dd(  $apiResult);
        }
        $tipoVenta =1;
     
        $electronicVoucher = $this->storeElectronicVoucher($electronic_voucher_type_id, $number, $serie, $this->body, 
        $salePayment->print_payment, true, $apiResult['api_result'],[],$tipoVenta,$salePayment->sale_id,$tipoNC);
    
        return $apiResult;
    }
}
