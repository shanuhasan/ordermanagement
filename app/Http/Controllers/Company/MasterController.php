<?php

namespace App\Http\Controllers\Company;

use App\Models\Employee;
use App\Models\MasterOrder;
use Illuminate\Http\Request;
use App\Models\MasterOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Company\AppController;

class MasterController extends AppController
{
    public function index(Request $request)
    {
        $employees = Employee::latest()
            ->where('company_id', $this->companyId)
            ->where('is_deleted', '!=', '1')
            ->where('type', '=', Employee::MASTER)
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

        return view('master.index', [
            'employees' => $employees
        ]);
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
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
            $model->address = $request->address;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->type = Employee::MASTER;
            $model->save();

            session()->flash('success', 'Master added successfully.');
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
            return redirect()->route('master.index');
        }

        return view('master.edit', compact('employee'));
    }

    public function update($guid, Request $request)
    {

        // if (empty(employeeExist($id))) {
        //     return redirect()->route('employee.index');
        // }

        $model = Employee::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Employee not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Employee not found.'
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

            $request->session()->flash('success', 'Master updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Master updated successfully.'
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
            $request->session()->flash('error', 'Master not found.');
            return response()->json([
                'status' => true,
                'message' => 'Master not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Master deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Master deleted successfully.'
        ]);
    }

    public function order($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);
        $id = $employee->id;

        $orders = MasterOrder::latest()->where('employee_id', $id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('size'))) {
            $orders = $orders->where('size_id', $request->get('size'));
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

        return view('master.order', $data);
    }

    public function orderCreate($guid)
    {
        $employee = Employee::findByGuid($guid);
        $data['employee'] = $employee;
        return view('master.order-create', $data);
    }

    public function orderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            // 'rate' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new MasterOrder();
            $model->company_id = $this->companyId;
            $model->employee_id = $request->employee_id;
            $model->item_id = $request->item_id;
            $model->size_id = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->date = $request->date;
            $model->printing_name = $request->printing_name;
            $model->total_amount = $request->qty * $request->rate;
            $model->save();

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

        $order = MasterOrder::where('id', $orderId)
            ->where('employee_id', $employee->id)
            ->first();

        if (empty($order)) {
            return redirect()->back();
        }


        $data['order'] = $order;
        $data['employee'] = $employee;

        return view('master.order-edit', $data);
    }

    public function orderUpdate($id, Request $request)
    {
        $model = MasterOrder::find($id);
        if (empty($model)) {
            return redirect()->route('employee.index')->with('error', 'Order not found.');
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            // 'rate' => 'required',
        ]);

        if ($validator->passes()) {

            $model->company_id = $this->companyId;
            $model->employee_id = $request->employee_id;
            $model->item_id = $request->item_id;
            $model->size_id = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->total_amount = $request->qty * $request->rate;
            $model->date = $request->date;
            $model->printing_name = $request->printing_name;
            $model->save();

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

    public function amount($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);

        $totalAmount = MasterOrder::where('employee_id', $employee->id)
            ->where('company_id', $this->companyId);
        $employeeTotalPayment = MasterOrderDetail::where('employee_id', $employee->id)
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

        return view('master.amount', $data);
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
                $model = new MasterOrderDetail();
                $model->company_id = $companyId;
                $model->employee_id = $request->employee_id;
                $model->amount = $request->amount;
                $model->date = $request->date;
                $model->payment_method = $request->payment_method;
                $model->payment_name = $request->payment_name;
                $model->save();
            }
            return redirect()->back()->with('success', 'Payment updated successfully.');
        } else {
            return Redirect::back()->withErrors($validator);
        }
    }

    public function paymentHistory($guid, Request $request)
    {
        $employee = Employee::findByGuid($guid);

        $employeePaymentHistory = MasterOrderDetail::latest()->where('employee_id', $employee->id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('year'))) {
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', $request->get('year'));
        } else {
            $employeePaymentHistory = $employeePaymentHistory->whereYear('created_at', date('Y'));
        }

        $employeePaymentHistory = $employeePaymentHistory->get();

        $data['employee'] = $employee;
        $data['employeePaymentHistory'] = $employeePaymentHistory;

        return view('master.payment-history', $data);
    }

    public function deleteOrder($id, Request $request)
    {
        $model = MasterOrder::findByIdAndCompanyId($id, $this->companyId);
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
