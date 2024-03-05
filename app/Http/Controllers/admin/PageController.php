<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request){
        $pages = Pages::latest();
        if(!empty($request->get('keyword'))){
            $pages = $pages->where('name','like','%'.$request->get('keyword').'%');
        }

        $pages = $pages->paginate(10);
        return view('admin.pages.list',[
            'pages' => $pages
        ]);

    }
    public function create(){
        return view('admin.pages.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages',
        ]);

        if($validator->passes()){
            $page = new Pages;
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            session()->flash('success','Page Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Page Added Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }
    public function edit(Request $request,$id){
        $page = Pages::find($id);
        if(empty($page)){
            session()->flash('error','Page not Found');
            return redirect()->route('pages.index');
        }
        return view('admin.pages.edit',[
            'page' => $page
        ]);

    }
    public function update(Request $request,$id){
        $page = Pages::find($id);
        if(empty($page)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'page not found'
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages,slug,'.$id.',id',
        ]);

        if($validator->passes()){
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            session()->flash('success','Page Updated Successfully');

            return response()->json([
                'status'=>true,
                'message'=> "Page Updated Successfully"
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request,$id){
        $page = Pages::find($id);
        if(empty($page)){
            session()->flash('error','Page not Found');
            return redirect()->route('pages.index');
        }
        $page->delete();

        session()->flash('success','Page deleted Successfully');
        return response()->json([
            'status'=>true,
            'message'=> "Page deleted Successfully"
        ]);
    }
}
