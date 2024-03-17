<?php

namespace App\Http\Controllers\Admin;

use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class YearController extends Controller
{
    public function index(Request $request)
    {
        $years = Year::orderBy('name', 'ASC');

        if (!empty($request->get('keyword'))) {
            $years = $years->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $years = $years->paginate(10);

        return view('admin.year.index', compact('years'));
    }

    public function create()
    {
        return view('admin.year.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:years',
        ]);
        if ($validator->passes()) {

            $model = new Year();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->save();

            $request->session()->flash('success', 'Year added successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Year added successfully.'
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
        $year = Year::findByGuid($guid);
        if (empty($year)) {
            return redirect()->route('admin.year.index');
        }

        return view('admin.year.edit', compact('year'));
    }
    public function update($guid, Request $request)
    {

        $model = Year::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Year not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Year not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:years,name,' . $model->id . ',id',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->save();

            $request->session()->flash('success', 'Year updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Year updated successfully.'
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
        $model = Year::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Year not found.');
            return response()->json([
                'status' => true,
                'message' => 'Year not found.'
            ]);
        }

        $model->delete();

        $request->session()->flash('success', 'Year deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Year deleted successfully.'
        ]);
    }
}
