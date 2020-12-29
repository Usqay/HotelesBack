<?php

namespace App\Http\Controllers;

use App\Http\Resources\SunatCodeResource;
use App\Models\SunatCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SunatCodesController extends Controller
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

        $storeHouses = SunatCode::where(function ($query) use ($q) {
            $query->where("code", "like", "%$q%")
            ->orWhere("description", "like", "%$q%");
        })
        ->paginate($paginate);

        return SunatCodeResource::collection($storeHouses);
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
     * @param  \App\Models\SunatCode  $sunatCode
     * @return \Illuminate\Http\Response
     */
    public function show(SunatCode $sunatCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SunatCode  $sunatCode
     * @return \Illuminate\Http\Response
     */
    public function edit(SunatCode $sunatCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SunatCode  $sunatCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SunatCode $sunatCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SunatCode  $sunatCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(SunatCode $sunatCode)
    {
        //
    }
}
