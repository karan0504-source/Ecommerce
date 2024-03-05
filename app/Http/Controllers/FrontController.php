<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Pages;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PharIo\Manifest\Url;

class FrontController extends Controller
{
    public function index(){
        
        $products=Product::where('is_featured','Yes')->orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['featuredProducts'] = $products;
        $latestProducts=Product::orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['latestProducts'] = $latestProducts;
        $data['meta_description']="Your search for the perfect shopping destination ends here. Welcome to [Your Ecommerce Website], where convenience meets quality. Explore our collection and shop with confidence!";
        return view('front.home',$data);
    }

    public function addToWishlist(Request $request){
        $user = Auth::guard('account')->user();
        if(!Auth::guard('account')->check()){

            Redirect::setIntendedUrl(url()->previous());
            // return redirect()->route('account.login');
            return response()->json([
                        'status' => false,
                        'message' => 'You need to log in to add products to your wishlist',
                    ]);
        }
        $product = Product::where('id',$request->id)->first();
        //dd($product);
        if($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found</div>',
            ]);
        }
        

        Wishlist::updateOrCreate(
            [
                'front_user_id' => $user->id,
                'product_id' => $request->id
            ],
            [
                'front_user_id' => $user->id,
                'product_id' => $request->id
            ]
            );

        // $wishlist = new Wishlist;
        // $wishlist->front_user_id = $user->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();


        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>'.$product->title.'</strong> Added to wishlist successfully</div>',
        ]);

    }

    public function page($slug){
        $page = Pages::where('slug',$slug)->first();
        if ($page == null) {
            abort(404);
        } else {
            return view('front.page',[
                'page' => $page,
                'meta_description' => $page->name
            ]);
        }
        
        
    }

    public function sendContactEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|min:8',
            'subject' => 'required|min:10',
        ]);

        if($validator->passes()){
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => "You have received a contact",
            ];
            $admin = User::where('id',1)->first();
            Mail::to($admin->email)->send(new ContactMail($mailData));
            session()->flash('success','Thanks for contacting us, we will get back to you soon.');
            return response()->json([
                'status' => true,
                'message' => 'Thanks for contacting us, we will get back to you soon.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }
    }

    public function category(){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $data['categories'] = $categories;
        $data['meta_description'] = "Discover the perfect Category for your life - Browse our curated selection and elevate your shopping experience.";
        return view('front.category',$data);
    }

    public function brand(){
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $data['brands'] = $brands;
        $data['meta_description'] = "Enhance your wellness routine with Brands selection of holistic health products. From supplements to aromatherapy, prioritize your well-being with our curated collection.";
        return view('front.brand',$data);
    }
}
