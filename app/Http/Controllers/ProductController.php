<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the All Products.
     */
    public function index()
    {
        try {
            $Product = Product::where('status', 'active')->paginate();
            return $Product;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'slug' => 'required',
                'price' => 'required',
                'description' => 'nullable'
            ]);

            DB::beginTransaction();
            $productName = $request->name;
            $isExist = Product::where('name', $productName)->count();

            if ($isExist) {
                return "Product Exist";
            }

            $data = $request->all();

            $Product = Product::create($data);

            // $Product->save();
            if ($Product) {
                DB::commit();
                return $Product;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $product = Product::find($id);
            return $product;
        } catch (\Throwable $th) {
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();


            $product = Product::find($id);
            $product->update($request->all());
            DB::commit();

            return $product;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $product=Product::find($id);

            if($product){
                $product->destroy($id);
                DB::commit();
                return "Product deleted";
            }


            return "Product Does not exist";

        } catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }
    }


    /**
     * Product Search
     */
    function productSearch(Request $request)
    {
        try {
            // $request->validate([
            //     'name'=>'required'
            // ]);

            $productName = $request->name;
            $products = Product::where('name', 'like', '%' . $productName . '%')->paginate();
            return $products;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
//code...