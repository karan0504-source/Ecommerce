<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\FrontUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index(Request $request){
    $users = FrontUsers::latest();
    if(!empty($request->get('keyword'))){
        $users = $users->where('name','like','%'.$request->get('keyword').'%');
    }

    $users = $users->paginate(10);
    return view('admin.users.list',[
        'users' => $users
    ]);
   }

    public function create(){
        return view('admin.users.create');
    }

public function store(Request $request){
    $validator =  Validator::make($request->all(),[
        'name' => 'required',
        'email' => 'required|email|unique:front_users',
        'password' => 'required',
        'phone' => 'required',
    ]);

    if($validator->passes()){
        $user = new FrontUsers;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->save();
        session()->flash('success','User Added Successfully');

        return response()->json([
            'status'=>true,
            'message'=> "User Added Successfully"
        ]);
    } else {
        return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);
    }
}

public function edit(Request $request,$id){
    $user = FrontUsers::find($id);
    if(empty($user)){
        session()->flash('error','User not Found');
        return redirect()->route('users.index');
    }
    return view('admin.users.edit',compact('user'));
}

public function update(Request $request,$id){

    $user = FrontUsers::find($id);
    if(empty($user)){
        return response()->json([
            'status' => false,
            'notFound' => true,
            'message' => 'user not found'
        ]);
    }

    $validator =  Validator::make($request->all(),[
        'name' => 'required',
        'email' => 'required|email|unique:front_users,email,'.$id.',id',
        'phone' => 'required'
    ]);

    if($validator->passes()){
        
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != '') {
            $user->password = Hash::make($request->password);
        }
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->save();
        session()->flash('success','User Updated Successfully');

        return response()->json([
            'status'=>true,
            'message'=> "User Updated Successfully"
        ]);
    } else {
        return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
        ]);
    }
}

public function destory(Request $request,$id){
    $user = FrontUsers::find($id);
    if(empty($user)){
        session()->flash('error','User not Found');
        return redirect()->route('users.index');
    }
    
    $user->delete();

    session()->flash('success','User deleted Successfully');
    return response()->json([
        'status'=>true,
        'message'=> "User deleted Successfully"
    ]);
}
}
