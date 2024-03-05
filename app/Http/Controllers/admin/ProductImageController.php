<?php

namespace App\Http\Controllers\admin;
use App\Models\ProductImage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function index(){
        
    }

    public function create(){

    }

    public function store(){
        
    }

    public function edit(){
        
    }

    public function update(Request $request){
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        
        $productImage = new ProductImage;
        $productImage->product_id = $request->id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->id.'-'.$productImage->id.'-'.time().'.'.$ext;
        // product_id => 4; product_image_id => 1;
        // 4-1-timestamp.jpg
        $productImage->image = $imageName;
        $productImage->save();

        //Large Image

        
        $destPath = public_path().'/uploads/product/large/'.$imageName;
        $image = Image::make($sourcePath);
        $image->resize(1400,null,function($constraint){
        $constraint->aspectRatio();
        });
        $image->save($destPath,60);


        //Small Image

        $destPath = public_path().'/uploads/product/small/'.$imageName;
        $image = Image::make($sourcePath);
        $image->fit(300,300);
        $image->save($destPath,60);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/small/'.$productImage->image),
            'message' => 'Image saved successfully'
        ]);

    }

    public function destroy(Request $request, $id) {
    try {
        $productImage = ProductImage::find($id);

        if (!empty($productImage)) {
            // Delete associated files
            File::delete(public_path('uploads/product/large/' . $productImage->image));
            File::delete(public_path('uploads/product/small/' . $productImage->image));

            // Delete product image record
            $productImage->delete();

            return response()->json([
                'status' => true,
                'message' => 'Image deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product image not found'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error deleting image: ' . $e->getMessage()
        ]);
    }
}

}
