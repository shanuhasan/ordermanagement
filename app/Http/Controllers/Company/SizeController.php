<?php

namespace App\Http\Controllers\Company;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Company\AppController;

class SizeController extends AppController
{
    public function index(Request $request)
    {
        $sizes = Size::where('company_id', $this->companyId)->where('is_deleted', '!=', 1)->latest();

        if (!empty($request->get('keyword'))) {
            $sizes = $sizes->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $sizes = $sizes->paginate(10);

        return view('size.index', compact('sizes'));
    }

    public function create()
    {
        return view('size.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->passes()) {

            $model = new Size();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->company_id = $this->companyId;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success', 'Size added successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Size added successfully.'
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
        $size = Size::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($size)) {
            return redirect()->route('size.index');
        }

        return view('size.edit', compact('size'));
    }

    public function update($guid, Request $request)
    {
        $model = Size::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Size not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Size not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {

            $model->company_id = $this->companyId;
            $model->name = $request->name;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success', 'Size updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Size updated successfully.'
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
        $model = Size::findByGuidAndCompanyId($guid, $this->companyId);
        if (empty($model)) {
            $request->session()->flash('error', 'Size not found.');
            return response()->json([
                'status' => true,
                'message' => 'Size not found.'
            ]);
        }
        $model->is_deleted = 1;
        $model->save();
        $request->session()->flash('success', 'Size deleted successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Size deleted successfully.'
        ]);
    }
}
