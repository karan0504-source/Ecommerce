<?php

namespace App\Http\Controllers\admin;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class BrandController extends Controller
{
    public function index(Request $request){
    $query = Brand::query()->latest();

    if ($request->has('keyword')) {
        $query->where('name', 'like', '%' . $request->input('keyword') . '%');
    }

    $brands = $query->paginate(10);

    return view('admin.brands.list', ['brands' => $brands]);
}
    public function create(){
        return view('admin.brands.create');
    }
    public function store(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:brands',
    ]);

    if($validator->passes()){
        try {
            DB::beginTransaction();

            $brand = Brand::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ]);

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName = $brand->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/brand/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/brand/thumbs/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->save($dPath,60);

                $brand->image = $newImageName;
                $brand->save();
            }

            DB::commit();

            session()->flash('success','Brand Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Brand Added Successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>false,
                'message'=> "Error occurred while adding brand: ".$e->getMessage()
            ]);
        }
    } else {
        return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);
    }
}
    public function edit(Request $request,$id){
        $brands = Brand::find($id);
        if(empty($brands)){
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit',compact('brands'));
    }
    public function update(Request $request,$id){

        $brand = Brand::find($id);
        if(empty($brand)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'brand not found'
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);

        if($validator->passes()){
            
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            
            $oldImage = $brand->image;

            if(!empty($request->image_id)){
                $tempImage= TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $brand->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/brand/'.$newImageName;
                File::copy($sPath,$dPath);


                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/brand/thumbs/'.$newImageName;
                
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->fit(450, 600, function ($constraint) {
                //     $constraint->upsize();
                // });
                $img->save($dPath,60);

                $brand->image = $newImageName;
                $brand->save();
                
                File::delete(public_path().'/uploads/brand/thumbs/'.$oldImage);
                File::delete(public_path().'/uploads/brand/'.$oldImage);

            }
            
            session()->flash('success','Brand Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Brand Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, $id){
    $brand = Brand::find($id);

    if (empty($brand)) {
        return redirect()->route('brands.index');
    }

    // Start a database transaction
    DB::beginTransaction();

    try {
        // Delete associated image files
        File::delete([
            public_path() . '/uploads/brand/thumbs/' . $brand->image,
            public_path() . '/uploads/brand/' . $brand->image
        ]);

        // Delete the brand record
        $brand->delete();

        // Commit the transaction
        DB::commit();

        // Flash success message
        session()->flash('success', 'Brand deleted successfully');

        // Return JSON response
        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    } catch (\Exception $e) {
        // Something went wrong, rollback the transaction
        DB::rollBack();

        // Log the error
        Log::error($e->getMessage());

        // Return error response
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete brand'
        ], 500);
    }
}
}
