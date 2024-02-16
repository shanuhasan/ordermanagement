<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
use App\Models\OrderItem;
use App\Models\ReceivedItem;
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
                                ->where('is_deleted','!=','1')
                                ->where('status','=','1');

        if(!empty($request->get('name')))
        {
            $employees = $employees->where('name','like','%'.$request->get('name').'%');
        }

        if(!empty($request->get('code')))
        {
            $employees = $employees->where('code','=',$request->get('code'));
        }

        if(!empty($request->get('phone')))
        {
            $employees = $employees->where('phone','like','%'.$request->get('phone').'%');
        }

        $employees = $employees->paginate(20);

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
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->code = $request->code;
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
            $model->code = $request->code;
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

        $orders = Order::latest()->where('employee_id',$id)
                        ->where('company_id',$companyId);

        if(!empty($request->get('size')))
        {
            $orders = $orders->where('size', $request->get('size'));
        }

        if(!empty($request->get('status')))
        {
            $orders = $orders->where('status', $request->get('status'));
        }

        $totalAmount = Order::where('employee_id',$id)
                            ->where('company_id',$companyId);

        $employeeTotalPayment = OrderItem::where('employee_id',$id)
                                            ->where('company_id',$companyId);

        if(!empty($request->get('year')))
        {
            $orders = $orders->whereYear('created_at', $request->get('year'));
            $totalAmount = $totalAmount->whereYear('created_at', $request->get('year'));
            $employeeTotalPayment = $employeeTotalPayment->whereYear('created_at', $request->get('year'));
        }else{
            $orders = $orders->whereYear('created_at', date('Y'));
            $totalAmount = $totalAmount->whereYear('created_at', date('Y'));
            $employeeTotalPayment = $employeeTotalPayment->whereYear('created_at', date('Y'));
        }

        $orders = $orders->paginate(20);
        $totalAmount = $totalAmount->sum('total_amount');
        $employeeTotalPayment = $employeeTotalPayment->sum('amount');


        $data['orders'] = $orders;
        $data['employeeId'] = $id;
        $data['totalAmount'] = $totalAmount;
        $data['employeeTotalPayment'] = $employeeTotalPayment;

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
            'size'=>'required',
            'qty'=>'required',
            'rate'=>'required',
        ]);

        if($validator->passes()){
            $model = new Order();
            $model->company_id = $companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->size = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->status = $request->status;
            $model->date = $request->date;
            $model->total_amount = $request->qty * $request->rate;

            if($model->save())
            {
                if($request->status == 'Completed')
                {
                    $rModel = new ReceivedItem();
                    $rModel->order_id = $model->id;
                    $rModel->company_id = $companyId;
                    $rModel->employee_id = $request->employee_id;
                    $rModel->qty = $request->qty;
                    $rModel->save();
                }
            }

            // if($request->qty == receivedItems($model->id))
            // {
            //     $model->status = 'Completed';
            //     $model->save();
            // }

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
            return redirect()->back();
        }

        $orderDetail = OrderItem::where('order_id',$order->id)->get();

        $pendingQty = $order->qty - receivedItems($orderId);

        $data['order'] = $order;
        $data['orderDetail'] = $orderDetail;
        $data['employeeId'] = $employeeId;
        $data['pendingItem'] = $pendingQty;

        return view('employee.order-edit',$data);
        
    }

    public function orderUpdate($id, Request $request){

        $companyId = Auth::guard('web')->user()->company_id;
        $model = Order::find($id);
        $pendingQty = $model->qty - receivedItems($id);
        if(empty($model))
        {
            return redirect()->route('employee.index')->with('error','Order not found.');
        }

        $validator = Validator::make($request->all(),[
            'employee_id'=>'required',
            'particular'=>'required',
            'size'=>'required',
            'qty'=>'required',
            'rate'=>'required',
        ]);

        if($validator->passes()){

            $model->company_id = $companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->size = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->total_amount = $request->qty * $request->rate;
            $model->status = $request->status;
            $model->date = $request->date;
            if($model->save())
            {
                $rModel = new ReceivedItem();
                $rModel->order_id = $id;
                $rModel->company_id = $companyId;
                $rModel->employee_id = $request->employee_id;

                if(!empty($request->received_qty) && $request->status == 'Pending' && ($request->received_qty <= $pendingQty))
                {
                    $rModel->qty = $request->received_qty;
                    $rModel->save();
                }

                if($request->status == 'Completed' && !empty($pendingQty) && $pendingQty > 0)
                {
                    $rModel->qty = $pendingQty;
                    $rModel->save();
                }
            }

            if($request->qty == receivedItems($id))
            {
                $model->status = 'Completed';
                $model->save();
            }

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

    public function orderView($employeeId,$orderId){

        if(empty(employeeExist($employeeId)))
        {
            return redirect()->route('employee.index');
        }

        $companyId = Auth::guard('web')->user()->company_id;

        $items = ReceivedItem::where('order_id',$orderId)
                                ->where('company_id', $companyId)
                                    ->paginate(30);

        return view('employee.order-view',[
            'items' => $items,
            'employeeId'=>$employeeId,
            'orderId'=>$orderId
        ]);
        
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
                $model->payment_method = $request->payment_method;
                $model->save();
            }
            return redirect()->back()->with('success','Payment updated successfully.');
        }else{
            return Redirect::back()->withErrors($validator);
        }
        
    }

    public function singlePrint($employeeId,$orderId)
    {
        if(empty(employeeExist($employeeId)))
        {
            return redirect()->route('employee.index');
        }

        $companyId = Auth::guard('web')->user()->company_id;

        // $order = Order::where('employee_id',$employeeId)
        //                 ->where('company_id',$companyId)
        //                 ->where('id',$orderId)
        //                 ->first();

        $items = ReceivedItem::where('order_id',$orderId)
                                ->where('company_id', $companyId)
                                ->get();

        return view('employee.single-print',[
            'items'=>$items,
            'employeeId'=>$employeeId,
        ]);
        
    }
    public function receivedPieceHistory(Request $request, $employeeId)
    {
        if(empty(employeeExist($employeeId)))
        {
            return redirect()->route('employee.index');
        }

        $companyId = Auth::guard('web')->user()->company_id;

        $items = ReceivedItem::where('company_id', $companyId)
                                ->where('employee_id', $employeeId)
                                ->orderBy('id','DESC');
                                

        if(!empty($request->get('year')))
        {
            $items = $items->whereYear('created_at', $request->get('year'));
        }else{
            $items = $items->whereYear('created_at', date('Y'));
        }

        $items = $items->get();

        return view('employee.received-piece-history',[
            'items'=>$items,
            'employeeId'=>$employeeId,
        ]);
        
    }

    public function orderPrint(Request $request, $employeeId)
    {
        if(empty(employeeExist($employeeId)))
        {
            return redirect()->route('employee.index');
        }

        $companyId = Auth::guard('web')->user()->company_id;

        $orders = Order::latest()->where('employee_id',$employeeId)
                        ->where('company_id',$companyId);

        $paymentHistory = OrderItem::latest()->where('employee_id',$employeeId)
                            ->where('company_id',$companyId);

        if(!empty($request->get('year')))
        {
            $orders = $orders->whereYear('created_at', $request->get('year'));
            $paymentHistory = $paymentHistory->whereYear('created_at', $request->get('year'));
        }else{
            $orders = $orders->whereYear('created_at', date('Y'));
            $paymentHistory = $paymentHistory->whereYear('created_at', date('Y'));
        }

        $orders = $orders->get();
        $paymentHistory = $paymentHistory->get();

        return view('employee.print',[
            'orders'=>$orders,
            'paymentHistory'=>$paymentHistory,
            'employeeId'=>$employeeId,
        ]);
        
    }

    public function items(Request $request)
    {
        $companyId = Auth::guard('web')->user()->company_id;

        $orders = Order::latest()
                        ->where('company_id',$companyId);

        if(!empty($request->get('date')))
        {
            $orders = $orders->whereDate('created_at','=',$request->get('date'));
        }

        if(!empty($request->get('particular')))
        {
            $orders = $orders->where('particular','like', '%'.$request->get('particular').'%');
        }

        if(!empty($request->get('employee_id')))
        {
            $orders = $orders->where('employee_id','=', $request->get('employee_id'));
        }

        if(!empty($request->get('status')))
        {
            $orders = $orders->where('status','=', $request->get('status'));
        }
        
        $orders = $orders->paginate(20);

        $data['orders'] = $orders;

        return view('employee.items',$data);

    }

    public function paymentHistory($id,Request $request)
    {
        $companyId = Auth::guard('web')->user()->company_id;

        if(empty(employeeExist($id)))
        {
            return redirect()->route('employee.index');
        }
        $data['employeeId'] = $id;

        // $totalAmount = Order::where('employee_id',$id)
        //                     ->where('company_id',$companyId)
        //                     ->sum('total_amount');

        // $employeeTotalPayment = OrderItem::where('employee_id',$id)
        //             ->where('company_id',$companyId)
        //             ->sum('amount');

        $employeePaymentHistory = OrderItem::latest()->where('employee_id',$id)
                                    ->where('company_id',$companyId);

        if(!empty($request->get('year')))
        {
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', $request->get('year'));
        }else{
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', date('Y'));
        }

        $employeePaymentHistory = $employeePaymentHistory->get();

        // $data['totalAmount'] = $totalAmount;
        // $data['employeeTotalPayment'] = $employeeTotalPayment;
        $data['employeePaymentHistory'] = $employeePaymentHistory;

        return view('employee.payment-history',$data);

    }
}
