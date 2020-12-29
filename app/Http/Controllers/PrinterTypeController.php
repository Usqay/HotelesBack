<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrinterTypeResource;
use App\Models\PrinterType;
use Illuminate\Http\Request;

class PrinterTypeController extends Controller
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

        $printerTypes = PrinterType::where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return PrinterTypeResource::collection($printerTypes);
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
     * @param  \App\Models\PrinterType  $printerType
     * @return \Illuminate\Http\Response
     */
    public function show(PrinterType $printerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrinterType  $printerType
     * @return \Illuminate\Http\Response
     */
    public function edit(PrinterType $printerType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrinterType  $printerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrinterType $printerType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrinterType  $printerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrinterType $printerType)
    {
        //
    }
}
