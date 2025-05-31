<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadImageController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        // dd($image);

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $media = new Media();
            $media->name = $newName;
            $media->save();

            $image->move(public_path() . '/media', $newName);

            //generate thumb

            // $sourcePath = public_path().'/media/'.$newName;
            // $dPath = public_path().'/media/thumb/'.$newName;

            // $image = Image::make($sourcePath);
            // $image->fit(300,250);
            // $image->save($dPath);



            return response()->json([
                'status' => true,
                'image_id' => $media->id,
                // 'imagePath'=>asset('media/thumb/'.$newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
