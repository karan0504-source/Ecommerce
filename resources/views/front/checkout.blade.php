@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form method="POST" action="" id="orderForm" name="orderForm">
        <div class="row">
            <div class="col-md-8">
                <div class="sub-title">
                    <h2>Shipping Address</h2>
                </div>
                <div class="card shadow-lg border-0">
                    <div class="card-body checkout-form">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}">
                                    <p></p>
                                </div>            
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                    <p></p>
                                </div>            
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select a Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option {{ (!empty($customerAddress)) ? (($customerAddress->country_id == $country->id) ? 'selected' : '') : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                        
                                        
                                    </select>
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : ''}}</textarea>
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="appartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : ''}}">
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select name="states" id="states" class="form-control">
                                        
                                        <option value="">Select a State</option>
                                        @if (!empty($customerAddress))
                                            @if (!empty($userState))
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" {{ $userState == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                @endforeach
                                            @else
                                            @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                            @endif       
                                        @endif
                                        
                                        
                                    </select>
                                    <p></p>	
                                </div>            
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : ''}}">
                                    <p></p>
                                </div>            
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="tel" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : ''}}">
                                    <p></p>
                                </div>            
                            </div>
                            

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->notes : ''}}</textarea>
                                    <p></p>
                                </div>            
                            </div>

                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-md-4">
                <div class="sub-title">
                    <h2>Order Summery</h3>
                </div>                    
                <div class="card cart-summery">
                    <div class="card-body">
                        @foreach (Cart::content() as $item)
                        <div class="d-flex justify-content-between pb-2">
                            <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                            <div class="h6">&#x20B9;&nbsp;{{ $item->price*$item->qty }}</div>
                        </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Subtotal</strong></div>
                            <div class="h6"><strong id="subtotal" data-subtotal="{{ Cart::subtotal() }}">&#x20B9;&nbsp;{{ Cart::subtotal() }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Discount</strong></div>
                            <div class="h6"><strong id="discount_value">&#x20B9;&nbsp;{{ $discount }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="h6"><strong>Shipping</strong></div>
                            

                            {{-- <div class="h6" id="set_charge" name="set_charge"><strong></strong></div> --}}
                           
                            <div class="h6"><strong id="set_charge">&#x20B9;&nbsp;{{ number_format($totalShipping,2) }}</strong></div>

                            
                        </div>
                        <div class="d-flex justify-content-between mt-2 summery-end">
                            <div class="h5"><strong>Total</strong></div>
                            {{-- <div class="h5" id="total_charge" name="total_charge"><strong>&#x20B9;&nbsp;{{ (!empty($grandTotal) ? number_format($grandTotal,2) : Cart::subtotal()+$amount )  }}</strong><strong></strong></div> --}}
                            <div class="h5"><strong id="total_charge">&#x20B9;&nbsp;{{ number_format($grandTotal,2) }}</strong><strong></strong></div>
                        </div>                            
                    </div>
                </div>
                
                <div class="input-group apply-coupan mt-4">
                    <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                    <button class="btn btn-dark" type="button" id="apply_discount">Apply Coupon</button>
                </div>

                <div id="discount_response_wrapper">
                    @if (Session::has('code'))
                    <div class="mt-4" id="discount_response">
                        <strong>{{ Session::get('code')['code'] }}</strong>
                        <a class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                    </div>
                @endif
                </div>
                @if (Session::has('code'))
                    <div class="mt-4" id="discount_response">
                        <strong>{{ Session::get('code')['code'] }}</strong>
                        <a class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                    </div>
                @endif
                
                
                <div class="card payment-form ">    
                    <h3 class="card-title h5 mb-3">Payment Method</h3>
                    <div class="">
                        <input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                        <label for="payment_method_one" class="form-check-label">COD</label>
                    </div>

                    <div class="">
                        <input type="radio" name="payment_method" value="cod" id="payment_method_two">
                        <label for="payment_method_two" class="form-check-label">Stripe</label>
                    </div>
                    
                    
                    <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                        <div class="mb-3">
                            <label for="card_number" class="mb-2">Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="expiry_date" class="mb-2">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="expiry_date" class="mb-2">CVV Code</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="pt-4">
                        {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                        <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                    </div>
                    
                    


                </div>

                      
                <!-- CREDIT CARD FORM ENDS HERE -->
                
            </div>
        </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        // $("#states option").each(function() {
        //     var selectedValue = $(this).data("selected");
        //     if (selectedValue === 'selected') {
        //     $(this).attr('selected', true);
        //     }
        // });
        $("#payment_method_one").click(function(){
            if($(this).is(":checked") == true){
                $("#card-payment-form").addClass('d-none');
            }
        });

        $("#payment_method_two").click(function(){
            if($(this).is(":checked") == true){
                $("#card-payment-form").removeClass('d-none');
            }
        });
        var selectval = document.getElementById("states");

        // if (selectval == null) {
        //     $('#states').change(function(){
        //     var state_id = $(this).val();
        //     $.ajax({
        //         url: '{{ route("shipping-charge.charge") }}',
        //         type: 'get',
        //         data: {state_id:state_id},
        //         datatype: 'json',
        //         success:function(response){
        //             var amount = response['totalCharge'];
        //             $('#set_charge').find("strong");
        //             $("#set_charge").append(`<strong value="${amount}">&#x20B9;&nbsp;${amount}</option>`);
        //         },
        //         error:function(){
        //             console.log('something went wrong');
        //         }
        //     });
        // });
        // } else {

        //     var state_id = $(this).val();
        //     $.ajax({
        //         url: '{{ route("shipping-charge.charge") }}',
        //         type: 'get',
        //         data: {state_id:state_id},
        //         datatype: 'json',
        //         success:function(response){
        //             var amount = response['totalCharge'];
        //             $('#set_charge').find("strong");
        //             $("#set_charge").append(`<strong value="${amount}">&#x20B9;&nbsp;${amount}</option>`);
        //         },
        //         error:function(){
        //             console.log('something went wrong');
        //         }
        //     });
        // }


//         $(document).ready(function() {
//     // Function to update shipping charge
//     function updateShippingCharge(state_id) {
//         $.ajax({
//             url: '{{ route("shipping-charge.charge") }}',
//             type: 'get',
//             data: { state_id: state_id },
//             dataType: 'json', // Corrected 'datatype' to 'dataType'
//             success: function(response) {
//                 var amount = response['totalCharge'];
//                 $('#set_charge').html(`<strong value="${amount}">&#x20B9;&nbsp;${amount}</strong>`);
//                 var currentSubtotal = parseInt($('#subtotal').replace(''));
//                 var newSubtotal = currentSubtotal + amount;
//                 $('#total_charge').html(`<strong>&#x20B9;&nbsp;${newSubtotal}</strong>`);
//             },
//             error: function() {
//                 console.log('Something went wrong');
//             }
//         });
//     }

//     // Attach change event handler to the states select
//     $('#states').change(function() {
//         var state_id = $(this).val();
//         updateShippingCharge(state_id);
//     });

//     // Check if a state is already selected on page load
//     var selectedStateId = $('#states').val();
//     if (selectedStateId) {
//         updateShippingCharge(selectedStateId);
//     }
// });

$('#states').change(function() {
    $.ajax({
                url: '{{ route("front.getOrderSummary") }}',
                type: 'post',
                data: {state_id:$(this).val()},
                dataType: 'json',
                success:function(response){
                    if (response.status == true) {
                        $("#set_charge").html('₹'+response.shippingCharge);
                        $("#total_charge").html('₹'+response.grandTotal);
                    }
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
    });
        
        // $('#set_charge').change(function(){
        //     var setCharge = $(this).val();
        //     console.log(setCharge+Cart::subtotal());
        //     $('#total_charge').find("strong").not(":first").remove();
        //     $("#total_charge").html(`<strong value="${(setCharge+Cart::subtotal())}">&#x20B9;&nbsp;${(setCharge+Cart::subtotal())}</option>`);
            
        // });

        $('#country').change(function(){
            var country_id = $(this).val();
            $.ajax({
                url: '{{ route("shipping-states.index") }}',
                type: 'get',
                data: {country_id:country_id},
                dataType: 'json',
                success:function(response){
                    $('#states').find("option").not(":first").remove();
            
            if (response.states) {
                response.states.forEach(item => {
                    $("#states").append(`<option value="${item.id}">${item.name}</option>`);
                });
                
                // Set the first option as selected if userState is null
                if (!"{{ $userState }}") {
                    $("#states option:first").prop('selected', true);
                }
                
                // Trigger the change event for the states dropdown
                $('#states').change();
            }
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
        });

        $('#orderForm').submit(function(event){
            event.preventDefault();
            $('button[type="submit"]').prop('disabled',true);
            $.ajax({
                url: '{{ route("front.processCheckOut") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    var errors = response.errors;
                    $('button[type="submit"]').prop('enabled',true);

                    if (response.status == false) {
                        if(errors.first_name){
                        $("#first_name").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.first_name);
                    } else {
                        $("#first_name").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.last_name){
                        $("#last_name").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.last_name);
                    } else {
                        $("#last_name").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.email){
                        $("#email").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.country){
                        $("#country").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.country);
                    } else {
                        $("#country").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.address){
                        $("#address").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.address);
                    } else {
                        $("#address").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.city){
                        $("#city").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.city);
                    } else {
                        $("#city").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.zip){
                        $("#zip").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.zip);
                    } else {
                        $("#zip").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    if(errors.mobile){
                        $("#mobile").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.mobile);
                    } else {
                        $("#mobile").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    } else {
                        window.location.href = "{{ url('/account/thanks/') }}/"+response.orderId;
                    }

                }
            });
        });

        $("#apply_discount").click(function(){
            $.ajax({
                url: '{{ route("front.applyDiscount") }}',
                type: 'post',
                data: {code:$('#discount_code').val(),state_id: $('#states').val()},
                dataType: 'json',
                success:function(response){
                    if (response.status == true) {
                        $("#set_charge").html('₹'+response.shippingCharge);
                        $("#total_charge").html('₹'+response.grandTotal);
                        $("#discount_value").html('₹'+response.discount);
                        $("#discount_response_wrapper").html(response.discountString);
                    } else {
                        $("#discount_response_wrapper").html("<span class='text-danger'><br>"+response.message+"</span>");

                    }
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
        });

        $('body').on('click','#remove_discount',function(){
            $.ajax({
                url: '{{ route("front.removeDiscount") }}',
                type: 'post',
                data: {state_id: $('#states').val()},
                dataType: 'json',
                success:function(response){
                    if (response.status == true) {
                        $("#set_charge").html('₹'+response.shippingCharge);
                        $("#total_charge").html('₹'+response.grandTotal);
                        $("#discount_value").html('₹'+response.discount);
                        $("#discount_response_wrapper").html('');
                        $("#discount_code").val('');
                        // window.location.href = '{{ route("front.checkOut") }}'; // Change this to your desired route
                    } else {
                // Handle the case where the removal was not successful, if needed
                console.log('Coupon removal failed');
            } 
                   
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
        });

        // $("#remove_discount").click(function(){
            
        // });
    
    </script>
@endsection