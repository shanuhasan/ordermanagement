<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Models\Company;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request){

        $companies = Company::latest();

        if(!empty($request->get('keyword')))
        {
            $companies = $companies->where('name','like','%'.$request->get('keyword').'%');
        }

        $companies = $companies->paginate(10);

        return view('admin.company.index',compact('companies'));
    }
    
    public function create(){
        return view('admin.company.create');
    }

    public function store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:companies',
        ]);
        if($validator->passes()){

            $model = new Company();
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->status = $request->status;
            $model->save();

            //save image
            if(!empty($request->image_id)){
                $media = Media::find($request->image_id);
                $extArray = explode('.',$media->name);
                $ext = last($extArray);

                $newImageName = $model->id .time(). '.'.$ext;
                $sPath = public_path().'/media/'.$media->name;
                $dPath = public_path().'/uploads/company/'.$newImageName;
                File::copy($sPath,$dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();
                
            }

            $request->session()->flash('success','Comapny added successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Comapny added successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function edit($categoryId , Request $request){

        $company = Company::find($categoryId);
        if(empty($company))
        {
            return redirect()->route('admin.company.index');
        }

        return view('admin.company.edit',compact('company'));
        
    }
    public function update($comapnyId, Request $request){

        $model = Company::find($comapnyId);
        if(empty($model))
        {
            $request->session()->flash('error','Company not found.');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Company not found.'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:companies,slug,'.$model->id.',id',
        ]);

        if($validator->passes()){

            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->status = $request->status;
            $model->save();

            $oldImage = $model->image;

            //save image
            if(!empty($request->image_id)){
                $media = Media::find($request->image_id);
                $extArray = explode('.',$media->name);
                $ext = last($extArray);

                $newImageName = $model->id .time(). '.'.$ext;
                $sPath = public_path().'/media/'.$media->name;
                $dPath = public_path().'/uploads/company/'.$newImageName;
                File::copy($sPath,$dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();

                //delete old image
                // File::delete(public_path().'/uploads/company/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/company/'.$oldImage);
                
            }

            $request->session()->flash('success','Company updated successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Company updated successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function destroy($categoryId, Request $request){
        $model = Company::find($categoryId);
        if(empty($model))
        {
            $request->session()->flash('error','Company not found.');
            return response()->json([
                'status'=>true,
                'message'=>'Company not found.'
            ]);
        }

        $model->delete();

        // File::delete(public_path().'/uploads/company/thumb/'.$model->image);
        File::delete(public_path().'/uploads/company/'.$model->image);

        $request->session()->flash('success','Company deleted successfully.');

        return response()->json([
            'status'=>true,
            'message'=>'Company deleted successfully.'
        ]);

    }
}
