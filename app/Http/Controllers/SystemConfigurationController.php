<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemConfigurationStoreRequest;
use App\Http\Resources\SystemConfigurationResource;
use App\Models\ElectronicVoucher;
use App\Models\SystemConfiguration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request ;

class SystemConfigurationController extends Controller
{
    public $url_fire = 'https://us-central1-licencias-usqay.cloudfunctions.net/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $systemConfigurations = SystemConfiguration::where(function ($query) use ($q) {
            $query->where("key", "like", "%$q%");
        })
        ->paginate($paginate);

        return SystemConfigurationResource::collection($systemConfigurations);
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
     * @param  \App\Http\Requests\SystemConfigurationStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemConfigurationStoreRequest $request)
    {
        try{
          
            if($request->items){
                DB::beginTransaction();
              
                foreach($request->items as $item){
                    SystemConfiguration::updateOrCreate(['key' => $item['key']], ['value' => $item['value']]);
                }
                DB::commit();
            }

            if(isset($request->logo_file)){
                $request->file('logo_file')->storeAs('public', 'logo.png');
            }

            
            return $this->successResponse(["success" => true], Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data".$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemConfiguration  $systemConfiguration
     * @return \Illuminate\Http\Response
     */
    public function show(SystemConfiguration $systemConfiguration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemConfiguration  $systemConfiguration
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemConfiguration $systemConfiguration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemConfiguration  $systemConfiguration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemConfiguration $systemConfiguration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemConfiguration  $systemConfiguration
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemConfiguration $systemConfiguration)
    {
        //
    }

    public function searchToken (Request $request){
             
        $data_json = [
            'token' =>$request->input('token')
        ];
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_fire . 'empresas/obtener_datos_token');
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                // 'Authorization: Token token="'.$token.'"',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);      
        curl_close($ch);
        return(json_decode($respuesta, true));
    }
    public function validarUso (Request $request){
             
        $data_json = [
            'token' =>$request->input('token')
        ];
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_fire . 'empresas/validar_uso');
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                // 'Authorization: Token token="'.$token.'"',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);      
        curl_close($ch);
        return(json_decode($respuesta, true));
    }

    public function getData (Request $request){
             
        $data_json = [
            'token' =>$request->input('token')
        ];
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_fire . 'empresas/get_data');
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                // 'Authorization: Token token="'.$token.'"',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);      
        curl_close($ch);
        return(json_decode($respuesta, true));
    }


    public function validarLicencia (Request $request){
        
       
        $data_json = [
            'token' => $request->input('token'),
            'mac'   => $this->getMac(),
            'ruc'   => $request->input('ruc'),
            'nombre'   => $request->input('nombre'),
            'razon_social'   => $request->input('razonSocial'),
            'nombre_comercial'   => $request->input('nombreComercial'),
            'direccion'   => $request->input('direccion'),
            'telefono'   => $request->input('telefono'),
            'ciudad'   => $request->input('ciudad'),
        ];
       
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url_fire . 'empresas/validar_licencia');
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                // 'Authorization: Token token="'.$token.'"',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);      
        curl_close($ch);
        return(json_decode($respuesta, true));
    }

    public function searchDocument($id){

        

        $document= ElectronicVoucher::find($id);
        $token =  SystemConfiguration::where('key', '=', 'billing_token')->first();
       
        if($document){

            $data_json=[
                'tipo_de_comprobante' => $document->electronic_voucher_type_id ==4 ? 3 : $document->electronic_voucher_type_id , //Mejorar esto
                'serie' => $document->serie,
                'numero' => $document->number,
                'token' => $token->value,
                'mac' => $this->getMAC()
            ];

         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url_fire . 'empresas/consultar_comprobante');
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                    // 'Authorization: Token token="'.$token.'"',
                    'Content-Type: application/json',
                )
            );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta  = curl_exec($ch);      
            curl_close($ch);
            return(json_decode($respuesta, true));
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
