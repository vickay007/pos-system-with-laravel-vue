<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pos;
use App\Models\Extra;

class CartController extends Controller
{
    public function addToCart(request $request, $id){

    	$product = Product::find($id);

    	$check = Pos::where('pro_id', $id)->first();

    	if($check){
    		Pos::where('pro_id', $id)->increment('pro_quantity');
    		$product = Pos::where('pro_id', $id)->first();
	    	$product->sub_total = $product->pro_quantity * $product->pro_price;
	    	$product->save();
    	}else{
    		$addToCart = new Pos;
		    $addToCart->pro_id = $id;
		    $addToCart->pro_name = $product->product_name;
		    $addToCart->pro_quantity = 1;
		    $addToCart->pro_price = $product->selling_price;
		    $addToCart->sub_total = $product->selling_price;
		    $addToCart->save();
    	}

    }

    public function cartProduct(){
    	$allCart = Pos::latest()->get();
    	return response()->json($allCart);
    }

    public function removeCart($id){
    	$removeCart = Pos::find($id)->delete();
    }

    public function increment($id){
    	$quantity = Pos::where('id', $id)->increment('pro_quantity');
    	$product = Pos::find($id);
    	$product->sub_total = $product->pro_quantity * $product->pro_price;
    	$product->save();
    }

    public function decrement($id){
    	$quantity = Pos::where('id', $id)->decrement('pro_quantity');
    	$product = Pos::find($id);
    	$product->sub_total = $product->pro_quantity * $product->pro_price;
    	$product->save();
    }

    public function vat(){
        $vat = Extra::first();
        return response()->json($vat);
    }
}
