<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function create(){
        $category = Category::orderBy('name','ASC')->get();
        $data['categories']=$category;
        return view('admin.sub_category.create',$data);
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();


            

            session()->flash('success','Sub-Category Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Sub-Category Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function destrtoy(Request $request,$id){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            session()->flash('errors','Record Not Found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $subCategory->delete();

        session()->flash('success','Sub-Category deleted Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Sub-Category deleted Successfully"
            ]);
    }

    public function index(Request $request){

        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list',compact('subCategories'));
    }

    public function update(Request $request,$id){

        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            session()->flash('errors','Record Not Found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();


            

            session()->flash('success','Sub-Category updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Sub-Category updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            session()->flash('errors','Record Not Found');
            return redirect()->route('sub-category.index');
        }

        $category = Category::orderBy('name','ASC')->get();
        $data['categories'] = $category;
        $data['subCategory'] = $subCategory;
        return view('admin.sub_category.edit',$data);
    }
}

