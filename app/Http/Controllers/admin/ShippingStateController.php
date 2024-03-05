<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use App\Models\State;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ShippingStateController extends Controller
{
    public function index(Request $request){
       
        if(!empty($request->country_id)){
 
        
         $states=State::where('country_id',$request->country_id)
         ->orderBy('name','ASC')
         ->get();
         return response()->json([
             'status' =>true,
             'states' => $states,
         ]);
     } else{
         return response()->json([
             'status' => true,
             'states' => [],
         ]);
     }
 
    }

    public function charge(Request $request){
        if(!empty($request->state_id)){
            // $st = (int)$request->state_id;
            $totalCharge = 0.00;
            $states=ShippingCharge::where('state_id',$request->state_id)
            ->get();
            foreach ($states as $state) {
               
                    foreach (Cart::content() as $item) {
                        $itemCharge = $item->qty * $state->amount;
                        $totalCharge += $itemCharge;
                    }
            }
            return response()->json([
                'status' =>true,
                'totalCharge' => $totalCharge,
            ]);
        } else{
            return response()->json([
                'status' => true,
                'totalCharge' => 0.00,
            ]);
        }
    
    }
}
