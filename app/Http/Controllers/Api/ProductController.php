<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Image;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = DB::table('products')
                   ->join('categories', 'products.category_id', 'categories.id')
                   ->join('suppliers', 'products.supplier_id', 'suppliers.id')
                   ->select('categories.category_name', 'suppliers.name', 'products.*')
                   ->orderBy('products.id', 'DESC')
                   ->get();
                   return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'product_name' => 'required|max:255',
            'product_code' => 'required|unique:products|max:255',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'root' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'buying_date' => 'required',
            'product_quantity' => 'required',
        ]);

         if($request->image){
            $position = strpos($request->image, ';');
            $sub = substr($request->image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($request->image)->resize(240,200);
            $img_url = 'backend/product/'.$name;
            $img->save($img_url);

            $product = new Product;
            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->product_code = $request->product_code;
            $product->root = $request->root;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->supplier_id = $request->supplier_id;
            $product->buying_date = $request->buying_date;
            $product->image = $img_url;
            $product->product_quantity = $request->product_quantity;
            $product->save();
        }else{
            $product = new Product;
            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->product_code = $request->product_code;
            $product->root = $request->root;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->supplier_id = $request->supplier_id;
            $product->buying_date = $request->buying_date;
            $product->product_quantity = $request->product_quantity;
            $product->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->product_name = $request->product_name;
        $product->product_code = $request->product_code;
        $product->root = $request->root;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->supplier_id = $request->supplier_id;
        $product->buying_date = $request->buying_date;
        $product->product_quantity = $request->product_quantity;
        $image = $request->newImage;

        if($image){
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($image)->resize(240,200);
            $img_url = 'backend/product/'.$name;
            $success = $img->save($img_url);

            if($success){
                $product->image = $img_url;
                $img = Product::find($id);
                $img_path = $img->image;
                unlink($img_path);
                $product->save();

            }
        }else{
            $oldphoto = $request->image;
            $product->image = $oldphoto;
            $product->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $photo = $product->image;
        if($photo){
            unlink($photo);
            Product::find($id)->delete();
        }else{
            Product::find($id)->delete();
        }
    }

    public function updateStock(request $request, $id){
        $stock = Product::find($id);
        $stock->product_quantity = $request->product_quantity;
        $stock->save();
    }
}
