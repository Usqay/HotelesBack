<?php

namespace App\Http\Controllers;

use App\Http\Resources\ElectronicVoucherTypeResource;
use App\Models\ElectronicVoucherType;
use Illuminate\Http\Request;

class ElectronicVoucherTypeController extends Controller
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

        $electronicVoucherTypes = ElectronicVoucherType::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return ElectronicVoucherTypeResource::collection($electronicVoucherTypes);
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
     * @param  \App\Models\ElectronicVoucherType  $electronicVoucherType
     * @return \Illuminate\Http\Response
     */
    public function show(ElectronicVoucherType $electronicVoucherType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ElectronicVoucherType  $electronicVoucherType
     * @return \Illuminate\Http\Response
     */
    public function edit(ElectronicVoucherType $electronicVoucherType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ElectronicVoucherType  $electronicVoucherType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ElectronicVoucherType $electronicVoucherType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ElectronicVoucherType  $electronicVoucherType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ElectronicVoucherType $electronicVoucherType)
    {
        //
    }
}
