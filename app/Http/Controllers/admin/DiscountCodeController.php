<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request){
        $coupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $coupons = $coupons->where('name','like','%'.$request->get('keyword').'%');
            $coupons = $coupons->orWhere('code','like','%'.$request->get('keyword').'%');
        }

        $coupons = $coupons->paginate(10);
        
        return view('admin.coupon.list',['coupons'=>$coupons]);
    }
    public function create(){
        return view('admin.coupon.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            // starting  date must be greater than current date
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date cannot be less than current date/time']
                    ]);
                }
            }

            // expiry date must be greater than starting date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry date must be greater than start date/time']
                    ]);
                }
            }

           $discountCode = new DiscountCoupon();
           $discountCode->code = $request->code;
           $discountCode->name = $request->name;
           $discountCode->description = $request->description;
           $discountCode->max_uses = $request->max_uses;
           $discountCode->max_uses_user = $request->max_uses_user;
           $discountCode->type = $request->type;
           $discountCode->discount_amount = $request->discount_amount;
           $discountCode->min_amount = $request->min_amount;
           $discountCode->status = $request->status;
           $discountCode->starts_at = $request->starts_at;
           $discountCode->expires_at = $request->expires_at;
           $discountCode->save();

           session()->flash('success','Discount Coupon added Successfully');

           return response()->json([
            'status' => true,
            'message' => 'Discount Coupon added successfully'
        ]);

        } else {
            session()->flash('error','Discount Coupon is not created');

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(Request $request,$id){
        $coupon = DiscountCoupon::find($id);
        if ($coupon == null) {
            session()->flash('error','Record not Found');
            return redirect()->route('coupons.index');
        }

        $data['coupon'] = $coupon;

        return view('admin.coupon.edit',$data);
        
    }
    public function update(Request $request,$id){
        $discountCode = DiscountCoupon::find($id);
        if(empty($discountCode)){
            session()->flash('error','Record not Found');
            return response()->json([
                'status' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            

            // expiry date must be greater than starting date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if ($expireAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry date must be greater than start date/time']
                    ]);
                }
            }

           
           $discountCode->code = $request->code;
           $discountCode->name = $request->name;
           $discountCode->description = $request->description;
           $discountCode->max_uses = $request->max_uses;
           $discountCode->max_uses_user = $request->max_uses_user;
           $discountCode->type = $request->type;
           $discountCode->discount_amount = $request->discount_amount;
           $discountCode->min_amount = $request->min_amount;
           $discountCode->status = $request->status;
           $discountCode->starts_at = $request->starts_at;
           $discountCode->expires_at = $request->expires_at;
           $discountCode->save();
        
           session()->flash('success','Discount Coupon updated Successfully');

           return response()->json([
            'status' => true,
            'message' => 'Discount Coupon updated successfully'
        ]);

        } else {
            session()->flash('error','Discount Coupon not found');

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request,$id){
        $discountCode = DiscountCoupon::find($id);
        if($discountCode == null){
            session()->flash('error','Record not Found');
            return response()->json([
                'status' => true
            ]);
        }
        $discountCode->delete();
        session()->flash('success','Discount Coupon deleted Successfully');

           return response()->json([
            'status' => true,
            'message' => 'Discount Coupon deleted successfully'
        ]);
    }
}
