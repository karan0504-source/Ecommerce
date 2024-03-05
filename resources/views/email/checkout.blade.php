<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px">
    
    {{-- @if ($mailData['Type'] == 'customer')
    <h1>Thanks for Order!!</h1>
    <h2>Your Order Id is : {{$mailData['order']->id}}</h2>
    @else
    <h1>You have received an order:</h1>
    <h2>Order Id is : {{$mailData['order']->id}}</h2>
    @endif
    
    
    <h2>Shipping Address</h2>
    <address>
        <strong>{{$mailData['order']->first_name}} {{$mailData['order']->last_name}}</strong><br>
        {{$mailData['order']->address}}<br>
        {{$mailData['order']->city}}, {{ $mailData['state']->name }} , {{$mailData['country']->name}} {{$mailData['order']->zip}}<br>
        Phone: {{$mailData['order']->mobile}}<br>
        Email: {{$mailData['order']->email}}
    </address> --}}

    <h1>Products</h1>
    <table class="table table-striped" cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #CCC;">
                <th>Product</th>
                <th >Price</th>
                <th >Qty</th>                                        
                <th >Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['content'] as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{number_format($item->price,2)}}</td>                                        
                <td>{{$item->qty}}</td>
                <td>₹{{number_format($item->total,2)}}</td>
            </tr>
            @endforeach
           
            
            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>₹{{number_format($mailData['subtotal'],2)}}</td>
            </tr>
            
            {{-- <tr>
                <th colspan="3" align="right">Discount{{!empty($mailData['order']->coupon_code)?' ( '.$mailData['order']->coupon_code.' ) ' :''}}:</th>
                <td>₹{{number_format($mailData['order']->discount,2)}}</td>
            </tr> --}}

            {{-- <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>₹{{number_format($mailData['order']->shipping,2)}}</td>
            </tr> --}}
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>₹{{number_format($mailData['subtotal'],2)}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>