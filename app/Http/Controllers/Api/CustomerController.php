<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Image;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::latest()->get();
        return response()->json($customer);
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
            'name' => 'required|unique:customers',
            'email' => 'required|unique:customers',
            'phone' => 'required|unique:customers',
            'address' => 'required',
            'photo' => 'required',
        ]);

        if($request->photo){
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($request->photo)->resize(240,200);
            $img_url = 'backend/customer/'.$name;
            $img->save($img_url);

            $customer = new Customer;
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->photo = $img_url;
            $customer->save();
        }else{
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->save();
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
        $customer = Customer::find($id);
        return response()->json($customer);
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
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $image = $request->newphoto;

        if($image){
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($image)->resize(240,200);
            $img_url = 'backend/customer/'.$name;
            $success = $img->save($img_url);

            if($success){
                $img = Customer::find($id);
                $img_path = $img->photo;
                unlink($img_path);
                $customer->photo = $img_url;
                $customer->save();

            }
        }else{
            $oldphoto = $request->photo;
            $customer->photo = $oldphoto;
            $customer->save();
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
        $customer = Customer::find($id);
        $photo = $customer->photo;
        if($photo){
            unlink($photo);
            Customer::find($id)->delete();
        }else{
            Customer::find($id)->delete();
        }
    }
}
