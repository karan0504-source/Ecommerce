<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\FrontUsers;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthhController extends Controller
{
    public function login(){
        return view('front.account.login');

    }

    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:front_users',
            'password' => 'required|min:8|confirmed'
        ]);

        if($validator->passes()){
            $user = new FrontUsers;
            $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->password=$request->password;
            $user->save();
            session()->flash('success','You have been regitered Successfully');
            return response()->json([
                'status' => true,
                
            ]);
        } else {
            session()->flash('error','Registration not done');
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::guard('account')->attempt(['email' => $request->email,'password' => $request->password],$request->get('remember'))){
                $admin = Auth::guard('account')->user();
                // dd(Redirect::getIntendedUrl());
                if(Redirect::getIntendedUrl() == null){
                    return redirect()->route('account.profile');

                }  else {
                   return redirect(Redirect::getIntendedUrl());
                }
                
                
            } else {
               Auth::guard('account')->logout();
                return redirect()->route('account.login')
                ->withInput($request->only('email'))
                ->with('error','Either Your Email/Password is incorrect');
            }
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    
    public function profile(){
        $userId = Auth::guard('account')->user()->id;
        $country = Country::orderBy('name','ASC')->get();
        $state = State::orderBy('name','ASC')->get();
        $user = FrontUsers::where('id',$userId)->first();
        $customerAddress = CustomerAddress::where('front_user_id',$userId)->first();
        return view('front.account.profile',[
            'user' => $user,
            'countries' => $country,
            'states' => $state,
            'customerAddress' => $customerAddress
        ]);
    }

    public function updateProfile(Request $request){
        
        $userId = Auth::guard('account')->user()->id;
        $user = FrontUsers::find($userId);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:front_users,email,'.$userId.',id',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            session()->flash('success','Profile Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
    }

    public function updateAddress(Request $request){
        $user = Auth::guard('account')->user();
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'states' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->passes()) {
        CustomerAddress::updateOrCreate(
            ['front_user_id' => $user->id],
            [
                'front_user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->appartment,
                'city' => $request->city,
                'state_id' => $request->states,
                'zip' => $request->zip,
                'notes' => $request->order_notes
            ]
        );
            session()->flash('success','Address Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Address Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
    }

    public function myOrders(){
        $user = Auth::guard('account')->user();

        $orders = Order::where('front_user_id',$user->id)->orderBy('created_at','DESC')->get();
        $data=[];
        $data['orders'] = $orders;
        return view('front.account.order',$data);
    }

    public function orderDetails($id){
        $user = Auth::guard('account')->user();
        $orderDetails = Order::where('front_user_id',$user->id)->where('id',$id)->first();
        $orderItems = OrderItems::where('order_id',$id)->get();
        $numOrderItems = OrderItems::where('order_id',$id)->count();

        $data=[];
        $data['orderDetails'] = $orderDetails;
        $data['orderItems'] = $orderItems;
        $data['numOrderItems'] = $numOrderItems;

        return view('front.account.orderDetail',$data);
    }

    

    public function logOut(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success','You Successfully Logged Out');
    }

    public function wishlist(){
        $wishlists = Wishlist::where('front_user_id',Auth::guard('account')->user()->id)->with('product')->get();
        $data = [];
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist',$data);
    }

    public function removeProductFromWishlist(Request $request){
        $wishlist = wishlist::where('front_user_id',Auth::guard('account')->user()->id)->where('product_id',$request->id)->first();
        if ($wishlist == null) {
            session()->flash('error','Product already removed');
           return response()->json([
            'status' => true,
           ]);
        } else {
            wishlist::where('front_user_id',Auth::guard('account')->user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product removed successfully');
           return response()->json([
            'status' => true,
           ]);
        }
    }

    public function showChangePassword(){
        return view('front.account.change-password');
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->passes()){
            $user = FrontUsers::select('id','password')->where('id',Auth::guard('account')->user()->id)->first();
            if(!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Your Old Password is Incorrect, Please Try Again');
                return response()->json([
                    'status' => true,
                ]);
            }
            FrontUsers::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            session()->flash('success','Your have successfully change your password,  Please Login!');
            Auth::guard('account')->logout();
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
    
    public function forgotPassword(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required|email|exists:front_users,email'
        ]);

        if($validator->fails()){
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }
        DB::table('password_reset_tokens')->where('email',$request->email)->delete();
        $token = Str::random(50);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
        $user = FrontUsers::where('email',$request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have request to reset your password'
            
        ];
        // send mail here
        Mail::to($request->email)->send(new ResetPasswordMail($formData));
        return redirect()->route('front.forgotPassword')->with('success','Please check your mail inbox to reset your password');
    }

    public function resetPassword($token){
        $tokenExist = DB::table('password_reset_tokens')->where('token',$token)->first();
        if($tokenExist == null) {
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');
        }
        return view('front.account.reset_password',['token' => $token]);
    }

    public function processResetPassword(Request $request){
        $token = $request->token;
        $tokenExist = DB::table('password_reset_tokens')->where('token',$token)->first();
        if($tokenExist == null) {
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');
        }

        // password reset

        $user = FrontUsers::where('email',$tokenExist->email)->first();
        
        $validator = Validator::make($request->all(),[
            'new_password' =>'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->fails()){
            return redirect()->route('front.resetPassword',$token)->withErrors($validator);
        } 
            FrontUsers::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            DB::table('password_reset_tokens')->where('email',$user->email)->delete();
            
            return redirect()->route('account.login')->with('success','You have successfully updated your password.');

    }
}
