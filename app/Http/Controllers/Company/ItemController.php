<?php

namespace App\Http\Controllers\Company;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Company\AppController;

class ItemController extends AppController
{
    public function index(Request $request)
    {
        $items = Item::where('company_id', $this->companyId)->where('is_deleted', '!=', 1)->latest();

        if (!empty($request->get('keyword'))) {
            $items = $items->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $items = $items->paginate(10);

        return view('item.index', compact('items'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:items',
        ]);
        if ($validator->passes()) {

            $model = new Item();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->company_id = $this->companyId;
            $model->save();

            $request->session()->flash('success', 'Item added successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Item added successfully.'
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
        $item = Item::findByGuidAndCompanyId($guid, $this->companyId);

        if (empty($item)) {
            return redirect()->route('item.index');
        }

        return view('item.edit', compact('item'));
    }

    public function update($guid, Request $request)
    {
        $model = Item::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Item not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Item not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:items,name,' . $model->id . ',id',
        ]);

        if ($validator->passes()) {

            $model->company_id = $this->companyId;
            $model->name = $request->name;
            $model->save();

            $request->session()->flash('success', 'Item updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Item updated successfully.'
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
        $model = Item::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Item not found.');
            return response()->json([
                'status' => true,
                'message' => 'Item not found.'
            ]);
        }
        $model->is_deleted = 1;
        $model->save();
        $request->session()->flash('success', 'Item deleted successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }
}
