<?php

namespace App\Http\Controllers;

use App\Mail\CheckoutMail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\DiscountCoupon;
use App\Models\Product;
use App\Models\Country;
use App\Models\FrontUsers;
use App\Models\Order;
use App\Models\State;
use App\Models\ShippingCharge;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function addToCart(Request $request) {
       
       $product = Product::with('product_images')->find($request->id);

       
       if($product == null){
        return response()->json([
            'status' => false,
            'message' => 'Record Not Found'
        ]);
       }
       
       if(Cart::count() > 0 ){
        //Products found in cart
        // Check if this product already in the cart
        // return as message that product already in your cart
        // if product not found in the cart , then add product in cart

        $cartContent = Cart::content();
        $productAlreadyExist = false;

        foreach ($cartContent as $item) {
            if ($item->id == $product->id) {
                $productAlreadyExist = true;
            }
        }

        if($productAlreadyExist == false) {
            Cart::add($product->id,$product->title,1, $product->price,['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in your cart successfully';
            session()->flash('success', $message);

        } else {
            $status = false;
            $message = '<strong>'.$product->title.'</strong> already added in your cart successfully';
        }

       } else {
        // cart is empty
        Cart::add($product->id,$product->title,1, $product->price,['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = $product->title.' added in cart';
            session()->flash('success', $message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
       
        
    }
    public function cart() {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        $data['meta_description']="Your virtual shopping cart awaits! Fill it with joy, checkout with ease. Explore now!";
        return view('front.cart',$data);
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);
        $product= Product::find($itemInfo->id);
        //check qty available in stock
        if($product->track_qty == 'Yes'){
            if ($qty <= $product->qty) {
                Cart::update($rowId,$qty);
                $message = 'Cart Updated Successfully';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Requested Qty('.$qty.') not available in stock ';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
                Cart::update($rowId,$qty);
                $message = 'Cart Updated Successfully';
                $status = true;
                session()->flash('success', $message);
        }
        
        Cart::update($rowId,$qty);
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    
    public function deleteItem(Request $request) {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);
        if($itemInfo == null){
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($rowId);

        $message = 'Item Removed from Cart Successfully';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkOut(Request $request){
        //dd(session()->get('code'));
        $discount = 0;
        // if cart is empty rediect to cart page
        if(Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        // if user is not logged in then redirect to login page 
        if(!Auth::guard('account')->check()){
            if(session()->has('url.intended') == null){
                Redirect::setIntendedUrl(url()->previous());
            }
            
            return redirect()->route('account.login');
        }
        $data=[];
        $customerAddress = CustomerAddress::where('front_user_id',Auth::guard('account')->user()->id)->first();
        $subTotal = Cart::subtotal(2,'.','');

        
        
        if($customerAddress != null) {
            $totalQty = 0;
            $totalShipping = 0;
            $grandTotal = 0;
            $amount = 0;
            $userCountry = $customerAddress->country_id;
            $userState = $customerAddress->state_id;
            $shippingInfo = ShippingCharge::where('country_id',$userCountry)->where('state_id',$userState)->first();
            
            if ($shippingInfo != null) {
                // Check if 'amount' property is set before accessing it
                $amount = isset($shippingInfo->amount) ? $shippingInfo->amount : 0;
            }
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            $totalShipping = $totalQty*$amount;
            $grandTotal = ($subTotal-$discount)+$totalShipping;
            
            $data['userState'] = $userState;
        } else {
            $totalShipping = 0;
            $grandTotal = ($subTotal-$discount);
            $data['userState'] = '';
        }

            
           
            
            $countries = Country::orderBy('name','ASC')->get();
            $states = State::orderBy('name','ASC')->get();
            $shippingCharge = ShippingCharge::orderBy('id','ASC')->get();
            $data['countries'] = $countries;
            $data['states'] = $states;
            $data['customerAddress'] = $customerAddress;
            $data['totalShipping'] = $totalShipping;
            $data['shippingCharge'] = $shippingCharge;
            $data['grandTotal'] = $grandTotal;
            $data['discount'] = $discount;
            session()->remove('code');
        return view('front.checkout',$data);
    }

    public function processCheckOut(Request $request){

        // step-1 apply validation
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

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' =>  $validator->errors()
            ]);
        }


        //step-2 save user address
        $user = Auth::guard('account')->user();
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

        //step-3 store data in orders table
        if($request->payment_method == 'cod') {


            // calculate shipping
            $shippingInfo =   ShippingCharge::where('state_id',$request->states)->first();
            $totalQty = 0;
            $shipping = 0;
            $discount = 0;
            $discountCodeId = NULL;
            $promoCode = NULL;
            $subTotal =Cart::subtotal(2,'.','');
            // apply discount here
            if(session()->has('code')){
                $code = session()->get('code');
                if($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;        
            }
            
            foreach(Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            if($shippingInfo !=null) {
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $shipping;
                
              } else {
                $shippingInfo = 150;
                $shipping = $totalQty*$shippingInfo;
                $grandTotal = ($subTotal-$discount) + $shipping;
                
              }

        

           
            $order = new Order;
            $order->front_user_id =$user->id;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'Unpaid';
            $order->status = 'Pending';
            $order->discount_coupon_id = $discountCodeId;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->appartment;
            $order->city = $request->city;
            $order->state_id = $request->states;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->save();

            //step-4 store order items in order items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItems;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                // Update Product Stock 
               $productData =  Product::find($item->id);
               if ($productData->track_qty == 'Yes') {
                $currentQty = $productData->qty;
               $updatedQty = $currentQty - $item->qty;
               $productData->qty = $updatedQty;
               $productData->save();
               }
               
            }
            //send Order Email
            //orderEmail($order->id);
           

            session()->flash('success','You have successfully placed your order');
            Cart::destroy();
            session()->remove('code');
            return response()->json([
                'status' => true,
                'message' => 'Order Saved Successfully',
                'orderId' => $order->id
            ]);

        } else {

        }
    }

    public function thankyou(){
        return view('front.thanks');
    }

    public function getOrderSummary(Request $request){
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0 ;
        $discountString = '';
        // apply discount here
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountString = '<div class="mt-4" id="discount_response">
                                    <strong>'.session()->get('code')->code.'</strong>
                                    <a class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                                </div>';

        }

        
        if($request->state_id > 0) {
          $shippingInfo =   ShippingCharge::where('state_id',$request->state_id)->first();
          $totalQty = 0;
          
          foreach(Cart::content() as $item) {
            $totalQty += $item->qty;
          }
          if($shippingInfo !=null) {
            $shippingCharge = $totalQty*$shippingInfo->amount;
            $grandTotal = ($subTotal-$discount) + $shippingCharge;
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal,2),
                'discount' =>number_format($discount,2),
                'discountString'=>$discountString,
                'shippingCharge' => number_format($shippingCharge,2),
            ]);
          } else {
            $shippingInfo = 150;
            $shippingCharge = $totalQty*$shippingInfo;
            $grandTotal = ($subTotal-$discount) + $shippingCharge;
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal,2),
                'discount' => number_format($discount,2),
                'discountString'=>$discountString,
                'shippingCharge' => number_format($shippingCharge,2),
            ]);
          }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal-$discount),2),
                'discount' => number_format($discount,2),
                'discountString'=>$discountString,
                'shippingCharge' => number_format(0,2),
            ]);
        }
    }

    public function applyDiscount(Request $request){
        if (session()->has('code')) {
            return $this->getOrderSummary($request);
        }
        $code = DiscountCoupon::where('code',$request->code)->first();

        //dd($code->starts_at, $code->expires_at);
        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Coupon Code'
            ]);
        }
        date_default_timezone_set('Asia/Kolkata');

        // check if coupon start date is valid or not
        $now = Carbon::now();

        //echo $now->format('Y-m-d H:i:s');
        if($code->starts_at != '') {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);
            if($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon code1'
                ]);
            }
        }

        if($code->expires_at != '') {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);
            if($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon code2'
                ]);
            }
        }
        // Max Uses Check
        if($code->max_uses > 0){
            $couponUsed = Order::where('discount_coupon_id',$code->id)->count();
        if($couponUsed >= $code->max_uses){
            return response()->json([
                'status' => false,
                'message' => 'Invalid discount coupon'
            ]);
        }
        }

        

        // Max Uses User Check
        if($code->max_uses_user > 0){
        $couponUsedByUser = Order::where(['discount_coupon_id'=>$code->id,'front_user_id'=>Auth::guard('account')->user()->id])->count();
        if($couponUsedByUser >= $code->max_uses_user){
            return response()->json([
                'status' => false,
                'message' => 'You already used this coupon code'
            ]);
        }
        }
        // Minimum amount condition check
        $subTotal = Cart::subtotal(2,'.','');
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'You Minimum Amount must be ₹'.$code->min_amount.'.'
                ]);
            }
        }

        session()->put('code',$code);

        return $this->getOrderSummary($request);
    }

    public function removeCoupon(Request $request) {
        session()->forget('code');
        return $this->getOrderSummary($request);
    }

    public function sendMailCheckout(){
        $request = Auth::guard('account')->user();
        $content = Cart::content();
        $subtotal =Cart::subtotal(2,'.','');
        if($content != null) {
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subtotal' => $subtotal,
                'subject' => "Cart Items! You have added.",
                'content' => $content,
                'mail_subject' => "You have received a cart",
            ];
            $admin = User::where('id',1)->first();
            $user = FrontUsers::where('id',$request->id)->first();
            Mail::to([$admin->email,$user->email])->send(new CheckoutMail($mailData));
            session()->flash('success','Thanks! we will get back to you soon.');
            return response()->json([
                'status' => true,
                'message' => 'Thanks! we will get back to you soon.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => "Please add some items first"
            ]);
        }
        
    }
}
