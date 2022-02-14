<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Helpers\HC;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with(['prices']);
        if ($request->title){
            $products->where('title', 'LIKE', '%' .$request->title. '%');
        }
        if ($request->description){
            $products->where('description', 'LIKE', '%' .$request->description. '%');
        }
        $products = $products->paginate($this->limitPerPage);

        return new ProductResource($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        foreach($request->prices as $value)
        {
            $price = new Price();
            $price->value = $value;

            $product->prices()->save($price);
        }

        return HC::rSuccess('New product has been added to the database.', new ProductResource($product), null, [], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return HC::rSuccess('Information about the product.', new ProductResource($product->load(['prices'])), null, [], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        $product->prices()->delete();

        foreach($request->prices as $value)
        {
            $price = new Price();
            $price->value = $value;

            $product->prices()->save($price);
        }

        return HC::rSuccess('A product has been updated in the database.', new ProductResource($product), null, [], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return HC::rSuccess('A product has been deleted from the database successfully.', [], null, [], 204);
    }
}
