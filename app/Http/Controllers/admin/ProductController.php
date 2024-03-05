<?php

namespace App\Http\Controllers\admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use App\Http\Controllers\Controller;
use App\Models\Packaging;
use App\Models\ProductType;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{

    public function index(Request $request){
        $data = [];
        $products = Product::latest('id');

        if($request->get('keyword') != "") {
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }

        $products=$products->with('product_images')->paginate(10);
        
        $data['products'] = $products;
        return view('admin.product.list',$data);
    }

    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $productTypes = ProductType::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['productTypes'] = $productTypes;
        $data['brands'] = $brands;

        return view('admin.product.create',$data);
    }

    public function store(Request $request){
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && ($request->track_qty == 'Yes')){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

            $formData = new Product;
            $formData->title = $request->title;
            $formData->slug = $request->slug;
            $formData->short_description = $request->short_description;
            $formData->description = $request->description;
            $formData->direction = $request->direction;
            $formData->ingredients = $request->ingredients;
            $formData->benefits = $request->benefits;
            $formData->shipping_returns = $request->shipping_returns;
            $formData->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            //$formData->packagings = (!empty($request->packagings)) ? implode(',',$request->packagings) : '';
            $formData->sizes = (!empty($request->sizes)) ? implode(',',$request->sizes) : '';
            $formData->price = $request->price;
            $formData->compare_price = $request->compare_price;
            $formData->sku = $request->sku;
            $formData->barcode = $request->barcode;
            $formData->track_qty = $request->track_qty;
            $formData->qty = $request->qty;
            $formData->status = $request->status;
            $formData->category_id = $request->category;
            $formData->sub_categories_id = $request->sub_category;
            $formData->brand_id = $request->brand;
            $formData->product_type_id = $request->productType;
            $formData->is_featured = $request->is_featured;
            $formData->save();

            //save Gallery Pics
            if(!empty($request->image_array)) {
                foreach ($request->image_array as $key => $temp_image_id) {
                    
                    $tempImageInfo = TempImage::find($temp_image_id);
                    dd($tempImageInfo);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); //jpg,jpeg,png,etc

                    $productImage = new ProductImage;
                    $productImage->product_id = $formData->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $formData->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    // product_id => 4; product_image_id => 1;
                    // 4-1-timestamp.jpg
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Product Thumbnails

                    //Large Image

                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400,null,function($constraint){
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath,60);


                    //Small Image

                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300,300);
                    $image->save($destPath,60);

                }

            }

            session()->flash('success','Product added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Added Successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request,$id){


        $products = Product::find($id);

        if(empty($products)){
            return redirect()->route('products.index')->with('error','Product not found');
        }
        //fetch images
        $productImages = ProductImage::where('product_id',$products->id)->get();

        $subCategories = SubCategory::where('category_id',$products->category_id)->get();
        
        $relatedProducts = [];
        // related products
        if($products->related_products != '') {
            $productArray = explode(',',$products->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->get();
        }

        $productTypes = [];
        // product types
        if($products->product_types != '') {
            $productTypeArray = explode(',',$products->product_types);
            $productTypes = ProductType::whereIn('id',$productTypeArray)->get();
        }

        // $packagings = [];
        // // product types
        // if($products->packagings != '') {
        //     $packagingArray = explode(',',$products->packagings);
        //     $packagings = Packaging::whereIn('id',$packagingArray)->get();
        // }

        $sizes = [];
        // product types
        if($products->sizes != '') {
            $sizeArray = explode(',',$products->sizes);
            $sizes = Sizes::whereIn('id',$sizeArray)->get();
        }



        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $productTypes = ProductType::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['products'] = $products;
        $data['brands'] = $brands;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;
         $data['productTypes'] = $productTypes;
        // $data['packagings'] = $packagings;
        $data['sizes'] = $sizes;

        return view('admin.product.edit',$data);
    }

    public function update(Request $request,$id){
        $formData = Product::find($id);
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$formData->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$formData->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && ($request->track_qty == 'Yes')){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

            
            $formData->title = $request->title;
            $formData->slug = $request->slug;
            $formData->short_description = $request->short_description;
            $formData->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
           // $formData->packagings = (!empty($request->packagings)) ? implode(',',$request->packagings) : '';
            $formData->sizes = (!empty($request->sizes)) ? implode(',',$request->sizes) : '';
            $formData->description = $request->description;
            $formData->direction = $request->direction;
            $formData->ingredients = $request->ingredients;
            $formData->benefits = $request->benefits;
            $formData->shipping_returns = $request->shipping_returns;
            $formData->price = $request->price;
            $formData->compare_price = $request->compare_price;
            $formData->sku = $request->sku;
            $formData->barcode = $request->barcode;
            $formData->track_qty = $request->track_qty;
            $formData->qty = $request->qty;
            $formData->status = $request->status;
            $formData->category_id = $request->category;
            $formData->sub_categories_id = $request->sub_category;
            $formData->brand_id = $request->brand;
            $formData->product_type_id = $request->productType;
            $formData->is_featured = $request->is_featured;
            $formData->save();

            //save Gallery Pics
            if(!empty($request->image_array)) {
                foreach ($request->image_array as $key => $temp_image_id) {
                    
                    $tempImageInfo = ProductImage::find($temp_image_id);
                    if(empty($tempImageInfo)){
                        $productImage = new ProductImage;
                    $productImage->product_id = $formData->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $formData->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    // product_id => 4; product_image_id => 1;
                    // 4-1-timestamp.jpg
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Product Thumbnails

                    //Large Image

                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400,null,function($constraint){
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath,60);


                    //Small Image

                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300,300);
                    $image->save($destPath,60);
                    }

                    

                }

            }
            session()->flash('success','Product Updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product Updated Successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request,$id){
        $product = Product::find($id);

        if(empty($product)) {
            session()->flash('error','Product Not Found');

            return response()->json([
                'status' =>false,
                'notFound' => true
            ]);
        }

       $productImages= ProductImage::where('product_id',$id)->get();

       if(!empty($productImages)) {
         foreach($productImages as $productImage) {
            File::delete(public_path('uploads/product/large/'.$productImage->image));
            File::delete(public_path('uploads/product/small/'.$productImage->image));
            }

            ProductImage::where('product_id',$id)->delete();
        }

        $product->delete();

        session()->flash('success','Product Deleted successfully');

            return response()->json([
                'status' =>true,
                'message' => 'Product deleted successfully'
            ]);
    }

    public function getProducts(Request $request){
        $tempProduct = [];
        if($request->term){
            $products = Product::where('title','like','%'.$request->term.'%')->get();
            
            if($products->isNotEmpty()){
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id,'text' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    public function getProductTypes(Request $request){
        $tempProductType = [];
        if($request->term){
            $productTypes = ProductType::where('name','like','%'.$request->term.'%')->get();
            
            if($productTypes->isNotEmpty()){
                foreach ($productTypes as $productType) {
                    $tempProductType[] = array('id' => $productType->id,'text' => $productType->name);
                }
            }
        }

        return response()->json([
            'tags' => $tempProductType,
            'status' => true
        ]);
    }

    // public function getProductPackagings(Request $request){
    //     $tempProductPackaging = [];
    //     if($request->term){
    //         $productPackagings = Packaging::where('name','like','%'.$request->term.'%')->get();
            
    //         if($productPackagings->isNotEmpty()){
    //             foreach ($productPackagings as $productPackaging) {
    //                 $tempProductPackaging[] = array('id' => $productPackaging->id,'text' => $productPackaging->name);
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'tags' => $tempProductPackaging,
    //         'status' => true
    //     ]);
    // }

    public function getProductSizes(Request $request){
        $tempProductSize = [];
        if($request->term){
            $productSizes = Sizes::where('name','like','%'.$request->term.'%')->get();
            
            if($productSizes->isNotEmpty()){
                foreach ($productSizes as $productSize) {
                    $tempProductSize[] = array('id' => $productSize->id,'text' => $productSize->name);
                }
            }
        }

        return response()->json([
            'tags' => $tempProductSize,
            'status' => true
        ]);
    }
    
}
