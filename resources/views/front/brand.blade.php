@extends('front.layouts.app')
@section('meta_description', $meta_description)
@section('content')


n
 <div class="row pb-3 bg-white">
    @if(!empty($brands->isNotEmpty()))
                @foreach ($brands as $brand)
    <div class="col-md-3" style="padding: 3%;">
        
                <div class="card bg-white" style="width: 18rem;
                background-color: #f1f1f1;
                border: none;
                /* border-radius: 5px; */
                padding:3%;
                /* box-shadow: 0px 0px 0px rgba(0,0,0,0.1); */
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;">
                
                    @if(!empty($brand->image))
                        <img src="{{asset('uploads/brand/thumbs/'.$brand->image)}}" style="
                        width: 225px;
                        height: 225px;
                        <!--border-radius:50%;-->
                        object-fit: cover;
                        ">
                    @else
                        <img src="{{asset('admin-assets/img/avatar5.png')}}" style="width: 225px;
                        height: 225px;
                        <!--border-radius:50%;-->
                        object-fit: cover;
                        ">
                    @endif
                    
                        <h2 style="padding: 10px;
                        font-size: 24px;
                        text-align: center;
                        /* background-color: #008CBA;
                        color: #fff; */
                        margin: 0;"><a class="link" href="{{ url('/Categories/'.$brand->slug) }}">{{ $brand->name }}</h2>
                    </a>
                    
                </div>
            
    </div>
    @endforeach
        @endif
</div> 

@endsection