@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index')}}" class="btn btn-outline-dark">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="productForm" name="productForm">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $products->title }}">	
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $products->slug }}">	
                                    <p class="error"></p>                                 
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="short_description">Short Description</label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">{{ $products->short_description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{ $products->description }}</textarea>
                                </div>
                            </div>  
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="shipping_returns">Shipping and Returns</label>
                                    <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Description">{{ $products->shipping_returns }}</textarea>
                                </div>
                            </div>  
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="direction">Direction</label>
                                    <textarea name="direction" id="direction" cols="30" rows="10" class="summernote" placeholder="Direction">{{ $products->direction }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="benefits">Benefits</label>
                                    <textarea name="benefits" id="benefits" cols="30" rows="10" class="summernote" placeholder="Benefits">{{ $products->benefits }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="ingredients">Ingredients</label>
                                    <textarea name="ingredients" id="ingredients" cols="30" rows="10" class="summernote" placeholder="Ingredients">{{ $products->ingredients }}</textarea>
                                </div>
                            </div>   
                        </div>
                    </div>	                                                                      
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Media</h2>								
                        <div id="image" class="dropzone dz-clickable">
                            <div class="dz-message needsclick">    
                                <br>Drop files here or click to upload.<br><br>                                            
                            </div>
                        </div>
                    </div>	                                                                      
                </div>
            <div class="row" id="product-gallery">
                @if($productImages->isNotempty())
                    @foreach ($productImages as $productImage)
                    <div class="col-md-3" id="image-row-{{$productImage->id}}">
                        <div class="card">
                            <input type="hidden" name="image_array[]" value="{{$productImage->id}}">
                            <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <a href="javascript:void(0)" onclick="deleteImage({{$productImage->id}})" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                        </div>
                    @endforeach
                @endif
            </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Pricing</h2>								
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $products->price }}">	
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="compare_price">Compare at Price</label>
                                    <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $products->compare_price }}">
                                    <p class="text-muted mt-3">
                                        To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                    </p>	
                                </div>
                            </div>                                            
                        </div>
                    </div>	                                                                      
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Inventory</h2>								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku">SKU (Stock Keeping Unit)</label>
                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{ $products->sku }}">	
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barcode">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $products->barcode }}">	
                                </div>
                            </div>
                             
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="hidden" name="track_qty" value="No" {{ ($products->track_qty == "No")? 'checked' : '' }}>
                                        <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{ ($products->track_qty == "Yes")? 'checked' : '' }}>
                                        <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $products->qty }}">	
                                    <p class="error"></p>
                                </div>
                            </div>                                         
                        </div>
                    </div>	                                                                      
                </div>
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Related Products</h2>
                        <div class="mb-3">
                            <select multiple name="related_products[]" id="related_products" class="related-product w-100">
                               @if (!empty($relatedProducts))
                                    @foreach ($relatedProducts as $relatedProduct)
                                        <option selected value="{{ $relatedProduct->id }}">{{ $relatedProduct->title }}</option>
                                    @endforeach
                                   
                               @endif
                            </select>
                            <p class="error"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Product status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                <option {{ ($products->status == 1)? 'selected' : '' }} value="1">Active</option>
                                <option {{ ($products->status == 0)? 'selected' : '' }} value="0">Block</option>
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="card">
                    <div class="card-body">	
                        <h2 class="h4  mb-3">Product category</h2>
                        <div class="mb-3">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if($categories->isNotEmpty())
                                    @foreach($categories aS $category)
                                    <option {{ ($products->category_id == $category->id)? 'selected' : '' }} value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="error"></p>
                        </div>
                        <!--<div class="mb-3">-->
                        <!--    <label for="sub_category">Sub category</label>-->
                        <!--    <select name="sub_category" id="sub_category" class="form-control">-->
                        <!--        <option value="">Select a Sub-Category</option>-->
                        <!--        @if($subCategories->isNotEmpty())-->
                        <!--        @foreach($subCategories aS $subCategory)-->
                        <!--        <option {{ ($products->sub_categories_id == $subCategory->id)? 'selected' : '' }} value="{{$subCategory->id}}">{{$subCategory->name}}</option>-->
                        <!--        @endforeach-->
                        <!--    @endif-->
                        <!--    </select>-->
                        <!--</div>-->
                    </div>
                </div> 
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Product brand</h2>
                        <div class="mb-3">
                            <select name="brand" id="brand" class="form-control">
                                <option value="">Select a Product Brand</option>
                                @if($brands->isNotEmpty())
                                    @foreach($brands aS $brand)
                                    <option {{ ($products->brand_id == $brand->id)? 'selected' : '' }} value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div> 
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Featured Product</h2>
                        <div class="mb-3">
                            <select name="is_featured" id="is_featured" class="form-control">
                                <option {{ ($products->is_featured == "No")? 'selected' : '' }} value="No">No</option>
                                <option {{ ($products->is_featured == "Yes")? 'selected' : '' }} value="Yes">Yes</option>                                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Product Types</h2>
                        <div class="mb-3">
                            <select name="productType" id="productType" class="form-control">
                                <option value="">Select a Product Type</option>
                                @if($productTypes->isNotEmpty())
                                    @foreach($productTypes aS $productType)
                                    <option {{ ($products->product_type_id == $productType->id)? 'selected' : '' }} value="{{$productType->id}}">{{$productType->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div> 
                <!--<div class="card mb-3">-->
                <!--    <div class="card-body">	-->
                <!--        <h2 class="h4 mb-3">Packagings</h2>-->
                <!--        <div class="mb-3">-->
                <!--            <select multiple name="packagings[]" id="packagings" class="packagings w-100">-->
                <!--                @if (!empty($packagings))-->
                <!--                @foreach ($packagings as $packaging)-->
                <!--                    <option selected value="{{ $packaging->id }}">{{ $packaging->name }}</option>-->
                <!--                @endforeach-->
                               
                <!--           @endif-->
                <!--            </select>-->
                <!--            <p class="error"></p>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div> -->
                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Sizes</h2>
                        <div class="mb-3">
                            <select multiple name="sizes[]" id="sizes" class="sizes w-100">
                                @if (!empty($sizes))
                                @foreach ($sizes as $size)
                                    <option selected value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                               
                           @endif
                            </select>
                            <p class="error"></p>
                        </div>
                    </div>
                </div>  
                                                 
            </div>
        </div>
        
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-outline-dark">Update</button>
            <a href="{{ route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
    <script>
        $('.related-product').select2({
            ajax: {
                url: '{{ route("products.getProducts") }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                    results: data.tags
                    };
                 }
            }
        }); 
        
        // $('.product-types').select2({
        //     ajax: {
        //         url: '{{ route("products.getProductTypes") }}',
        //         dataType: 'json',
        //         tags: true,
        //         multiple: true,
        //         minimumInputLength: 3,
        //         processResults: function (data) {
        //             return {
        //             results: data.tags
        //             };
        //          }
        //     }
        // }); 
        // $('.packagings').select2({
        //     ajax: {
        //         url: '{{ route("products.getProductPackagings") }}',
        //         dataType: 'json',
        //         tags: true,
        //         multiple: true,
        //         minimumInputLength: 3,
        //         processResults: function (data) {
        //             return {
        //             results: data.tags
        //             };
        //          }
        //     }
        // }); 
        $('.sizes').select2({
            ajax: {
                url: '{{ route("products.getProductSizes") }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                    results: data.tags
                    };
                 }
            }
        }); 

        $("#title").change(function(){
			element = $(this);
			$("button[type=submit]").prop('disabled',true);
			$.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response){
					$("button[type=submit]").prop('disabled',false);
					if(response["status"] == true){
						$('#slug').val(response['slug']);
					}
				}
			});
		});

        $('#productForm').submit(function(event){
            event.preventDefault();
           var formArray = $(this).serializeArray();
           $("button[type='submit']").prop('disabled',false);
            $.ajax({
                url: '{{route("products.update",$products->id)}}',
                type: 'put',
                data: formArray,
                datatype: 'json',
                success:function(response){
                    if(response['status']==true){
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'],input[type='number'],select").removeClass('is-invalid');
                        window.location.href="{{ route('products.index') }}";
                    }else{
                        var errors = response['errors'];
                         $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'],input[type='number'],select").removeClass('is-invalid');
                        $.each(errors,function(key,value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        });
                    }
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
        });

        // $('#category').change(function(){
        //     var category_id = $(this).val();
        //     $.ajax({
        //         url: '{{ route("product-subcategory.index") }}',
        //         type: 'get',
        //         data: {category_id:category_id},
        //         datatype: 'json',
        //         success:function(response){
        //             $('#sub_category').find("option").not(":first").remove();
        //             $.each(response["subCategories"],function(key,item){
        //                 $("#sub_category").append(`<option value="${item.id}">${item.name}</option>`);
        //             });
        //         },
        //         error:function(){
        //             console.log('something went wrong');
        //         }
        //     });
        // });

        
        Dropzone.autoDiscover = false;    
		const dropzone = $("#image").dropzone({ 
    	url:  "{{ route('product-images.update') }}",
    	maxFiles: 10,
    	paramName: 'image',
        params : {'id' : '{{ $products->id }}'},
    	addRemoveLinks: true,
    	acceptedFiles: "image/jpeg,image/png,image/gif",
    	headers: {
        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			}, success: function(file, response){
        		//$("#image_id").val(response.image_id);
        		//console.log(response)

                var html =`<div class="col-md-3" id="image-row-${response.image_id}">
                <div class="card">
                    <input type="hidden" name="image_array[]" value="${response.image_id}">
                    <img src="${response.ImagePath}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                    </div>
                </div>
                </div>`;
                $("#product-gallery").append(html);
    		},
            complete: function(file){
                this.removeFile(file);
            }
		});

        function deleteImage(id){
            
            var url = '{{ route("product-images.delete","ID") }}';
        var newUrl = url.replace("ID",id);
        
        if(confirm("Are you really want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                success: function(response){
					if(response["status"]==true){
					    $('input[name="image_array[]"][value="' + id + '"]').remove();
                        $("#image-row-"+id).remove();
                    }
                }
            });
        }
        }
    </script>
@endsection

