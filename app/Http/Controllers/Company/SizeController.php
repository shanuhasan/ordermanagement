<?php

namespace App\Http\Controllers\Company;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index(Request $request){

        $companyId = Auth::guard('web')->user()->company_id;

        $sizes = Size::where('company_id',$companyId)->where('is_deleted','!=',1)->latest();

        if(!empty($request->get('keyword')))
        {
            $sizes = $sizes->where('name','like','%'.$request->get('keyword').'%');
        }

        $sizes = $sizes->paginate(10);

        return view('size.index',compact('sizes'));
    }
    
    public function create(){
        return view('size.create');
    }

    public function store(Request $request){
        $companyId = Auth::guard('web')->user()->company_id;
        $validator = Validator::make($request->all(),[
            'name'=>'required',
        ]);
        if($validator->passes()){

            $model = new Size();
            $model->name = $request->name;
            $model->company_id = $companyId;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success','Size added successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Size added successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function edit($sizeId , Request $request){

        $companyId = Auth::guard('web')->user()->company_id;
        $size = Size::where('id',$sizeId)->where('company_id',$companyId)->first();
        if(empty($size))
        {
            return redirect()->route('size.index');
        }

        return view('size.edit',compact('size'));
        
    }

    public function update($sizeId, Request $request){
        $companyId = Auth::guard('web')->user()->company_id;

        $model = Size::where('id',$sizeId)->where('company_id',$companyId)->first();
        if(empty($model))
        {
            $request->session()->flash('error','Size not found.');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Size not found.'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required',
        ]);

        if($validator->passes()){

            $model->company_id = $companyId;
            $model->name = $request->name;
            $model->status = $request->status;
            $model->save();

            $request->session()->flash('success','Size updated successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Size updated successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function destroy($sizeId, Request $request){

        $companyId = Auth::guard('web')->user()->company_id;
        $model = Size::where('id',$sizeId)->where('company_id',$companyId)->first();
        if(empty($model))
        {
            $request->session()->flash('error','Size not found.');
            return response()->json([
                'status'=>true,
                'message'=>'Size not found.'
            ]);
        }
        $model->is_deleted = 1;
        $model->save();
        $request->session()->flash('success','Size deleted successfully.');
        return response()->json([
            'status'=>true,
            'message'=>'Size deleted successfully.'
        ]);

    }
}
