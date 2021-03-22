<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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

        $products = Product::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("name", "like", "%$q%");
        })
        ->paginate($paginate);

        return ProductResource::collection($products);
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
     * @param  \App\Http\Requests\ProductCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        try{

            DB::beginTransaction();

            $product = Product::create($request->toArray());

            $this->saveUserLog($product);

            DB::commit();

            return $this->successResponse(new ProductResource($product), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->successResponse(new ProductResource($product));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        try{

            DB::beginTransaction();

            $product->fill($request->toArray());
            $product->save();

            if($request->prices){
                foreach($request->prices as $price){
                    ProductPrice::where('product_id', '=', $product->id)
                    ->where('currency_id', '=', $price['currency_id'])
                    ->withTrashed()
                    ->update([
                        'purchase_price' => $price['purchase_price'],
                        'sale_price' => $price['sale_price'],
                    ]);
                }
            }

            $this->saveUserLog($product, 'update');

            DB::commit();

            return $this->successResponse(new ProductResource($product));
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't update data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product = Product::withTrashed()->findOrFail($product);

        if ($product->trashed()) {
            $product->restore();
            $this->saveUserLog($product, 'restore');
        } else {
            $product->delete();
            $this->saveUserLog($product, 'delete');
        }

        return $this->successResponse(new ProductResource($product));
    }
}
