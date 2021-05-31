<?php

namespace App\Http\Controllers;

use App\Http\Resources\ElectronicVoucherResource;
use App\Models\ElectronicVoucher;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleTotal;
use App\Models\SalePayment;
use App\Models\CashRegisterMovement;
use App\Models\ProductMovement;
use App\Models\SaleProduct;
use App\Models\SaleService;
use App\Models\ServiceProduct;
use App\Models\StoreHouse;
use App\Models\StoreHouseMovement;
use App\Models\SystemConfiguration;
use App\Traits\Billing;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ElectronicVoucherController extends Controller
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
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $electronicVouchers = ElectronicVoucher::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("number", "like", "%$q%");
            $query->orWhere("serie", "like", "%$q%");
            $query->orWhere("created_at", "like", "%$q%");
        })
        ->paginate($paginate);

        return ElectronicVoucherResource::collection($electronicVouchers);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ElectronicVoucher $electronicVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ElectronicVoucher  $electronicVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(ElectronicVoucher $electronicVoucher)
    {
       
        //Optimizar toda esta mierda
        $billing_token = SystemConfiguration::where('key', '=', 'billing_token')->first();
        $result='';
        if($electronicVoucher->electronic_voucher_type_id != 3){

            $cabecera = array();
            $cabecera["operacion"] = "generar_anulacion";
            $cabecera["tipo_de_comprobante"] =$electronicVoucher->electronic_voucher_type_id;
            $cabecera["serie"] = $electronicVoucher->serie;
            $cabecera["numero"] = $electronicVoucher->number;
            $cabecera["motivo"] = "ERROR DE SISTEMA";
            $cabecera["token"] = $billing_token->value;
            $cabecera["mac"] = $this->getMAC();
           
            $data_json = json_encode($cabecera);

            $result =(array)($this->senToApiDelete($data_json));
         
           if (($result['success'])){

                if( $electronicVoucher->sale_reservation == 1 ){ //Anular venta -Falta devolver productos a almacen


                    $ts = \Carbon\Carbon::now()->toDateTimeString();

                    $sale=Sale::where('id',$electronicVoucher->sale_reservation_id)->update(['sale_state_id' => 4 ,'deleted_at' =>$ts]);          
                    $saleTotal= SaleTotal::where('sale_id',$electronicVoucher->sale_reservation_id)->delete();
                    $salePayment= SalePayment::where('sale_id',$electronicVoucher->sale_reservation_id)->first();  
                    $cashRegister = CashRegisterMovement::where('id',$salePayment->cash_register_movement_id)->delete();

                    /* Retornar stck de productos vendidos */               
                    $this->deleteSale($electronicVoucher->sale_reservation_id,$electronicVoucher->sale_reservation);
                   

                     
                    $salePayment->delete();
                    $electronicVoucher->delete();

                }
           }
        }else if($electronicVoucher->electronic_voucher_type_id == 3){
           
            $ts = \Carbon\Carbon::now()->toDateTimeString();

            if( $electronicVoucher->sale_reservation == 1 ){ //Anular venta - Falta devolver productos a almacen                

               
                $sale=Sale::where('id',$electronicVoucher->sale_reservation_id)->update(['sale_state_id' => 4 ,'deleted_at' =>$ts]);          
                $saleTotal= SaleTotal::where('sale_id',$electronicVoucher->sale_reservation_id)->delete();
                $salePayment= SalePayment::where('sale_id',$electronicVoucher->sale_reservation_id)->first();  
                if($salePayment) 
                    CashRegisterMovement::where('id',$salePayment->cash_register_movement_id)->delete();

                /* Retornar stck de productos vendidos */               
                $this->deleteSale($electronicVoucher->sale_reservation_id,$electronicVoucher->sale_reservation);
                if($salePayment)
                    $salePayment->delete();

                $electronicVoucher->delete();
                $result = [
                    'success' => true,
                    'message' =>'Documento anulado'
                   
                ];

            }else{

                $sale=Sale::where('reservation_id',$electronicVoucher->sale_reservation_id)->update(['sale_state_id' => 4 ,'deleted_at' =>$ts]);          
                $saleTotal= SaleTotal::where('sale_id',$electronicVoucher->sale_reservation_id)->delete();
                $salePayment= SalePayment::where('sale_id',$electronicVoucher->sale_reservation_id)->first();  
                if($salePayment) 
                    CashRegisterMovement::where('id',$salePayment->cash_register_movement_id)->delete();

                /* Retornar stck de productos vendidos */               
                $this->deleteSale($electronicVoucher->sale_reservation_id,$electronicVoucher->sale_reservation);
                if($salePayment)
                    $salePayment->delete();

                $electronicVoucher->delete();
                $result = [
                    'success' => true,
                    'message' =>'Documento anulado'
                   
                ];

            }
        }
        

      
        
         return($result);

        /*return $this->successResponse([
            'success' => true,            
            'mensage' => 'Documento fue anulado!'

        ]);*/
    }

    public function imprimir (Request $request){
       
        $datos = json_decode($request->getContent(), true);
      
        return $this->successResponse([
            'success' => true,            
            'imprimir' => \View::make('documents.note', compact('datos'))->render()

        ]);

    }

    public function cancel (Request $request,$id){
        //$datos = json_decode($request->getContent(), true);

        try{
            DB::beginTransaction();

                    
            $electronicVoucher= ElectronicVoucher::findOrFail($id);
            
            if ( $electronicVoucher->sale_reservation == 1){ //Venta

                    $sale=Sale::where('id',$electronicVoucher->sale_reservation_id)->first();
                
                
            }else{
                //Buscar primero las reservaciones y buscar si tiene venta
                    $sale=Sale::where('reservation_id',$electronicVoucher->sale_reservation_id)->first(); 

            }
            
                $result = $this->creditNoteSale($sale);
             
                $datos=$result['api_body'];
              
                if(!$result['success']){
                    $message = isset($result['api_result']['errors']) ? $result['api_result']['errors'] : 'No se pudo generar la Nota de crédito.';
                    DB::rollBack();
                    return $this->successResponse([
                        'success' => false,
                        'error' => $message

                    ]);
                }


            DB::commit();

            return $this->successResponse(['success' => true,'respuesta' =>  $result], Response::HTTP_OK);

        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data ".$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    
     
              
        //  $details = json_decode($_POST['data']);
        /*  $itemsNotaCreditoDebito= json_decode($_POST["itemsNotaCreditoDebito"]);
          $subtotal=  $_POST["subTotal"];
          $total=  $_POST["total"];
          $igv=  $_POST["igv"];
          $res = $objventa->consulta_arreglo("SELECT * FROM venta WHERE id = " . $_POST["idVenta"]);

          $config = $objventa->consulta_arreglo("SELECT * from configuracion");

          $cliente = $objventa->consulta_arreglo("SELECT * FROM cliente WHERE id =" . $_POST["idCliente"]);
          
        
          if (intval($_POST['nota'] == 2)) {
              $tipDoc = 4;
              $tipo_de_nota_de_credito = "";
              $tipo_de_nota_de_debito =  $_POST["motivoEmision"];
          } else {
              $tipDoc = 3;
              $tipo_de_nota_de_credito =  $_POST["motivoEmision"];
              $tipo_de_nota_de_debito = "";
          }
         
          if (intval($_POST["tipo"]) == 1) {
              $serie = $config["serie_boleta"];
              $boleta = $objventa->consulta_arreglo("Select * from boleta WHERE id_venta =" . $res["id"]);
              $numero = $boleta['id'];

              $tipDocMod = 2;
              if (empty($cliente["documento"])) {
                  $tipoAdq = '-';
                  $tipoDoc = '-';
                  $tipoNom = '---';
                  $tipoEmail = '';
              } else {
                  $tipoAdq = '1';
                  $tipoDoc = $cliente["documento"];
                  $tipoNom = $cliente["nombre"];
                  $tipoEmail = $cliente["correo"];
              }
          } else {
              $serie = $config["serie_factura"];
              $factura = $objventa->consulta_arreglo("Select * from factura WHERE id_venta =" . $res["id"]);
              $numero = $factura['id'];

              $tipoAdq = '6';
              $tipDocMod = 1;

              $tipoDoc = $cliente["documento"];
              $tipoNom = $cliente["nombre"];
              $tipoEmail = $cliente["correo"];
          }
          
          $cabecera = array();


          if (is_array($res)) {

              $cabecera["operacion"] = "generar_comprobante";
              $cabecera["tipo_de_comprobante"] = $tipDoc;
              $cabecera["serie"] = $serie;
              $cabecera["numero"] = str_pad($numero, 8, "0", STR_PAD_LEFT);
              $cabecera["sunat_transaction"] = 1;
              $cabecera["cliente_tipo_de_documento"] = $tipoAdq;
              $cabecera["cliente_numero_de_documento"] = $tipoDoc;
              $cabecera["cliente_denominacion"] = $tipoNom;
              $cabecera["cliente_email"] = $tipoEmail;
              $cabecera["cliente_email_1"] = "";
              $cabecera["cliente_email_2"] = "";
              $cabecera["fecha_de_emision"] = date("d-m-Y");
              $cabecera["fecha_de_vencimiento"] = "";
              $cabecera["moneda"] = 1;
              $cabecera["tipo_de_cambio"] = "";
              $cabecera["porcentaje_de_igv"] = "18.00";
              $cabecera["descuento_global"] =  floatval(0.00);
              $cabecera["total_descuento"] =  floatval(0.00);
              $cabecera["total_anticipo"] = "";
              $cabecera["total_anticipo"] = "";
              $cabecera["total_gravada"] = number_format(floatval($subtotal), 4, ".", "");
              $cabecera["total_inafecta"] = "";
              $cabecera["total_exonerada"] = "";
              $cabecera["total_igv"] = number_format(floatval($igv), 4, ".", "");
              $cabecera["total_gratuita"] = "";
              $cabecera["total_otros_cargos"] = "";
              $cabecera["total"] = number_format(floatval($total), 4, ".", "");
              $cabecera["percepcion_tipo"] = "";
              $cabecera["percepcion_base_imponible"] = "";
              $cabecera["total_percepcion"] = "";
              $cabecera["total_incluido_percepcion"] = "";
              $cabecera["detraccion"] = "false";
              $cabecera["observaciones"] = "";
              $cabecera["documento_que_se_modifica_tipo"] = $tipDocMod;
              $cabecera["documento_que_se_modifica_serie"] = $serie;
              $cabecera["documento_que_se_modifica_numero"] = str_pad($numero, 8, "0", STR_PAD_LEFT);
              $cabecera["tipo_de_nota_de_credito"] = $tipo_de_nota_de_credito;
              $cabecera["tipo_de_nota_de_debito"] = $tipo_de_nota_de_debito;
              $cabecera["enviar_automaticamente_a_la_sunat"] = "true";
              $cabecera["enviar_automaticamente_al_cliente"] = "true";
              $cabecera["codigo_unico"] = "";
              $cabecera["condiciones_de_pago"] = "";
              $cabecera["medio_de_pago"] = "";
              $cabecera["placa_vehiculo"] = "";
              $cabecera["orden_compra_servicio"] = "";
              $cabecera["tabla_personalizada_codigo"] = "";
              $cabecera["formato_de_pdf"] = "TICKET";

              $items = array();
            
            

              if (isset($itemsNotaCreditoDebito))
              {
                  foreach($itemsNotaCreditoDebito as $itemsNota){
                    
                          $item = array();
                              $unidadMedida = 'NIU';
                    
                          $objConn = new venta();
                          $producto_taxonomia = $objConn->consulta_arreglo("SELECT * FROM producto_taxonomiap WHERE id_producto = " . $itemsNota->idProducto . " AND id_taxonomiap = -1");
                          $valor_sunat = $producto_taxonomia['valor'];
                          $separar = explode("_", $valor_sunat);
                          $codigo = $separar[0];
                          
      
                          $valor_unitario = floatval($itemsNota->precio / 1.18);
                          $subtotal = floatval($valor_unitario * $itemsNota->cantidad);
          
              
                          $item["unidad_de_medida"] = $unidadMedida;
                          $item["codigo"] = $itemsNota->idProducto;
                          $item["descripcion"] =  $itemsNota->nombre;
                          $item["cantidad"] = $itemsNota->cantidad;
                          $item["codigo_producto_sunat"] = $codigo;
                          $item["valor_unitario"] = number_format($valor_unitario, 4, ".", "");
                          $item["precio_unitario"] = $itemsNota->precio;
                          $item["descuento"] = "";
                          $item["subtotal"] = number_format($subtotal, 4, ".", "");
                          $item["tipo_de_igv"] = '1';
                          $item["igv"] = number_format(floatval((($itemsNota->precio - $valor_unitario) *  $itemsNota->cantidad)), 4, ".", "");
                          $item["total"] = number_format(floatval(($itemsNota->precio * $itemsNota->cantidad)), 4, ".", "");
                          $item["anticipo_regularizacion"] = false;
                          $item["anticipo_documento_serie"] = "";
      
                          $items[] = $item;
                      }
                  }
            
              $cabecera["items"] = $items;
            
              $data_json = json_encode($cabecera);

              $ruta = $config["ruta"];
              $token = $config["token"];

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ruta);
              curl_setopt(
                  $ch,
                  CURLOPT_HTTPHEADER,
                  array(
                      'Authorization: Token token="' . $token . '"',
                      'Content-Type: application/json',
                  )
              );
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $respuesta  = curl_exec($ch);
              if (intval(curl_errno($ch)) === 0) {
                  curl_close($ch);
                  
                  $leer_respuesta = json_decode($respuesta, true);
                  if (isset($leer_respuesta['errors'])) {
                     
                  } else {
                      if (intval($_POST['nota']) == 1) {
                          $qr = "UPDATE venta SET estado_fila = 3 WHERE id = " . $_POST['idVenta'];
                          $objventa->consulta_simple($qr);
                      } else {
                          $qr = "UPDATE venta SET estado_fila = 4 WHERE id = " . $_POST['idVenta'];
                          $objventa->consulta_simple($qr);
                      }
                  }
              } else {
                  echo "NE";
              }

              echo $respuesta;
          } else {
              echo json_encode(0);
          }
        */
    
    }
    
    public function deleteSale($sale_reservation_id, $tipo){
     
        if($tipo == 1){// Venta=1 - Alquiler=2
          
            $sale = Sale::withTrashed()->where('id','=',$sale_reservation_id)->first();
          
        }else{
        
            $sale = Sale::withTrashed()->where('reservation_id','=',$sale_reservation_id)->first();
        }
     
            
        if($sale){

        
            $products = SaleProduct::where('sale_id', '=', $sale->id)->get();
            $services = SaleService::where('sale_id', '=', $sale->id)->get();
            $storeHouse = StoreHouse::where('is_base', '=', true)->first();
        
        
            $storeHouseMovement = StoreHouseMovement::create([
                'store_house_id' => $storeHouse->id,
                'store_house_movement_type_id' => '6',
                'description' => 'Ingreso de productos por anulación de venta',
            ]);

            foreach($products as $product){
                ProductMovement::create([
                    'product_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'store_house_movement_id' => $storeHouseMovement->id,
                    'product_movement_type_id' => '5',
                ]);
            }

            foreach($services as $service){
                $serviceProducts = ServiceProduct::where('service_id', '=', $service->service_id)->get();
                
                foreach($serviceProducts as $product){
                    ProductMovement::create([
                        'product_id' => $product->product_id,
                        'quantity' => $product->quantity,
                        'store_house_movement_id' => $storeHouseMovement->id,
                        'product_movement_type_id' => '5',
                    ]);
                }
            }

        }       
    }

    public function getMAC()
    {
       /* exec("ipconfig /all", $arr, $retval);
        $ph = explode(":",$arr[14]);
        return trim($ph[1]);*/
        ob_start();
        system('getmac');
        $Content = ob_get_contents();
        ob_clean();
        return substr($Content, strpos($Content,'\\')-20, 17);
    }
}
