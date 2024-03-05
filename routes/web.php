<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Clear application cache:

// Route::get('/clear-cache', function() {
//     Artisan::call('cache:clear');
//     return 'Application cache has been cleared;';
// });
// //Clear route cache:

// Route::get('/route-cache', function() {
// Artisan::call('route:cache');
//     return 'Routes cache has been cleared;';
// });
// //Clear config cache:

// Route::get('/config-cache', function() {
//   Artisan::call('config:cache');
//   return 'Config cache has been cleared;';
// }); 
// // Clear view cache:

// Route::get('/view-clear', function() {
//     Artisan::call('view:clear');
//     return 'View cache has been cleared;';
// });

Route::get('/','App\Http\Controllers\FrontController@index')->name('front.home');




Route::get('/Categories','App\Http\Controllers\FrontController@category')->name('front.category');

Route::get('/Brands','App\Http\Controllers\FrontController@brand')->name('front.brand');

Route::get('/Categories/{categorySlug?}','App\Http\Controllers\ShopController@index')->name('front.shop');

Route::get('/Categories/{slugCategory}/product/{slug}','App\Http\Controllers\ShopController@product')->name('front.product');

Route::get('/Cart','App\Http\Controllers\CartController@cart')->name('front.cart');

Route::post('/add-to-cart','App\Http\Controllers\CartController@addToCart')->name('front.addToCart');

Route::post('/update-cart','App\Http\Controllers\CartController@updateCart')->name('front.updateCart');

Route::post('/delete-item','App\Http\Controllers\CartController@deleteItem')->name('front.deleteItem');

Route::post('/add-to-wishlist','App\Http\Controllers\FrontController@addToWishlist')->name('front.addToWishlist');
Route::post('/send-contact-email','App\Http\Controllers\FrontController@sendContactEmail')->name('front.sendContactEmail');
Route::get('/forgot-password','App\Http\Controllers\AuthhController@forgotPassword')->name('front.forgotPassword');
Route::post('/process-forgot-password','App\Http\Controllers\AuthhController@processForgotPassword')->name('front.processForgotPassword');
Route::get('/reset-password/{token}','App\Http\Controllers\AuthhController@resetPassword')->name('front.resetPassword');
Route::post('/process-reset-password/{token}','App\Http\Controllers\AuthhController@processResetPassword')->name('front.processResetPassword');

Route::get('/Page/{slug}','App\Http\Controllers\FrontController@page')->name('front.page');




Route::group(['prefix'=>'account'],function(){

    Route::group(['middleware' => 'account.guest'],function(){
        Route::get('/login','App\Http\Controllers\AuthhController@login')->name('account.login');
        Route::post('/login','App\Http\Controllers\AuthhController@authenticate')->name('account.authenticate');

        Route::get('/register','App\Http\Controllers\AuthhController@register')->name('account.register');
        Route::post('/process-register','App\Http\Controllers\AuthhController@processRegister')->name('account.processRegister');


    });

    Route::group(['middleware' => 'account.auth'],function(){
        Route::get('/profile','App\Http\Controllers\AuthhController@profile')->name('account.profile');
        Route::post('/update-profile','App\Http\Controllers\AuthhController@updateProfile')->name('account.updateProfile');
        Route::post('/update-address','App\Http\Controllers\AuthhController@updateAddress')->name('account.updateAddress');
        Route::get('/logOut','App\Http\Controllers\AuthhController@logOut')->name('account.logOut');
        Route::get('/check-out','App\Http\Controllers\CartController@checkOut')->name('front.checkOut');
        Route::post('/process-check-out','App\Http\Controllers\CartController@processCheckOut')->name('front.processCheckOut');
        Route::get('/thanks/{orderId}','App\Http\Controllers\CartController@thankyou')->name('front.thankyou');
        Route::post('/get-order-summary','App\Http\Controllers\CartController@getOrderSummary')->name('front.getOrderSummary');
        Route::get('/my-orders','App\Http\Controllers\AuthhController@myOrders')->name('account.myOrders');
        Route::get('/order-details/{order_id}','App\Http\Controllers\AuthhController@orderDetails')->name('account.orderDetails');

        Route::get('/Shipping-States','App\Http\Controllers\admin\ShippingStateController@index')->name('shipping-states.index');
        Route::get('/Shipping-Charge','App\Http\Controllers\admin\ShippingStateController@charge')->name('shipping-charge.charge');

        Route::post('/apply-discount','App\Http\Controllers\CartController@applyDiscount')->name('front.applyDiscount');

        Route::post('/remove-discount','App\Http\Controllers\CartController@removeCoupon')->name('front.removeDiscount');
        Route::get('/my-wishlist','App\Http\Controllers\AuthhController@wishlist')->name('account.wishlist');
        Route::post('/remove-product-from-wishlist','App\Http\Controllers\AuthhController@removeProductFromWishlist')->name('account.removeProductFromWishlist');

        Route::get('/change-password','App\Http\Controllers\AuthhController@showChangePassword')->name('account.changePassword');
        Route::post('/process-change-password','App\Http\Controllers\AuthhController@changePassword')->name('account.processChangePassword');
        Route::post('/send-checkout-mail','App\Http\Controllers\CartController@sendMailCheckout')->name('front.sendMailCheckout');
        
    });
});

