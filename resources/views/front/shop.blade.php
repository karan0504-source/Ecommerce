@extends('front.layouts.app')
@section('content')
@php
    $categoryslug = ''; // Initialize $categoryslug variable
@endphp

<div class="bg-white">
<section class="section-5 pt-2 pb-1 mb-1 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </div>
        @if (!empty($categorySelected))
            @foreach ($categories as $category)
                @if ($categorySelected == $category->id)
                    <div class="light-font mt-2">
                        <h2 class="text-dark">{{$category->name}}</h2>
                        <?php 
                            $categoryslug = $category->slug;
                        ?>
                    </div>                
                @endif
            @endforeach        
        @endif
        @if ($categoryslug == '')
        @foreach ($brands as $brand)
        @if ($brandSelected == $brand->id)
            <div class="light-font mt-2">
                <h2>{{$brand->name}}</h2>
                <?php 
                    $categoryslug = $brand->slug;
                ?>
            </div>                
        @endif
    @endforeach
        @endif
    </div>
</section>
<section class="section-6 pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3 sidebar">
                {{-- <div class="sub-title">
                    <h2>Categories</h3>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @if ($categories->isNotEmpty())
                            @foreach ($categories as $key => $category)
                            <div class="accordion-item">
                                @if ($category->sub_category->isNotEmpty())
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false"
                                        aria-controls="collapseOne-{{ $key }}">
                                        {{ $category->name }}
                                    </button>
                                </h2>
                                @else
                                <a href="{{ route('front.shop',$category->slug) }}"
                                    class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : ''}}">{{ $category->name }}</a>
                                @endif
                                @if ($category->sub_category->isNotEmpty())
                                <div id="collapseOne-{{ $key }}"
                                    class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">

                                            @foreach ($category->sub_category as $subCategory)
                                            <a href="{{ route('front.shop',[$category->slug,$subCategory->slug]) }}"
                                                class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : ''}}">{{ $subCategory->name }}</a>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach

                            @endif


                        </div>
                    </div>
                </div> --}}

                {{-- <div class="sub-title mt-5">
                    <h2>Brand</h3>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($brands->isNotEmpty())
                        @foreach ($brands as $brand)
                        <div class="form-check mb-2" id="brand-contents">
                            <input {{ in_array($brand->id,$brandsArray) ? 'checked' : '' }}
                                class="form-check-input brand-label" type="checkbox" name="brand[]"
                                value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>
                        @endforeach
                        @endif


                    </div>
                </div> --}}

                <div class="sub-title mt-3">
                    <h2 class="bg-white">Product Type</h3>
                </div>

               <div class="card bg-light">
                    <div class="card-body">
                        @if ($productTypes->isNotEmpty())
                            @php $count = 0; @endphp
                            @foreach ($productTypes as $productType)
                                <div class="form-check mb-2 product-type-contents">
                                    <input {{ in_array($productType->id, $productTypesArray) ? 'checked' : '' }}
                                        class="form-check-input productType-label" type="checkbox" name="productType[]"
                                        value="{{ $productType->id }}" id="productType-{{ $productType->id }}">
                                    <label class="form-check-label" for="productType-{{ $productType->id }}">
                                        {{ $productType->name }}
                                    </label>
                                </div>
                                 @php $count++; @endphp
                            @endforeach

                            @if ($count > 5)
                                <button class="show-more btn btn-outline-dark">Show More</button>
                                <button class="show-less btn btn-outline-dark" style="display: none;">Show Less</button>
                            @endif
                        @endif
                    </div>
                </div>

                 <div class="sub-title mt-4">
                    <h2 class="bg-white">Size</h2>
                </div>

                <div class="card bg-light">
                    <div class="card-body">
                        @if ($sizes->isNotEmpty())
                            @php $countSize = 0; @endphp
                            @foreach ($sizes as $size)
                            <div class="form-check mb-2 size">
                                <input {{ in_array($size->id,$sizesArray) ? 'checked' : '' }} class="form-check-input size-label" type="checkbox" name="size[]" value="{{ $size->id }}" id="size-{{ $size->id }}">
                <label class="form-check-label" for="size-{{ $size->id }}">
                    {{ $size->name }}
                </label>
            </div>
            @php $countSize++; @endphp
            @endforeach
            @if ($countSize > 5)
                                <button class="show-more-size btn btn-outline-dark">Show More</button>
                                <button class="show-less-size btn btn-outline-dark" style="display: none;">Show Less</button>
                            @endif
            @endif


        </div>
    </div>
{{--
    <div class="sub-title mt-5">
        <h2>Packaging</h3>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($brands->isNotEmpty())
            @foreach ($brands as $brand)
            <div class="form-check mb-2">
                <input {{ in_array($brand->id,$brandsArray) ? 'checked' : '' }} class="form-check-input brand-label"
                    type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                <label class="form-check-label" for="brand-{{ $brand->id }}">
                    {{ $brand->name }}
                </label>
            </div>
            @endforeach
            @endif


        </div>
    </div> --}}

    <div class="sub-title mt-4">
        <h2 class="bg-white">Price</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <input type="text" class="js-range-slider" name="my_range" value="" />
        </div>
    </div>
    </div>
    <div class="col-md-9">
        <div class="row pb-3">
            <div class="col-12 pt-3">
                <div class="d-flex align-items-center justify-content-end mb-4">
                    <div class="ml-2">
                        <select name="sort" id="sort" class="form-control">
                            <option value="latest" {{ ($sort == 'latest') ? 'selected' : ''}}>Latest </option>
                            <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : ''}}>Price High
                            </option>
                            <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : ''}}>Price Low </option>
                        </select>
                    </div>
                </div>
            </div>
            @if(!empty($products->isNotEmpty()))
            @foreach ($products as $product)
            @php
            $productImage = $product->product_images->first();
            @endphp
            @if (empty($categoryslug))
                @if (!empty($categories))
                    @foreach ($categories as $category)
                        @if ($product->category_id == $category->id)
                            @php
                                $categoryslug = $category->slug;
                            @endphp
                        @endif
                    @endforeach
                @endif
            @endif
            
            <div class="col-md-4">
                <div class="card product-card" style="width: 300; height: 415;">
                    <div class="product-image position-relative">
                        <a href="{{ url('/Categories/'.$categoryslug.'/product/'.$product->slug) }}" class="product-img">
                            @if(!empty($productImage->image))
                            <img src="{{asset('uploads/product/small/'.$productImage->image)}}" class="card-img-top">
                            @else
                            <img src="{{asset('admin-assets/img/avatar5.png')}}" class="card-img-top">
                            @endif
                        </a>
                        <a onclick="addToWishlist({{$product->id}})" class="whishlist" href="javascript:void(0);"><i
                                class="far fa-heart"></i></a>
                        <div class="product-action">
                            <div class="product-info text-center mt-3">
                                @if ($product->track_qty == 'Yes')
                                @if ($product->qty > 0)
                                <a class="btn btn-dark" href="javascript:void(0);"
                                    onclick="addToCart({{ $product->id }})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Out Of Stock
                                </a>
                                @endif

                                @else
                                <a class="btn btn-dark" href="javascript:void(0);"
                                    onclick="addToCart({{ $product->id }})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="{{ url('/Categories/'.$categoryslug.'/product/'.$product->slug) }}">{{ Str::limit($product->title, 22, '...') }}</a>
                        <div class="price mt-2">
                            <span class="h6"><strong>&#x20B9;&nbsp;{{ number_format($product->price,2) }}</strong></span>
                            @if ($product->compare_price > 0)
                            <span
                                class="h6 text-underline"><del>&#x20B9;&nbsp;{{ number_format($product->compare_price,2) }}</del></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            @endif



            <div class="col-md-12 pt-5">
                {{ $products->withQueryString()->links() }}

            </div>
        </div>
    </div>
    </div>
    </div>
