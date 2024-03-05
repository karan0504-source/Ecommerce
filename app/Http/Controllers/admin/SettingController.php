<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    public function showChangePassword(){
        return view('admin.change-password');
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->passes()){
            $user = User::select('id','password')->where('id',Auth::guard('admin')->user()->id)->first();
            if(!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Your Old Password is Incorrect, Please Try Again');
                return response()->json([
                    'status' => true,
                ]);
            }
            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

                session()->flash('success','Your have successfully change your password,  Please Login!');
                Auth::guard('admin')->logout();
                return response()->json([
                    'status' => true,
                ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
