<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;


class CategoryController extends Controller
{
    public function index(Request $request){

        $categories = Category::latest();

        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list',['categories'=>$categories]);
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if($validator->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();


            if(!empty($request->image_id)){
                $tempImage= TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);


                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/category/thumbs/'.$newImageName;
                
                $img = Image::make($sPath);
                // $img->resize(300,null,function($constraint){
                //         $constraint->aspectRatio();
                //     });
                $img->resize(300,null,function($constraint){ $constraint->aspectRatio(); });
                $img->save($dPath,90);

                $category->image = $newImageName;
                $category->save();
                
            }

            session()->flash('success','Category Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Category Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){
        $category = Category::find($id);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request,$id){

        $category = Category::find($id);
        if(empty($category)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'category not found'
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){
            
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            if(!empty($request->image_id)){
                $tempImage= TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);


                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/category/thumbs/'.$newImageName;
                
                $img = Image::make($sPath);
                $img->resize(300,null,function($constraint){ $constraint->aspectRatio(); });
                // $img->resize(450, 600);
                // $img->fit(450, 600, function ($constraint) {
                //     $constraint->upsize();
                // });
                $img->save($dPath,90);

                $category->image = $newImageName;
                $category->save();
                
                File::delete(public_path().'/uploads/category/thumbs/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);

            }

            session()->flash('success','Category Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Category Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function destory(Request $request,$id){
        $category = Category::find($id);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        File::delete(public_path().'/uploads/category/thumbs/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);
        $category->delete();

        session()->flash('success','Category deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=> "Category deleted Successfully"
        ]);
    }
}
