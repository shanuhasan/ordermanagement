<?php

namespace App\Http\Controllers\Company;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::latest()->where('is_deleted','!=','1');

        if(!empty($request->get('keyword')))
        {
            $employees = $employees->where('name','like','%'.$request->get('keyword').'%');
        }

        $employees = $employees->paginate(10);

        return view('employee.index',[
            'employees'=>$employees
        ]);
    }

    public function create(){

        return view('employee.create');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3',
            'phone'=>'required|numeric',
            'status'=>'required',
        ]);

        if($validator->passes())
        {
            $model = new Employee();
            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->address = $request->address;
            $model->company_id = Auth::guard('web')->user()->company_id;
            $model->status = $request->status;
            $model->save();

            session()->flash('success','Employee added successfully.');
            return response()->json([
                'status'=>true
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit($id , Request $request){

        $employee = Employee::find($id);
        if(empty($employee))
        {
            return redirect()->route('employee.index');
        }

        return view('employee.edit',compact('employee'));        
    }

    public function update($id, Request $request){

        $model = Employee::find($id);
        if(empty($model))
        {
            $request->session()->flash('error','Employee not found.');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Employee not found.'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3',
            'phone'=>'required|numeric',
            'status'=>'required',
        ]);

        if($validator->passes()){

            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->address = $request->address;
            $model->company_id = Auth::guard('web')->user()->company_id;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success','Employee updated successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Employee updated successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }

    public function destroy($id, Request $request){
        $model = Employee::find($id);
        if(empty($model))
        {
            $request->session()->flash('error','Employee not found.');
            return response()->json([
                'status'=>true,
                'message'=>'Employee not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success','Employee deleted successfully.');

        return response()->json([
            'status'=>true,
            'message'=>'Employee deleted successfully.'
        ]);

    }
}