Route::group(['prefix'=>'admin'],function(){

    Route::group(['middleware' => 'admin.guest'],function(){
        Route::post('/authenticate','App\Http\Controllers\admin\AdminLoginController@authenticate')->name('admin.authenticate');
        Route::get('/login','App\Http\Controllers\admin\AdminLoginController@index')->name('admin.login');


    });

    Route::group(['middleware' => 'admin.auth'],function(){

        Route::get('/dashboard','App\Http\Controllers\admin\HomeController@index')->name('admin.dashboard');
        Route::get('/logout','App\Http\Controllers\admin\HomeController@logout')->name('admin.logout');

        // Category Routes

        Route::get('/Categories','App\Http\Controllers\admin\CategoryController@index')->name('categories.index');

        Route::get('/Categories/Create','App\Http\Controllers\admin\CategoryController@create')->name('categories.create');
        Route::post('/Categories','App\Http\Controllers\admin\CategoryController@store')->name('categories.store');
        Route::get('/upload','App\Http\Controllers\admin\HomeController@upload')->name('image.upload');
        Route::post('/upload-temp-image','App\Http\Controllers\admin\TempImagesController@create')->name('temp-images.create');
        Route::delete('/upload-temp-image/{imageId}','App\Http\Controllers\admin\TempImagesController@destroy')->name('temp-images.delete');
        Route::get('/Categories/{category}/edit','App\Http\Controllers\admin\CategoryController@edit')->name('categories.edit');
        Route::put('/Categories/{category}','App\Http\Controllers\admin\CategoryController@update')->name('categories.update');
        Route::delete('/Categories/{category}','App\Http\Controllers\admin\CategoryController@destory')->name('categories.delete');


        

        Route::get('/getSlug',function (Request $request){
            $slug = '';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');


        // Sub Category Routes
        Route::get('/Sub-Categories','App\Http\Controllers\admin\SubCategoryController@index')->name('sub-categories.index');
        Route::get('/Sub-Categories/Create','App\Http\Controllers\admin\SubCategoryController@create')->name('sub-categories.create');
        Route::post('/Sub-Categories','App\Http\Controllers\admin\SubCategoryController@store')->name('sub-categories.store');
        // Route::get('/upload','App\Http\Controllers\admin\HomeController@upload')->name('image.upload');
        // Route::post('/upload-temp-image','App\Http\Controllers\admin\TempImagesController@create')->name('temp-images.create');
        Route::get('/Sub-Categories/{subcategory}/edit','App\Http\Controllers\admin\SubCategoryController@edit')->name('sub-categories.edit');
        Route::put('/Sub-Categories/{subcategory}','App\Http\Controllers\admin\SubCategoryController@update')->name('sub-categories.update');
        Route::delete('/Sub-Categories/{subcategory}','App\Http\Controllers\admin\SubCategoryController@destrtoy')->name('sub-categories.delete');

        // Brand Routes

        Route::get('/Brand','App\Http\Controllers\admin\BrandController@index')->name('brands.index');

        Route::get('/Brand/Create','App\Http\Controllers\admin\BrandController@create')->name('brands.create');
        Route::post('/Brand','App\Http\Controllers\admin\BrandController@store')->name('brands.store');
        Route::get('/Brand/{brand}/edit','App\Http\Controllers\admin\BrandController@edit')->name('brands.edit');
        Route::put('/Brand/{brand}','App\Http\Controllers\admin\BrandController@update')->name('brands.update');
        Route::delete('/Brand/{brand}','App\Http\Controllers\admin\BrandController@destroy')->name('brands.delete');

        // Product Routes

        Route::get('/Product','App\Http\Controllers\admin\ProductController@index')->name('products.index');

        Route::get('/Product/Create','App\Http\Controllers\admin\ProductController@create')->name('products.create');
        Route::post('/Product','App\Http\Controllers\admin\ProductController@store')->name('products.store');
        Route::get('/Product/{product}/edit','App\Http\Controllers\admin\ProductController@edit')->name('products.edit');
        Route::put('/Product/{product}','App\Http\Controllers\admin\ProductController@update')->name('products.update');
        Route::delete('/Product/{product}','App\Http\Controllers\admin\ProductController@destroy')->name('products.delete');

        Route::get('/get-products','App\Http\Controllers\admin\ProductController@getProducts')->name('products.getProducts');
        Route::get('/get-product-types','App\Http\Controllers\admin\ProductController@getProductTypes')->name('products.getProductTypes');
        Route::get('/get-product-packagings','App\Http\Controllers\admin\ProductController@getProductPackagings')->name('products.getProductPackagings');
        Route::get('/get-product-sizes','App\Http\Controllers\admin\ProductController@getProductSizes')->name('products.getProductSizes');

        Route::get('/Product-SubCategories','App\Http\Controllers\admin\ProductSubCategoryController@index')->name('product-subcategory.index');

        Route::post('/Product-Images','App\Http\Controllers\admin\ProductImageController@update')->name('product-images.update');
        Route::delete('/Product-Images/{imageId}','App\Http\Controllers\admin\ProductImageController@destroy')->name('product-images.delete');

        // Shipping Routes

        Route::get('/Shipping/Create','App\Http\Controllers\admin\ShippingController@create')->name('shipping.create');
        Route::post('/Shipping','App\Http\Controllers\admin\ShippingController@store')->name('shipping.store');
        Route::get('/Shipping/{shipping}/edit','App\Http\Controllers\admin\ShippingController@edit')->name('shipping.edit');
        Route::put('/Shipping/{shipping}','App\Http\Controllers\admin\ShippingController@update')->name('shipping.update');
        Route::delete('/Shipping/{shipping}','App\Http\Controllers\admin\ShippingController@destroy')->name('shipping.delete');

        Route::get('/Shipping-State','App\Http\Controllers\admin\ShippingStateController@index')->name('shipping-state.index');

         // Coupon Code Routes

        //  Route::get('/Product','App\Http\Controllers\admin\ProductController@index')->name('products.index');

         Route::get('/Coupons/Create','App\Http\Controllers\admin\DiscountCodeController@create')->name('coupons.create');
          Route::post('/Coupons','App\Http\Controllers\admin\DiscountCodeController@store')->name('coupons.store');
          Route::get('/Coupons/{coupon}/edit','App\Http\Controllers\admin\DiscountCodeController@edit')->name('coupons.edit');
         Route::put('/Coupons/{coupon}','App\Http\Controllers\admin\DiscountCodeController@update')->name('coupons.update');
         Route::delete('/Coupons/{coupon}','App\Http\Controllers\admin\DiscountCodeController@destroy')->name('coupons.delete');
         Route::get('/Coupons','App\Http\Controllers\admin\DiscountCodeController@index')->name('coupons.index');

         //order routes

         Route::get('/Orders','App\Http\Controllers\admin\OrderController@index')->name('orders.index');
         Route::get('/Orders/{order_id}','App\Http\Controllers\admin\OrderController@detail')->name('orders.detail');
         Route::post('/Order/change-status/{id}','App\Http\Controllers\admin\OrderController@changeOrderStatus')->name('orders.changeOrderStatus');
         Route::post('/Order/send-email/{id}','App\Http\Controllers\admin\OrderController@sendInvoiceEmail')->name('orders.sendInvoiceEmail');

        //User routes
        Route::get('/Users','App\Http\Controllers\admin\UserController@index')->name('users.index');
        Route::get('/Users/Create','App\Http\Controllers\admin\UserController@create')->name('users.create');
          Route::post('/Users','App\Http\Controllers\admin\UserController@store')->name('users.store');
          Route::get('/Users/{user}/edit','App\Http\Controllers\admin\UserController@edit')->name('users.edit');
         Route::put('/Users/{user}','App\Http\Controllers\admin\UserController@update')->name('users.update');
         Route::delete('/Users/{user}','App\Http\Controllers\admin\UserController@destory')->name('users.delete');
    
         //Page routes
        Route::get('/Pages','App\Http\Controllers\admin\PageController@index')->name('pages.index');
        Route::get('/Pages/Create','App\Http\Controllers\admin\PageController@create')->name('pages.create');
          Route::post('/Pages','App\Http\Controllers\admin\PageController@store')->name('pages.store');
          Route::get('/Pages/{page}/edit','App\Http\Controllers\admin\PageController@edit')->name('pages.edit');
         Route::put('/Pages/{page}','App\Http\Controllers\admin\PageController@update')->name('pages.update');
         Route::delete('/Pages/{page}','App\Http\Controllers\admin\PageController@destroy')->name('pages.delete');
    
         //setting routes
        Route::get('/change-password','App\Http\Controllers\admin\SettingController@showChangePassword')->name('admin.showChangePassword');
        Route::post('/process-change-password','App\Http\Controllers\admin\SettingController@changePassword')->name('admin.processChangePassword');
        

        // Packaging Routes

        Route::get('/Packaging','App\Http\Controllers\admin\PackagingController@index')->name('packagings.index');

        Route::get('/Packaging/Create','App\Http\Controllers\admin\PackagingController@create')->name('packagings.create');
        Route::post('/Packaging','App\Http\Controllers\admin\PackagingController@store')->name('packagings.store');
        Route::get('/Packaging/{pakaging}/edit','App\Http\Controllers\admin\PackagingController@edit')->name('packagings.edit');
        Route::put('/Packaging/{pakaging}','App\Http\Controllers\admin\PackagingController@update')->name('packagings.update');
        Route::delete('/Packaging/{pakaging}','App\Http\Controllers\admin\PackagingController@destory')->name('packagings.delete');

        // ProductType Routes

        Route::get('/ProductType','App\Http\Controllers\admin\ProductTypeController@index')->name('product-types.index');

        Route::get('/ProductType/Create','App\Http\Controllers\admin\ProductTypeController@create')->name('product-types.create');
        Route::post('/ProductType','App\Http\Controllers\admin\ProductTypeController@store')->name('product-types.store');
        Route::get('/ProductType/{producttype}/edit','App\Http\Controllers\admin\ProductTypeController@edit')->name('product-types.edit');
        Route::put('/ProductType/{producttype}','App\Http\Controllers\admin\ProductTypeController@update')->name('product-types.update');
        Route::delete('/ProductType/{producttype}','App\Http\Controllers\admin\ProductTypeController@destory')->name('product-types.delete');


        // Size Routes

        Route::get('/Size','App\Http\Controllers\admin\SizeController@index')->name('sizes.index');

        Route::get('/Size/Create','App\Http\Controllers\admin\SizeController@create')->name('sizes.create');
        Route::post('/Size','App\Http\Controllers\admin\SizeController@store')->name('sizes.store');
        Route::get('/Size/{size}/edit','App\Http\Controllers\admin\SizeController@edit')->name('sizes.edit');
        Route::put('/Size/{size}','App\Http\Controllers\admin\SizeController@update')->name('sizes.update');
        Route::delete('/Size/{size}','App\Http\Controllers\admin\SizeController@destory')->name('sizes.delete');

    });
});