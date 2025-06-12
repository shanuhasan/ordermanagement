<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Models\Employee;
use App\Models\OrderItem;
use App\Models\ReceivedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Company\AppController;

class ContractorController extends AppController
{
    public function index(Request $request)
    {
        $employees = Employee::latest()
            ->where('company_id', $this->companyId)
            ->where('is_deleted', '!=', '1')
            ->where('type', '=', Employee::CONTRACTOR)
            ->where('status', '=', '1');

        if (!empty($request->get('name'))) {
            $employees = $employees->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('code'))) {
            $employees = $employees->where('code', '=', $request->get('code'));
        }

        if (!empty($request->get('phone'))) {
            $employees = $employees->where('phone', 'like', '%' . $request->get('phone') . '%');
        }

        $employees = $employees->paginate(100);

        return view('contractor.index', [
            'employees' => $employees
        ]);
    }

    public function create()
    {
        return view('contractor.create');
    }

    public function storeContractor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'status' => 'required',
            // 'code'=>'required|unique:employees',
        ]);

        if ($validator->passes()) {
            $model = new Employee();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->code = $request->code;
            $model->address = $request->address;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->type = Employee::CONTRACTOR;
            $model->save();

            session()->flash('success', 'Contractor added successfully.');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($guid, Request $request)
    {
        $employee = Employee::findByGuidAndCompanyId($guid, $this->companyId);

        if (empty($employee)) {
            return redirect()->route('contractor.index');
        }

        return view('contractor.edit', compact('employee'));
    }

    public function update($guid, Request $request)
    {

        // if (empty(employeeExist($id))) {
        //     return redirect()->route('employee.index');
        // }

        $model = Employee::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Contractor not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Contractor not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'status' => 'required',
            // 'code'=>'required|unique:employees,code,'.$id.',id',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->code = $request->code;
            $model->address = $request->address;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success', 'Contractor updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Contractor updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($guid, Request $request)
    {
        $model = Employee::findByGuidAndCompanyId($guid, $this->companyId);

        if (empty($model)) {
            $request->session()->flash('error', 'Contractor not found.');
            return response()->json([
                'status' => true,
                'message' => 'Contractor not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Contractor deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Contractor deleted successfully.'
        ]);
    }

    public function order($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);
        $id = $employee->id;

        $orders = Order::latest()->where('employee_id', $id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('size'))) {
            $orders = $orders->where('size', $request->get('size'));
        }

        if (!empty($request->get('item'))) {
            $orders = $orders->where('item_id', $request->get('item'));
        }

        if (!empty($request->get('status'))) {
            $orders = $orders->where('status', $request->get('status'));
        }

        if (!empty($request->get('year'))) {
            $orders = $orders->whereYear('created_at', $request->get('year'));
        } else {
            $orders = $orders->whereYear('created_at', date('Y'));
        }

        $orders = $orders->paginate(50);

        $data['orders'] = $orders;
        $data['employee'] = $employee;

        return view('contractor.order', $data);
    }

    public function orderCreate($guid)
    {
        $employee = Employee::findByGuid($guid);
        $data['employee'] = $employee;
        return view('contractor.order-create', $data);
    }

    public function orderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            'rate' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new Order();
            $model->company_id = $this->companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->item_id = $request->item_id;
            $model->size = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->status = $request->status;
            $model->date = $request->date;
            $model->total_amount = $request->qty * $request->rate;

            if ($model->save()) {
                if ($request->status == 'Completed') {
                    $rModel = new ReceivedItem();
                    $rModel->order_id = $model->id;
                    $rModel->company_id = $this->companyId;
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

            session()->flash('success', 'Added successfully.');
            return response()->json([
                'status' => true,
                'employeeId' => $request->employee_id,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function orderEdit($guid, $orderId)
    {
        $employee = Employee::findByGuid($guid);

        $order = Order::where('id', $orderId)
            ->where('employee_id', $employee->id)
            ->first();

        if (empty($order)) {
            return redirect()->back();
        }

        $orderDetail = OrderItem::where('order_id', $order->id)->get();

        $pendingQty = $order->qty - receivedItems($orderId);

        $data['order'] = $order;
        $data['orderDetail'] = $orderDetail;
        $data['employee'] = $employee;
        $data['pendingItem'] = $pendingQty;

        return view('contractor.order-edit', $data);
    }

    public function orderUpdate($id, Request $request)
    {
        $model = Order::find($id);
        $pendingQty = $model->qty - receivedItems($id);
        if (empty($model)) {
            return redirect()->route('employee.index')->with('error', 'Order not found.');
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            'rate' => 'required',
        ]);

        if ($validator->passes()) {

            $model->company_id = $this->companyId;
            $model->employee_id = $request->employee_id;
            $model->particular = $request->particular;
            $model->item_id = $request->item_id;
            $model->size = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->total_amount = $request->qty * $request->rate;
            $model->status = $request->status;
            $model->date = $request->date;
            if ($model->save()) {
                $rModel = new ReceivedItem();
                $rModel->order_id = $id;
                $rModel->company_id = $this->companyId;
                $rModel->employee_id = $request->employee_id;

                if (!empty($request->received_qty) && $request->status == 'Pending' && ($request->received_qty <= $pendingQty)) {
                    $rModel->qty = $request->received_qty;
                    $rModel->save();
                }

                if ($request->status == 'Completed' && !empty($pendingQty) && $pendingQty > 0) {
                    $rModel->qty = $pendingQty;
                    $rModel->save();
                }
            }

            if ($request->qty == receivedItems($id)) {
                $model->status = 'Completed';
                $model->save();
            }

            session()->flash('success', 'Updated successfully.');
            return response()->json([
                'status' => true,
                'employeeId' => $request->employee_id,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function orderView($guid, $orderId)
    {
        $employee = Employee::findByGuid($guid);

        $items = ReceivedItem::where('order_id', $orderId)
            ->where('company_id', $this->companyId)
            ->paginate(30);

        return view('contractor.order-view', [
            'items' => $items,
            'employee' => $employee,
            'orderId' => $orderId
        ]);
    }

    public function orderPayment(Request $request)
    {
        if (empty(employeeExist($request->employee_id))) {
            return redirect()->route('employee.index');
        }
        $companyId = Auth::guard('web')->user()->company_id;
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            if (!empty($request->amount) && $request->amount > 0) {
                $model = new OrderItem();
                $model->company_id = $companyId;
                $model->employee_id = $request->employee_id;
                $model->amount = $request->amount;
                $model->created_at = $request->date;
                $model->payment_method = $request->payment_method;
                $model->save();
            }
            return redirect()->back()->with('success', 'Payment updated successfully.');
        } else {
            return Redirect::back()->withErrors($validator);
        }
    }

    public function singlePrint($guid, $orderId)
    {
        $employee = Employee::findByGuid($guid);

        $items = ReceivedItem::where('order_id', $orderId)
            ->where('company_id', $this->companyId)
            ->get();

        return view('contractor.single-print', [
            'items' => $items,
            'employee' => $employee,
        ]);
    }

    public function receivedPieceHistory(Request $request, $guid)
    {
        $employee = Employee::findByGuid($guid);

        $items = ReceivedItem::where('company_id', $this->companyId)
            ->where('employee_id', $employee->id)
            ->orderBy('id', 'DESC');


        if (!empty($request->get('year'))) {
            $items = $items->whereYear('created_at', $request->get('year'));
        } else {
            $items = $items->whereYear('created_at', date('Y'));
        }

        $items = $items->get();

        return view('contractor.received-piece-history', [
            'items' => $items,
            'employee' => $employee,
        ]);
    }

    public function orderPrint(Request $request, $guid)
    {
        $employee = Employee::findByGuid($guid);
        $employeeId = $employee->id;

        $orders = Order::latest()->where('employee_id', $employeeId)
            ->where('company_id', $this->companyId);

        $paymentHistory = OrderItem::latest()->where('employee_id', $employeeId)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('year'))) {
            $orders = $orders->whereYear('created_at', $request->get('year'));
            $paymentHistory = $paymentHistory->whereYear('created_at', $request->get('year'));
        } else {
            $orders = $orders->whereYear('created_at', date('Y'));
            $paymentHistory = $paymentHistory->whereYear('created_at', date('Y'));
        }

        $orders = $orders->get();
        $paymentHistory = $paymentHistory->get();

        return view('contractor.print', [
            'orders' => $orders,
            'paymentHistory' => $paymentHistory,
            'employee' => $employee,
        ]);
    }

    public function items(Request $request)
    {
        $companyId = Auth::guard('web')->user()->company_id;

        $orders = Order::latest()
            ->where('company_id', $companyId);

        if (!empty($request->get('date'))) {
            $orders = $orders->whereDate('created_at', '=', $request->get('date'));
        }

        if (!empty($request->get('particular'))) {
            $orders = $orders->where('particular', 'like', '%' . $request->get('particular') . '%');
        }

        if (!empty($request->get('employee_id'))) {
            $orders = $orders->where('employee_id', '=', $request->get('employee_id'));
        }

        if (!empty($request->get('status'))) {
            $orders = $orders->where('status', '=', $request->get('status'));
        }

        $orders = $orders->paginate(20);

        $data['orders'] = $orders;

        return view('contractor.items', $data);
    }

    public function paymentHistory($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);

        $employeePaymentHistory = OrderItem::latest()->where('employee_id', $employee->id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('year'))) {
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', $request->get('year'));
        } else {
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', date('Y'));
        }

        $employeePaymentHistory = $employeePaymentHistory->get();

        $data['employee'] = $employee;
        $data['employeePaymentHistory'] = $employeePaymentHistory;

        return view('contractor.payment-history', $data);
    }

    public function amount($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);

        $totalAmount = Order::where('employee_id', $employee->id)
            ->where('company_id', $this->companyId);
        $employeeTotalPayment = OrderItem::where('employee_id', $employee->id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('year'))) {
            $totalAmount = $totalAmount->whereYear('created_at', $request->get('year'));
            $employeeTotalPayment = $employeeTotalPayment->whereYear('created_at', $request->get('year'));
        } else {
            $totalAmount = $totalAmount->whereYear('created_at', date('Y'));
            $employeeTotalPayment = $employeeTotalPayment->whereYear('created_at', date('Y'));
        }

        $totalAmount = $totalAmount->sum('total_amount');
        $employeePaymentHistory = $employeeTotalPayment->get();
        $employeeTotalPayment = $employeeTotalPayment->sum('amount');

        $data['employee'] = $employee;
        $data['totalAmount'] = $totalAmount;
        $data['employeeTotalPayment'] = $employeeTotalPayment;
        $data['employeePaymentHistory'] = $employeePaymentHistory;

        return view('contractor.amount', $data);
    }

    public function deleteOrder($id, Request $request)
    {
        $model = Order::findByIdAndCompanyId($id, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Not found.');
            return response()->json([
                'status' => true,
                'message' => 'Not found.'
            ]);
        }
        $model->delete();
        $request->session()->flash('success', 'Order Deleted Successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Order Deleted Successfully.'
        ]);
    }
}
