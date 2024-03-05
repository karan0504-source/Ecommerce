<?php

namespace App\Http\Controllers\admin;
use App\Models\Country;
use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        $countries = Country::get();
        $states = State::get();
        $data = [];
        $data['countries'] = $countries;
        $data['states'] = $states;
        $shippings = ShippingCharge::select('shipping_charges.*','countries.name As country','states.name As state')
        ->leftJoin('countries','countries.id','shipping_charges.country_id')
        ->leftJoin('states','states.id','shipping_charges.state_id')
        ->get();
        $data['shippings'] = $shippings;
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request){

        $count = ShippingCharge::where('state_id',$request->states)->count();

       
        
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'states' => 'required',
            'amount' => 'required|numeric'
        ]);
        if ($validator->passes()) {

            if ($count > 0) {
                session()->flash('error','Shipping already exists');
                return response()->json([
                    'status' => true,
                ]);
            } 

            $shipping = new ShippingCharge;
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->states;
            $shipping->amount = $request->amount;
            $shipping->save();


            session()->flash('success','Shipping added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
    }

    public function edit($id) {
        $countries = Country::get();
        $states = State::get();
        $data = [];
        $data['countries'] = $countries;
        $data['states'] = $states;
        $shipping = ShippingCharge::find($id);
        $data['shipping'] = $shipping;
        return view('admin.shipping.edit',$data);
    }

    public function update(Request $request,$id){
        
        $shipping = ShippingCharge::find($id);
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'states' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->passes()) {
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->states;
            $shipping->amount = $request->amount;
            $shipping->save();


            session()->flash('success','Shipping updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
    }

    public function destroy($id){
       $shipping = ShippingCharge::find($id);

        if($shipping == null) {
            session()->flash('error','Shipping not found');
            return response()->json([
             'status' => true,
        
            ]);
        } else {
            $shipping->delete();
            session()->flash('success','Shipping deleted successfully');
            return response()->json([
             'status' => true,
             'message' => 'Shipping deleted successfully'
         ]);
        }

       
    }
}
