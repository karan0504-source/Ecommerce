@extends('front.layouts.app')
@section('meta_description', $meta_description)
@section('content')
<div class="bg-white">
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">{{ $product->title }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-7 pt-3 mb-3">
    <div class="container">
        <div class="row ">
            <div class="col-md-5">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner bg-white">
                        @if ($product->product_images )
                            @foreach ($product->product_images as $key=>$productImage)
                            <div class="carousel-item {{ ($key == 0)? 'active' : '' }}">
                                <img class="w-100 h-100" src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="Image">
                            </div>
                            @endforeach
                        @endif
                        
                        
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="bg-white right">
                    <h1>{{ $product->title }}</h1>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(99 Reviews)</small>
                    </div>
                    @if ($product->compare_price > 0)
                        <h2 class="price text-secondary"><del>&#x20B9;&nbsp;{{ $product->compare_price }}</del></h2>
                    @endif
                    
                    <h2 class="price ">&#x20B9;&nbsp;{{ $product->price }}</h2>

                    {!! $product->short_description !!}

                    <br>
                    <br>
                    <br>
                    @if ($product->track_qty == 'Yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a> 
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                         Out Of Stock
                                    </a> 
                                    @endif    
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>                                    
                                @endif
                </div>
            </div>
            <?php 
                $count=0;
            ?>

            <div class="col-md-12 mt-5">
                <div class="bg-white">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @if(!empty($product->description))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($flag==1) active @endif" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                            <?php 
                                if($count == 0)
                                $count=1;
                            ?>
                        </li>
                        @endif
                        @if(!empty($product->direction))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($flag==2) active @endif" id="direction-tab" data-bs-toggle="tab" data-bs-target="#directions" type="button" role="tab" aria-controls="directions" aria-selected="false">Direction</button>
                            <?php 
                                if($count == 0)
                                $count=2;
                            ?>
                        </li>
                         @endif
                         @if(!empty($product->benefits))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($flag==3) active @endif" id="benefits-tab" data-bs-toggle="tab" data-bs-target="#benefits" type="button" role="tab" aria-controls="benefits" aria-selected="false">Benefits</button>
                            <?php 
                                if($count == 0)
                                $count=3;
                            ?>
                        </li>
                         @endif
                          @if(!empty($product->ingredients))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($flag==4) active @endif" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">Ingredients</button>
                            <?php 
                                if($count == 0)
                                $count=4;
                            ?>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content bg-light" id="myTabContent">
                        
                        <div class="tab-pane fade @if($count==1) show active @endif" id="description" role="tabpanel" aria-labelledby="description-tab">
                           {!! $product->description !!}
                        </div>
                        
                        <div class="tab-pane fade @if($count==2) show active @endif" id="directions" role="tabpanel" aria-labelledby="directions-tab">
                            {!! $product->direction !!}
                        </div>
                        <div class="tab-pane fade @if($count==3) show active @endif" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
                            {!! $product->benefits !!}
                        </div>
                        <div class="tab-pane fade @if($count==4) show active @endif" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                            {!! $product->ingredients !!}
                        </div>
                    </div>
                </div>
            </div> 
        </div>           
    </div>
</section>

@if (!empty($relatedProducts))
<section class="pt-5 section-8 bg-white">
    <div class="container">
        <div class="section-title">
            <h2>Related Products</h2>
        </div> 
        <div class="col-md-12">
            <div id="related-products" class="carousel">
                
                    @foreach ($relatedProducts as $relatedProduct)
                    @if (!empty($categories))
                        @foreach ($categories as $category)
                            @if ($relatedProduct->category_id == $category->id)
                                @php
                                    $slugCategory = $category->slug;
                                @endphp
                            @endif
                        @endforeach
                    @else
                        @php
                            $slugCategory = 'related-products';
                        @endphp
                    @endif
                    @php
                        $productImage = $relatedProduct->product_images->first();
                    @endphp

                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ url('/Categories/'.$slugCategory.'/product/'.$relatedProduct->slug) }}" class="product-img">
                                @if(!empty($productImage->image))
                                <img src="{{asset('uploads/product/small/'.$productImage->image)}}" class="card-img-top" >
                                @else
                                <img src="{{asset('admin-assets/img/avatar5.png')}}" class="card-img-top" >
                                @endif
                            </a>
                            <a onclick="addToWishlist({{$relatedProduct->id}})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            
                            <div class="product-action">
                                <div class="product-info text-center mt-3">
                                    @if ($relatedProduct->track_qty == 'Yes')
                                            @if ($relatedProduct->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $relatedProduct->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a> 
                                            @else
                                            <a class="btn btn-dark" href="javascript:void(0);">
                                                Out Of Stock
                                            </a> 
                                            @endif    
                                        @else
                                        <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $relatedProduct->id }})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>                                    
                                    @endif
                                </div>
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{ url('/Categories/'.$slugCategory.'/product/'.$relatedProduct->slug) }}">{{ $relatedProduct->title }}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>&#x20B9;&nbsp;{{ $relatedProduct->price }}</strong></span>
                                @if ($relatedProduct->compare_price > 0)
                                <span class="h6 text-underline"><del>&#x20B9;&nbsp;{{ $relatedProduct->compare_price }}</del></span>
                                @endif
                                
                            </div>
                        </div>                        
                    </div>
                    @endforeach
                 
               
            </div>
        </div>
    </div>
</section>
@endif
</div>
@endsection
@section('customJs')
<script type="text/javascript">
    
</script>
@endsection