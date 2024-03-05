<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->image;
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp',$newName);

            //Generate Thumbnail

            $sourcePath = public_path().'/temp/'.$newName;
            $destPath = public_path().'/temp/thumbs/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumbs/'.$newName),
                'message' => 'image uploaded successfully'
            ]);
        }
    }
    
    public function destroy(Request $request,$id){
        $tempImages= TempImage::find($id);

       if(!empty($tempImages)) {
            File::delete(public_path('temp/'.$tempImages->image));
            File::delete(public_path('temp/thumbs'.$tempImages->image));

            $tempImages->delete();
        
           return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
       } else {
           return response()->json([
            'status' => false,
            'message' => 'something went wrong'
        ]);
       }
    }
}
