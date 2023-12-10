<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::guard('web')->user()->company_id;
        $employees = Employee::latest()
                                ->where('company_id',$companyId)
                                ->where('is_deleted','!=','1');

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
        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }

        $employee = Employee::find($id);
        if(empty($employee))
        {
            return redirect()->route('employee.index');
        }

        return view('employee.edit',compact('employee'));        
    }

    public function update($id, Request $request){

        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }

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
        
        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }
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

    public function order($id,Request $request)
    {
        $companyId = Auth::guard('web')->user()->company_id;

        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }

        $orders = Order::where('employee_id',$id)
                        ->where('company_id',$companyId)
                        ->paginate(10);
        $data['orders'] = $orders;
        $data['employeeId'] = $id;

        $totalAmount = Order::where('employee_id',$id)
                            ->where('company_id',$companyId)
                            ->sum('total_amount');

        $employeeTotalPayment = OrderItem::where('employee_id',$id)
                                        ->where('company_id',$companyId)
                                        ->sum('amount');

        $employeePaymentHistory = OrderItem::where('employee_id',$id)
                                    ->where('company_id',$companyId)
                                    ->get();

        $data['totalAmount'] = $totalAmount;
        $data['employeeTotalPayment'] = $employeeTotalPayment;
        $data['employeePaymentHistory'] = $employeePaymentHistory;

        return view('employee.order',$data);

    }

    public function orderCreate($id){
        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }
        $employeeId = $id;
        return view('employee.order-create',compact('employeeId'));
    }

    public function orderStore(Request $request){
        $companyId = Auth::guard('web')->user()->company_id;

        $validator = Validator::make($request->all(),[
            'employee_id'=>'required',
            'particular'=>'required',
            'qty'=>'required',
            'rate'=>'required',
        ]);

        if($validator->passes()){
            $model = new Order();
            $model->company_id = $companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->total_amount = $request->qty * $request->rate;
            $model->save();

            session()->flash('success','Add New added successfully.');
            return response()->json([
                'status'=>true,
                'employeeId'=>$request->employee_id,
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function orderEdit($employeeId,$orderId){

        if(empty(employeeExist($employeeId)))
        {
            return redirect()->route('employee.index');
        }
        $order = Order::where('id',$orderId)
                        ->where('employee_id',$employeeId)
                        ->first();
        if(empty($order))
        {
            return redirect()->route('orders.index');
        }

        $orderDetail = OrderItem::where('order_id',$order->id)->get();

        $data['order'] = $order;
        $data['orderDetail'] = $orderDetail;
        $data['employeeId'] = $employeeId;

        return view('employee.order-edit',$data);
        
    }

    public function orderUpdate($id, Request $request){

        $companyId = Auth::guard('web')->user()->company_id;
        $model = Order::find($id);
        if(empty($model))
        {
            return redirect()->route('employee.index')->with('error','Order not found.');
        }

        $validator = Validator::make($request->all(),[
            'employee_id'=>'required',
            'particular'=>'required',
            'qty'=>'required',
            'rate'=>'required',
        ]);

        if($validator->passes()){

            $model->company_id = $companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->total_amount = $request->qty * $request->rate;
            $model->save();

            session()->flash('success','Updated successfully.');
            return response()->json([
                'status'=>true,
                'employeeId'=>$request->employee_id,
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function orderPayment(Request $request)
    {
        if(empty(employeeExist($request->employee_id)))
        {
            return redirect()->route('employee.index');
        }
        $companyId = Auth::guard('web')->user()->company_id;
        $validator = Validator::make($request->all(),[
            'amount'=>'required|numeric',
        ]);

        if($validator->passes())
        {
            if(!empty($request->amount) && $request->amount > 0)
            {
                $model = new OrderItem();
                $model->company_id = $companyId;
                $model->employee_id = $request->employee_id;
                $model->amount = $request->amount;
                $model->save();
            }
            return redirect()->back()->with('success','Payment updated successfully.');
        }else{
            return Redirect::back()->withErrors($validator);
        }
        
    }
}
