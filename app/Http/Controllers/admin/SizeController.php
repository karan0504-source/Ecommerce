<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index(Request $request){

        $sizes = Sizes::latest();

        if(!empty($request->get('keyword'))){
            $sizes = $sizes->where('name','like','%'.$request->get('keyword').'%');
        }

        $sizes = $sizes->paginate(10);
        return view('admin.sizes.list',['sizes'=>$sizes]);
    }

    public function create(){
        return view('admin.sizes.create');
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sizes',
        ]);

        if($validator->passes()){
            $size = new Sizes();
            $size->name = $request->name;
            $size->slug = $request->slug;
            $size->status = $request->status;
            $size->save();

            
            

            session()->flash('success','Size Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Size Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){
        $sizes = Sizes::find($id);
        if(empty($sizes)){
            return redirect()->route('sizes.index');
        }
        return view('admin.sizes.edit',compact('sizes'));
    }

    public function update(Request $request,$id){

        $size = Sizes::find($id);
        if(empty($size)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'size not found'
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sizes,slug,'.$size->id.',id',
        ]);

        if($validator->passes()){
            
            $size->name = $request->name;
            $size->slug = $request->slug;
            $size->status = $request->status;
            $size->save();

            
            
            session()->flash('success','Size Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Size Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function destory(Request $request,$id){
        $size = Sizes::find($id);
        if(empty($size)){
            return redirect()->route('sizes.index');
        }
        $size->delete();

        session()->flash('success','Size deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=> "Size deleted Successfully"
        ]);
    }
}