</section>
</div>
@endsection

@section('customJs')
<script>
    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 5,
        max: 4500,
        from: {{ ($priceMin) }},
        step: 12,
        to: {{ ($priceMax) }},
        skin: "round",
        max_postfix: "+",
        prefix: '\u20B9',
        onFinish: function (){
            apply_filters()
        }
    });

    // Saving it's instance of var
    var slider = $(".js-range-slider").data("ionRangeSlider");

    $(".brand-label").change(function(){
        apply_filters();
    });

    $(".productType-label").change(function(){
        apply_filters();
    });

    $(".size-label").change(function(){
        apply_filters();
    });

    $("#sort").change(function () {
        apply_filters();
    });



    function apply_filters(){
        var brands = [];
        var productTypes = [];
        var sizes = [];

        $(".brand-label:checked").each(function (){
            
                brands.push($(this).val());
            
        });

        $(".productType-label:checked").each(function (){
            
            productTypes.push($(this).val());
        
    });

    $(".size-label:checked").each(function (){
            
            sizes.push($(this).val());
        
    });

        var url = '{{ url()->current() }}';
       
        url += (url.indexOf('?') === -1) ? '?' : '&';
        
        //Brands Filter

        if (brands.length > 0) {
            url+='&brands='+brands.toString();
        }

        //Product Types Filter

        if (productTypes.length > 0) {
            url+='&productTypes='+productTypes.toString();
        }

        //Sizes Filter

        if (sizes.length > 0) {
            url+='&sizes='+sizes.toString();
        }
        
        //Price Range
        url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;
       

        var keyword = $("#search").val();
        if(keyword.length > 0) {
            url +='&search='+keyword;
        }
        //Sorting Filter

        url +='&sort='+$('#sort').val();



        window.location.href = url;
    }


    $(document).ready(function(){
        var productTypes = $(".product-type-contents");
        var sizes = $(".size");

        // Hide product types beyond the first 10
        productTypes.slice(5).hide();
        sizes.slice(5).hide();

        $(".show-more").on('click', function(){
            productTypes.show(); // Show all product types
            $(".show-more").hide(); // Hide the "Show More" button
            $(".show-less").show(); // Show the "Show Less" button
        });
        
        $(".show-more-size").on('click', function(){
            sizes.show(); // Show all product types
            $(".show-more-size").hide(); // Hide the "Show More" button
            $(".show-less-size").show(); // Show the "Show Less" button
        });

        $(".show-less").on('click', function(){
            productTypes.slice(5).hide(); // Hide product types beyond the first 10
            $(".show-more").show(); // Show the "Show More" button
            $(".show-less").hide(); // Hide the "Show Less" button
        });
        
        $(".show-less-size").on('click', function(){
            sizes.slice(5).hide(); // Hide product types beyond the first 10
            $(".show-more-size").show(); // Show the "Show More" button
            $(".show-less-size").hide(); // Hide the "Show Less" button
        });
    });
    
</script>
@endsection