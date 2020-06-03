<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
//        $products=Product::all();
//        foreach ($products as $p){
//            $slug=\Illuminate\Support\Str::slug($p->__get("product_name"));
//            $p->slug=$slug=$slug.$p->__get("id");
//            $p->save();
//            //$p->update(["slug"=>$slug.$p->__get("id");
//        }
        //die("home");
        // $categories=Category::orderBy("created_at","ASC")->get();
        $featureds = Product::orderBy("updated_at", "DESC")->limit(8)->get();
        $lastest_1 = Product::orderBy("created_at", "DESC")->limit(3)->get();
        $lastest_2 = Product::orderBy("created_at", "DESC")->offset(3)->limit(3)->get();
        return view('frontend.home', [
            //  "categories"=>$categories,
            "featureds" => $featureds,
            "lastest_1" => $lastest_1,
            "lastest_2" => $lastest_2,
        ]);
    }

    public function category(Category $category)
    {
        //dd($category);//de kiem tra ket qua ->cach lam frontend voi admin
        //->cai nay chi dung voi orm thoi
        // $categories=Category::all();
        //  $products=Product::where("category_id",$category->__get("id"))->paginate(12);
        $products = $category->Products()->paginate(12);
        return view("frontend.category", [
            "category" => $category,
            // "categories"=>$categories,
            "products" => $products
        ]);
    }
    public function product(Product $product){
        $category=Category::all();
        $products=$product->paginate(12);
        return view("frontend.product",[
            "product"=>$product,
            "category"=>$category

        ]);
    }
}
