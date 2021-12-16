<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    public function TodayOrder(){

    	$date = date('d/m/y');
	    $order = DB::table('orders')
		         ->join('customers', 'orders.customer_id', 'customers.id')
		         ->where('order_date', $date)
		         ->select('customers.name', 'orders.*')
		         ->orderBy('orders.id','DESC')
		         ->get();
		         return response()->json($order);
    }

    public function orderDetails($id)
    {
    	$orders = DB::table('orders')
    	          ->join('customers', 'orders.customer_id', 'customers.id')
    	          ->where('orders.id', $id)
    	          ->select('customers.name', 'customers.phone', 'customers.address', 'orders.*')
    	          ->first();
    	          return response()->json($orders);
    }

    public function orderDetailsAll($id)
    {
    	$details = DB::table('order_details')
    	           ->join('products', 'order_details.product_id','products.id')
    	           ->where('order_details.order_id', $id)
    	           ->select('products.product_name','products.product_code','products.image','order_details.*')
    	           ->get();
    	return response()->json($details);
    }
}
