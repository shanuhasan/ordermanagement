<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_deleted', '!=', '1')->orderBy('id', 'DESC');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    public function create()
    {

        return view('admin.user.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'company' => 'required',
            'status' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        if ($validator->passes()) {
            $model = new User();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->email = $request->email;
            $model->phone = $request->phone;
            $model->company_id = $request->company;
            $model->status = $request->status;
            $model->password = Hash::make($request->password);

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/user/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/user/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();
            }
            $model->save();

            session()->flash('success', 'User added successfully.');
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

        $user = User::findByGuid($guid);
        if (empty($user)) {
            return redirect()->route('admin.user.index');
        }

        return view('admin.user.edit', compact('user'));
    }

    public function update($guid, Request $request)
    {

        $model = User::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'User not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'User not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $model->id . ',id',
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'company' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->phone = $request->phone;
            $model->company_id = $request->company;
            $model->status = $request->status;

            if ($request->password != "") {
                $model->password = Hash::make($request->password);
            }

            $model->save();

            $oldImage = $model->image;

            //save image
            if (!empty($request->image_id)) {
                $media = media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/user/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/user/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();

                //delete old image
                // File::delete(public_path().'/uploads/user/thumb/'.$oldImage);
                File::delete(public_path() . '/uploads/user/' . $oldImage);
            }

            $request->session()->flash('success', 'User updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully.'
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
        $model = User::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'User not found.');
            return response()->json([
                'status' => true,
                'message' => 'User not found.'
            ]);
        }

        $model->is_deleted = 1;
        $model->save();

        $request->session()->flash('success', 'User deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
