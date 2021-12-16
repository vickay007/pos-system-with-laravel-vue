<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order_detail;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Pos;
use DB;
use DateTime;

class PosController extends Controller
{
    public function getProduct($id)
    {
    	$product = Product::where('category_id', $id)->get();
    	return response()->json($product);
    } 

    public function orderDone(Request $request)
    {
    	$validateData = $request->validate([
    		'customer_id' => 'required',
    		'payby' => 'required',
    	]);
    	
    	$order = new Order;
    	$order->customer_id = $request->customer_id;
    	$order->qty = $request->qty;
    	$order->sub_total = $request->subtotal;
    	$order->vat = $request->vat;
    	$order->total = $request->total;
    	$order->pay = $request->pay;
    	$order->due = $request->due;
    	$order->payby = $request->payby;
    	$order->order_date = date('d/m/y');
    	$order->order_month = date('F');
    	$order->order_year = date('Y');
    	$order->save();

    	$order_id = $order->id;

    	$contents = Pos::all();

    	foreach($contents as $content){

    		$order_details = new Order_detail;
	    	$order_details->order_id = $order_id;
	    	$order_details->product_id = $content->pro_id;
	    	$order_details->pro_quantity = $content->pro_quantity;
	    	$order_details->pro_price = $content->pro_price;
	    	$order_details->sub_total = $content->sub_total;
	    	$order_details->save();

	    	DB::table('products')
	    	    ->where('id', $content->pro_id)
	    	    ->update(['product_quantity' => DB::raw('product_quantity -' .$content->pro_quantity)]);

    	}

    	DB::table('pos')->delete();
    	return response()->json('done');
    }

    public function searchOrderDate(Request $request)
    {
        $orderDate = $request->date;
        $newDate = new DateTime($orderDate);
        $done = $newDate->format('d/m/y');

        $order = DB::table('orders')
                 ->join('customers', 'orders.customer_id', 'customers.id')
                 ->select('customers.name', 'orders.*')
                 ->where('orders.order_date', $done)
                 ->get();
        return response()->json($order);
    }

    public function todaySale()
    {
        $date = date('d/m/y');
        $sale = Order::where('order_date', $date)->sum('total');
        return response()->json($sale);
    }

    public function todayIncome()
    {
        $date = date('d/m/y');
        $income = Order::where('order_date', $date)->sum('pay');
        return response()->json($income);
    }

    public function todayDue()
    {
        $date = date('d/m/y');
        $due = Order::where('order_date', $date)->sum('due');
        return response()->json($due);
    }

    public function todayExpense()
    {
        $date = date('d/m/y');
        $todayExpense = Expense::where('expense_date', $date)->sum('amount');
        return response()->json($todayExpense);
    }

    public function stockOut()
    {
        $product = Product::where('product_quantity', '<', '1')->get();
        return response()->json($product);
    }
}
