@extends('front.layouts.app')
@section('meta_description', $meta_description)
@section('content')

{{-- <div class="col-md-9 pt-5">
    <div class="row pb-3">
        @if(!empty($categories->isNotEmpty()))
            @foreach ($categories as $category)

                <div class="col-md-4">
                    <div class="card"  style="border-radius: 50%;width:100;height:100;" >
                        <div class="product-image position-relative">
                            <a href="{{ route('front.category',$category->slug) }}" class="product-img">
                                @if(!empty($category->image))
                                <img src="{{asset('uploads/category/thumbs/'.$category->image)}}" class="card-img-top">
                                @else
                                <img src="{{asset('admin-assets/img/avatar5.png')}}" class="card-img-top">
                                @endif
                            </a>
                            
                        </div>
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{ route('front.category',$category->slug) }}">{{ $category->title }}</a>
                            
                        </div>
                    </div>
                </div>
            @endforeach

        @endif
    </div>
</div> --}}
<div class="bg-white">
<div class="row pb-3">
    @if(!empty($categories->isNotEmpty()))
                @foreach ($categories as $category)
    <div class="col-md-3" style="padding: 3%;">
        
            <div class="card text-center bg-white" style="width: 18rem;
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
                   
                    @if(!empty($category->image))
                        <img src="{{asset('uploads/category/thumbs/'.$category->image)}}" class="mx-auto d-block" style="width: 225px;
                        height: 225px;
                        border-radius:50%;
                        object-fit: cover;
                        max-width: 100%;">
                        {{-- style="width: 300px;
                        height: 300px;
                        border-radius:50%;
                        object-fit: cover;
                        align: justify-center;" 
                        data-widths="[300, 420, 640]" data-sizes="auto"
                        class="responsive"--}}
                        @else
                        <img src="{{asset('admin-assets/img/avatar5.png')}}" class="mx-auto d-block" style="width: 225px;
                        height: 225px;
                        border-radius:50%;
                        object-fit: cover;">
                    @endif
                    
                        <h2 style=" padding: 10px;
                        font-size: 24px;
                        text-align: center;
                        /* background-color: #008CBA;
                        color: #fff; */
                        margin: 0;" class="card-title"><a class="link" href="{{ url('/Categories/'.$category->slug) }}">{{ $category->name }}</h2>
                    </a>
                    
            </div>
            
            
    </div>
    @endforeach
        @endif
</div>

</div>
@endsection