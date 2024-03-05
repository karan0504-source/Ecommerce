<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Packaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagingController extends Controller
{
    public function index(Request $request){

        $packagings = Packaging::latest();

        if(!empty($request->get('keyword'))){
            $packagings = $packagings->where('name','like','%'.$request->get('keyword').'%');
        }

        $packagings = $packagings->paginate(10);
        return view('admin.packagings.list',['packagings'=>$packagings]);
    }

    public function create(){
        return view('admin.packagings.create');
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:packagings',
        ]);

        if($validator->passes()){
            $packaging = new Packaging();
            $packaging->name = $request->name;
            $packaging->slug = $request->slug;
            $packaging->status = $request->status;
            $packaging->save();

            
            

            session()->flash('success','packaging Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "packaging Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){
        $packagings = Packaging::find($id);
        if(empty($packagings)){
            return redirect()->route('packagings.index');
        }
        return view('admin.packagings.edit',compact('packagings'));
    }

    public function update(Request $request,$id){

        $packaging = Packaging::find($id);
        if(empty($packaging)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'packaging not found'
            ]);
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:packagings,slug,'.$packaging->id.',id',
        ]);

        if($validator->passes()){
            
            $packaging->name = $request->name;
            $packaging->slug = $request->slug;
            $packaging->status = $request->status;
            $packaging->save();

            
            
            session()->flash('success','packaging Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "packaging Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function destory(Request $request,$id){
        $packaging = Packaging::find($id);
        if(empty($packaging)){
            return redirect()->route('packagings.index');
        }
        $packaging->delete();

        session()->flash('success','packaging deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=> "packaging deleted Successfully"
        ]);
    }
}
