<?php

namespace App\Http\Controllers\admin;
use App\Models\ProductType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{
    public function index(Request $request){

        $product_types = ProductType::latest();

        if(!empty($request->get('keyword'))){
            $product_types = $product_types->where('name','like','%'.$request->get('keyword').'%');
        }

        $product_types = $product_types->paginate(10);
        return view('admin.product_types.list',['product_types'=>$product_types]);
    }

    public function create(){
        return view('admin.product_types.create');
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:product_types',
        ]);

        if($validator->passes()){
            $product_type = new ProductType();
            $product_type->name = $request->name;
            $product_type->slug = $request->slug;
            $product_type->status = $request->status;
            $product_type->save();

            
            

            session()->flash('success','Product Type Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Product Type Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){
        $product_types = ProductType::find($id);
        if(empty($product_types)){
            return redirect()->route('product_types.index');
        }
        return view('admin.product_types.edit',compact('product_types'));
    }

    public function update(Request $request,$id){

        $product_type = ProductType::find($id);
        if(empty($product_type)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Product Type not found'
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:product_types,slug,'.$product_type->id.',id',
        ]);

        if($validator->passes()){
            
            $product_type->name = $request->name;
            $product_type->slug = $request->slug;
            $product_type->status = $request->status;
            $product_type->save();

            
            
            session()->flash('success','Product Type Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Product Type Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function destory(Request $request,$id){
        $product_type = ProductType::find($id);
        if(empty($product_type)){
            return redirect()->route('product_types.index');
        }
        $product_type->delete();

        session()->flash('success','Product Type deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=> "Product Type deleted Successfully"
        ]);
    }
}
