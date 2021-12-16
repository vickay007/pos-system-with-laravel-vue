<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Image;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::all();
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:employees|max:255',
            'email' => 'required|unique:employees',
            'phone' => 'required|unique:employees',
        ]);

        if($request->photo){
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($request->photo)->resize(240,200);
            $img_url = 'backend/employee/'.$name;
            $img->save($img_url);

            $employee = new Employee;
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->nid = $request->nid;
            $employee->joining_date = $request->joining_date;
            $employee->photo = $img_url;
            $employee->save();
        }else{
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->nid = $request->nid;
            $employee->joining_date = $request->joining_date;
            $employee->save();
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
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $employee = Employee::find($id);
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->salary = $request->salary;
        $employee->address = $request->address;
        $employee->nid = $request->nid;
        $employee->joining_date = $request->joining_date;
        $image = $request->newphoto;

        if($image){
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $img = image::make($image)->resize(240,200);
            $img_url = 'backend/employee/'.$name;
            $success = $img->save($img_url);

            if($success){
                $employee->photo = $img_url;
                $img = Employee::find($id);
                $img_path = $img->photo;
                unlink($img_path);
                $employee->save();

            }
        }else{
            $oldphoto = $request->photo;
            $employee->photo = $oldphoto;
            $employee->save();
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
        $employee = Employee::find($id);
        $photo = $employee->photo;
        if($photo){
            unlink($photo);
            Employee::find($id)->delete();
        }else{
            Employee::find($id)->delete();
        }
    }
}
