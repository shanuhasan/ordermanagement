<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::where('is_deleted', '!=', 1)->latest();

        if (!empty($request->get('keyword'))) {
            $items = $items->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        if (!empty($request->get('company_guid'))) {
            $company = Company::findByGuid($request->get('company_guid'));
            
            $items = $items->where('company_id', '=', $company->id);
        }

        $items = $items->paginate(10);

        return view('admin.item.index', compact('items'));
    }

    public function create()
    {
        return view('admin.item.create');
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|unique:items',
    //     ]);
    //     if ($validator->passes()) {

    //         $model = new Item();
    //         $model->guid = GUIDv4();
    //         $model->name = $request->name;
    //         $model->status = $request->status;
    //         $model->company_id = $this->companyId;
    //         $model->save();

    //         $request->session()->flash('success', 'Item added successfully.');
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Item added successfully.'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    // }
    // public function edit($guid, Request $request)
    // {
    //     $item = Item::findByGuidAndCompanyId($guid, $this->companyId);

    //     if (empty($item)) {
    //         return redirect()->route('item.index');
    //     }

    //     return view('item.edit', compact('item'));
    // }

    // public function update($guid, Request $request)
    // {
    //     $model = Item::findByGuidAndCompanyId($guid, $this->companyId);
    //     if (empty($model)) {
    //         $request->session()->flash('error', 'Item not found.');
    //         return response()->json([
    //             'status' => false,
    //             'notFound' => true,
    //             'message' => 'Item not found.'
    //         ]);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|unique:items,name,' . $model->id . ',id',
    //     ]);

    //     if ($validator->passes()) {

    //         $model->company_id = $this->companyId;
    //         $model->name = $request->name;
    //         $model->status = $request->status;
    //         $model->save();

    //         $request->session()->flash('success', 'Item updated successfully.');
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Item updated successfully.'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    // }

    // public function destroy($guid, Request $request)
    // {
    //     $model = Item::findByGuidAndCompanyId($guid, $this->companyId);
    //     if (empty($model)) {
    //         $request->session()->flash('error', 'Item not found.');
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Item not found.'
    //         ]);
    //     }
    //     $model->is_deleted = 1;
    //     $model->save();
    //     $request->session()->flash('success', 'Item deleted successfully.');
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Item deleted successfully.'
    //     ]);
    // }
}
