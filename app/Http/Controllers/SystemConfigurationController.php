<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemConfigurationStoreRequest;
use App\Http\Resources\SystemConfigurationResource;
use App\Models\SystemConfiguration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SystemConfigurationController extends Controller
{
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
}
