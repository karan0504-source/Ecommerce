<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Sizes;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // public function index(Request $request,$categorySlug = null,$brandSlug = null){
    //     $categorySelected = '';
    // //    $subCategorySelected = '';
    // $flag = false;
    //    $brandSelected = '';
    //    $productTypesArray = [];
    //    $sizesArray = [];
    //    $filteredProducts = [];
       
    //    $productTypes = ProductType::orderBy('name','ASC')->where('status',1)->get();

       


       
       

    //     $categories = Category::orderBy('name','ASC')->where('status',1)->get();
    //     $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
    //     $sizes = Sizes::orderBy('name','ASC')->where('status',1)->get();
    //     $products = Product::where('status',1);

    //     //Apply Filters Here
        
           
    //     if(!empty($categorySlug)){
    //         $category = Category::where('slug',$categorySlug)->first();
    //         if($category != null) {
    //             $products = $products->where('category_id',$category->id);
    //         $categorySelected = $category->id;
    //         }
    //         $brand = Brand::where('slug',$categorySlug)->first();
    //         if($brand != null){
    //             $products = $products->where('brand_id',$brand->id);
    //         $brandSelected = $brand->id;
    //         }
    //     }

        
       

    //     // if(!empty($subCategorySlug)){
    //     //     $subCategory = SubCategory::where('slug',$subCategorySlug)->first();
    //     //     $products = $products->where('sub_categories_id',$subCategory->id);
    //     //     $subCategorySelected = $subCategory->id;
    //     // }

    //     if(!empty($brandSlug)) {
    //         $brand = Brand::where('slug',$brandSlug)->first();
    //         $products = $products->where('brand_id',$brand);
    //         $brandSelected = $brand->id;
    //     }

    //     if(!empty($request->get('productTypes'))) {
    //         $productTypesArray = explode(',',$request->get('productTypes'));
    //         $products = $products->whereIn('product_type_id',$productTypesArray);
    //     }
    //     if($request->get('price_max') != '' && $request->get('price_min') != '') {
    //         $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
    //     }

    //     if (!empty($request->get('search'))) {
    //         $products = $products->where('title','like','%'.$request->get('search').'%');

    //     }
       
        
    //     if ($request->get('sort') != '') {
    //         if ($request->get('sort') == 'latest') {
    //             $products = $products->orderBy('id','DESC');
    //         } else if ($request->get('sort') == 'price_asc') {
    //             $products = $products->orderBy('price','ASC');
    //         } else {
    //             $products = $products->orderBy('price','DESC');
    //         }
    //     } else {
    //         $products = $products->orderBy('id','DESC');
    //     }
        

    //     if(!empty($request->get('sizes'))) {
    //         $sizesArray = explode(',',$request->get('sizes'));
            

    //         // Filter products based on selected sizes
    //         $products = $products->where(function ($query) use ($sizesArray) {
    //             foreach ($sizesArray as $selectedSize) {
    //                 $query->orWhereRaw("FIND_IN_SET(?, sizes)", [$selectedSize]);
    //             }
    //         });
    //         // $products = $products->whereIn('sizes',$sizesArray);
            
    //     }
        

        
        
        
        
    //     $products = $products->paginate(9);
    //      $data['categories'] = $categories;
    //     $data['brands'] = $brands;
    //     $data['products'] = $products;
    //     $data['sizes'] = $sizes;
    //     $data['categorySelected'] = $categorySelected;
    //     // $data['subCategorySelected'] = $subCategorySelected;
    //     $data['brandSelected'] = $brandSelected;
    //     // $data['brandsArray'] = $brandsArray;
    //     $data['sizesArray'] = $sizesArray;
    //     $data['productTypesArray'] = $productTypesArray;
    //     // $data['filteredProducts'] = $filteredProducts;
    //     $data['productTypes'] = $productTypes;
    //     $data['priceMin'] = intval($request->get('price_min'));
    //     $data['priceMax'] = (intval($request->get('price_max')) == 0)? '500000' : intval($request->get('price_max'));
    //     $data['sort'] = $request->get('sort');
    //     // dd($data);
    //     return view('front.shop',$data);
    // }

    public function index(Request $request, $categorySlug = null, $brandSlug = null)
{
    $categorySelected = '';
    $brandSelected = '';
    $productTypesArray = [];
    $sizesArray = [];
    // Get categories and brands
    $categories = Category::all();
    $brands = Brand::all();
    $sizes = Sizes::all();
    $productTypes = ProductType::all();

    // Get products
    $products = Product::query();

    // Filter by category if provided
    if (!empty($categorySlug)) {
        if ($categorySlug == 'latest-products' || $categorySlug == 'featured-products' || $categorySlug == 'all-products') {
            // Do nothing
        } else {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            } else {
                // Check if the category slug matches any brand slug
                $brand = Brand::where('slug', $categorySlug)->first();
                if ($brand) {
                    $products = $products->where('brand_id', $brand->id);
                    $brandSelected = $brand->id;
                } else {
                    return view('front.error');
                }
            }
        }
    }

    // Filter by brand if provided
    if (!empty($brandSlug)) {
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $products = $products->where('brand_id', $brand->id);
            $brandSelected = $brand->id;
        }
    }

    // Apply other filters
    if (!empty($request->get('productTypes'))) {
        $productTypesArray = explode(',', $request->get('productTypes'));
        $products = $products->whereIn('product_type_id', $productTypesArray);
    }
     

    if ($request->get('price_max') != '' && $request->get('price_min') != '') {
        $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
    }

    if (!empty($request->get('search'))) {
        $products = $products->where('title', 'like', '%' . $request->get('search') . '%');
    }

    if (!empty($request->get('sizes'))) {
        $sizesArray = explode(',', $request->get('sizes'));
        $products = $products->where(function ($query) use ($sizesArray) {
            foreach ($sizesArray as $selectedSize) {
                $query->orWhereRaw("FIND_IN_SET(?, sizes)", [$selectedSize]);
            }
        });
    }

    // Sort the results
    if ($request->get('sort') != '') {
        if ($request->get('sort') == 'latest') {
            $products = $products->orderBy('id', 'DESC');
        } else if ($request->get('sort') == 'price_asc') {
            $products = $products->orderBy('price', 'ASC');
        } else {
            $products = $products->orderBy('price', 'DESC');
        }
    } else {
        $products = $products->orderBy('id', 'DESC');
    }
   

    // Paginate the results
    $products = $products->paginate(9);
    
    // Prepare data for the view
    $data = [
        'categories' => $categories,
        'brands' => $brands,
        'products' => $products,
        'sizes' => $sizes,
        'categorySelected' => $categorySelected,
        'brandSelected' => $brandSelected,
        'productTypesArray' => $productTypesArray,
        'sizesArray' => $sizesArray,
        'productTypes' => $productTypes,
        'priceMin' => intval($request->get('price_min')),
        'priceMax' => intval($request->get('price_max', 500000)),
        'sort' => $request->get('sort'),
        'meta_description' => 'Explore our wide range of products and find the perfect items for your needs.'
    ];
    return view('front.shop', $data);
}

    public function product($categorySlug = null,$slug = null){
        $flag = 0;

        $product = Product::where('slug',$slug)->with('product_images')->first();
        if($product == Null){
            abort(404);
        }
        if($product->description != null){
            if($flag == 0)
            $flag = 1;
        }
        if($product->direction != null){
            if($flag == 0)
            $flag = 2;
        }
        if($product->benefits != null){
            if($flag == 0)
            $flag = 3;
        }
        if($product->ingredients != null){
            if($flag == 0)
            $flag = 4;
        }
        //Fetch Related Products
        $relatedProducts = [];
        // related products
        if($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
        
            $relatedProducts = Product::whereIn('id',$productArray)->where('status',1)->with('product_images')->get();
        }
        $categories = Category::orderBy('name','ASC')->get();
        $data['relatedProducts'] = $relatedProducts;
        $data['product'] = $product;
        $data['flag'] = $flag;
        $data['meta_description'] = $product->description;
        
        return view('front.product',$data);
    }
}
