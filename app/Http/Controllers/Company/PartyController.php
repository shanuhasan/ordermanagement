<?php

namespace App\Http\Controllers\Company;

use App\Models\Party;
use App\Models\PartyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Company\AppController;

class PartyController extends AppController
{
    public function index(Request $request)
    {
        $employees = Party::latest()
            ->where('company_id', $this->companyId)
            ->where('is_deleted', '!=', '1')
            ->where('status', '=', '1');

        if (!empty($request->get('name'))) {
            $employees = $employees->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('phone'))) {
            $employees = $employees->where('phone', 'like', '%' . $request->get('phone') . '%');
        }

        $employees = $employees->paginate(100);

        return view('party.index', [
            'employees' => $employees
        ]);
    }

    public function create()
    {
        return view('party.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new Party();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->address = $request->address;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->save();

            session()->flash('success', 'Party added successfully.');
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
        $employee = Party::findByGuidAndCompanyId($guid, $this->companyId);

        if (empty($employee)) {
            return redirect()->route('party.index');
        }

        return view('party.edit', compact('employee'));
    }

    public function update($guid, Request $request)
    {

        // if (empty(partyExist($id))) {
        //     return redirect()->route('employee.index');
        // }

        $model = Party::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Party not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Party not found.'
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
            $model->address = $request->address;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success', 'Party updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Party updated successfully.'
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
        $model = Party::findByGuidAndCompanyId($guid, $this->companyId);

        if (empty($model)) {
            $request->session()->flash('error', 'Party not found.');
            return response()->json([
                'status' => true,
                'message' => 'Party not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'Party deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Party deleted successfully.'
        ]);
    }

    public function order($guid, Request $request)
    {
        $employee = Party::findByGuid($guid);
        $id = $employee->id;

        $orders = PartyOrder::where('party_id', $id)
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

        $orders = $orders->paginate(100);

        $data['orders'] = $orders;
        $data['employee'] = $employee;

        return view('party.order', $data);
    }

    public function orderCreate($guid)
    {
        $employee = Party::findByGuid($guid);
        $data['employee'] = $employee;
        return view('party.order-create', $data);
    }

    public function orderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'party_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            'rate' => 'required',
        ]);

        if ($validator->passes()) {
            $model = new PartyOrder();
            $model->company_id = $this->companyId;
            $model->party_id = $request->party_id;
            $model->item_id = $request->item_id;
            $model->size_id = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->date = $request->date;
            $model->total_amount = $request->qty * $request->rate;

            $model->save();

            session()->flash('success', 'Added successfully.');
            return response()->json([
                'status' => true,
                'partyId' => $request->party_id,
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
        $employee = Party::findByGuid($guid);

        $order = PartyOrder::where('id', $orderId)
            ->where('party_id', $employee->id)
            ->first();

        if (empty($order)) {
            return redirect()->back();
        }

        $data['order'] = $order;
        $data['employee'] = $employee;

        return view('party.order-edit', $data);
    }

    public function orderUpdate($id, Request $request)
    {
        $model = PartyOrder::find($id);

        if (empty($model)) {
            return redirect()->route('party.index')->with('error', 'Order not found.');
        }

        $validator = Validator::make($request->all(), [
            'party_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'qty' => 'required',
            'rate' => 'required',
        ]);

        if ($validator->passes()) {

            $model->company_id = $this->companyId;
            $model->party_id = $request->party_id;
            $model->item_id = $request->item_id;
            $model->size_id = $request->size;
            $model->qty = $request->qty;
            $model->rate = $request->rate;
            $model->date = $request->date;
            $model->total_amount = $request->qty * $request->rate;

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
        $employee = Party::findByGuid($guid);

        $totalAmount = PartyOrder::where('party_id', $employee->id)
            ->where('company_id', $this->companyId);

        if (!empty($request->get('year'))) {
            $totalAmount = $totalAmount->whereYear('created_at', $request->get('year'));
        } else {
            $totalAmount = $totalAmount->whereYear('created_at', date('Y'));
        }

        $totalAmount = $totalAmount->sum('total_amount');

        $data['employee'] = $employee;
        $data['totalAmount'] = $totalAmount;

        return view('party.amount', $data);
    }

    public function orderPayment(Request $request)
    {
        if (empty(partyExist($request->party_id))) {
            return redirect()->route('party.index');
        }
        $companyId = Auth::guard('web')->user()->company_id;
        $validator = Validator::make($request->all(), [
            'credit' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            if (!empty($request->credit) && $request->credit > 0) {
                $model = new PartyOrder();
                $model->company_id = $companyId;
                $model->party_id = $request->party_id;
                $model->credit = $request->credit;
                $model->date = $request->date;
                $model->payment_method = $request->payment_method;
                $model->payment_name = $request->payment_name;
                $model->save();
            }
            return redirect()->route('party.order', $request->pguid)->with('success', 'Payment updated successfully.');
        } else {
            return Redirect::back()->withErrors($validator);
        }
    }

    public function orderAmountEdit($guid, $orderId)
    {
        $employee = Party::findByGuid($guid);

        $order = PartyOrder::where('id', $orderId)
            ->where('party_id', $employee->id)
            ->first();

        if (empty($order)) {
            return redirect()->back();
        }

        $data['order'] = $order;
        $data['employee'] = $employee;

        return view('party.order-amount-edit', $data);
    }

    public function orderAmountUpdate($id, Request $request)
    {
        $model = PartyOrder::find($id);

        if (empty($model)) {
            return redirect()->route('party.index')->with('error', 'Order not found.');
        }

        $companyId = Auth::guard('web')->user()->company_id;
        $validator = Validator::make($request->all(), [
            'credit' => 'required|numeric',
        ]);

        if ($validator->passes()) {

            $model->company_id = $companyId;
            $model->party_id = $request->party_id;
            $model->credit = $request->credit;
            $model->date = $request->date;
            $model->payment_method = $request->payment_method;
            $model->payment_name = $request->payment_name;
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

    public function orderPrint(Request $request, $guid)
    {
        $employee = Party::findByGuid($guid);
        $employeeId = $employee->id;

        $orders = PartyOrder::where('party_id', $employeeId)
            ->where('company_id', $this->companyId);


        if (!empty($request->get('year'))) {
            $orders = $orders->whereYear('created_at', $request->get('year'));
        } else {
            $orders = $orders->whereYear('created_at', date('Y'));
        }

        $orders = $orders->get();

        return view('party.print', [
            'orders' => $orders,
            'employee' => $employee,
        ]);
    }

    public function deleteOrder($id, Request $request)
    {
        $model = PartyOrder::findByIdAndCompanyId($id, $this->companyId);
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
