<?php

use App\Mail\OrderMail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\Pages;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\State;
use Illuminate\Support\Facades\Mail;

    function getCategories(){
        return Category::orderBy('name','ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status',1)
        ->where('showHome','Yes')
        ->get();
    }

    function getProductImage($productId){
        return ProductImage::where('product_id',$productId)
        ->first();
    }

    function orderEmail($orderid,$type="customer") {
        $order = Order::where('id',$orderid)->with('items')->first();
        $state = State::where('id',$order->state_id)->first();
        $country = Country::where('id',$order->country_id)->first();
        if ($type == 'customer') {
            $subject = 'Thanks for your order';
            $email = $order->email;
        } else {
            $subject = 'You have received an order';
            $email = env('ADMIN_EMAIL');
        }
        $mailData = [
            'subject' => $subject,
            'order' => $order,
            'state' => $state, 
            'country' => $country, 
            'Type' => $type
        ];
        Mail::to($email)->send(new OrderMail($mailData));

    }

    function staticPages(){
        $pages = Pages::orderBy('name','ASC')->get();
        return $pages;
    }

    function sendContactMail(){
        
    }
?>