<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\State;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::latest('orders.created_at')->select('orders.*','front_users.name','front_users.email');
        $orders = $orders->leftJoin('front_users','front_users.id','orders.front_user_id');
        if(!empty($request->get('keyword'))){
            $orders = $orders->where('front_users.name','like','%'.$request->get('keyword').'%');
            $orders = $orders->orWhere('front_users.email','like','%'.$request->get('keyword').'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->get('keyword').'%');

        }
        $orders = $orders->paginate(10);

        return view('admin.orders.list',['orders'=>$orders]);
    }

    public function detail($id){
        $orders = Order::where('id',$id)->first();
        $state = State::where('id',$orders->state_id)->first();
        $country = Country::where('id',$orders->country_id)->first();
        $orderItems = OrderItems::where('order_id',$id)->get();
        $data['orders'] = $orders;
        $data['state'] = $state;
        $data['country'] = $country;
        $data['orderItems'] = $orderItems;
        //dd($data);
        return view('admin.orders.detail',$data);

    }

    public function changeOrderStatus(Request $request,$id) {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();
        session()->flash('success','Status updated Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Status updated Successfully'
        ]);
    }

    public function sendInvoiceEmail(Request $request,$orderId) {
        orderEmail($orderId,$request->type);
        session()->flash('success','Order Email Send Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Order Email Send Successfully'
        ]);
    }
}
