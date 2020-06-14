<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{

    public function index(){
        return view("home");
    }

    public function dashboard(){
        return view("dashboard");
    }

    public function listCategory(){
        // Query builder
        //$categories = DB::table("categories")->get();
        // Model (ORM)
        $categories = Category::withCount("Products")->paginate(20);
        // show with condition: start from D
        //$categories = Category::where("category_name","LIKE","D%")->get();
        // dd($categories);
        return view("category.list",[
            "categories"=> $categories
        ]);
    }

    public function newCategory(){
        return view("category.new");
    }

    public function saveCategory(Request $request){
        $request->validate([
            "category_name"=> "required|string|min:3|unique:categories"
        ]);
        try{
            Category::create([
                "category_name"=> $request->get("category_name")
            ]); // return an Object of Category Model
//            DB::table("categories")->insert([
//                "category_name"=> $request->get("category_name"),
//                "created_at"=> Carbon::now(),
//                "updated_at"=> Carbon::now(),
//            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("admin/list-category");
    }

    public function editCategory($id){
//        $category = Category::find($id);
//        if(is_null($category))
//            abort(404);
        $category = Category::findOrFail($id);
        return view("category.edit",["category"=>$category]);
    }

    public function updateCategory($id,Request $request){
        $category = Category::findOrFail($id);
        $request->validate([
            "category_name"=> "required|min:3|unique:categories,category_name,{$id}"
        ]);
        try {
            $category->update([
                "category_name"=> $request->get("category_name")
            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("admin/list-category");
    }

    public function deleteCategory($id){
        $category = Category::findOrFail($id);
        try{
            $category->delete();
        }catch (\Exception $exception){
        }
        return redirect()->to("admin/list-category");
    }
    //product
    public function editProduct($id){
        $product = Product::findOrFail($id);
        $category = Category::all();
        $brand = Brand::all();
        return view("product.edit",["product"=>$product,"categories"=>$category,"brands"=>$brand]);
    }

    public function updateProduct($id,Request $request){
        $product = Product::findOrFail($id);
        $request->validate([
            "product_name" => "required|min:3|unique:products,product_name,{$id}",
            "product_desc" => "required",
            "product_color"=>"required|string|min:2",
            "product_size"=>"required|string",
            "price" => "required|numeric|min:0",
            "qty" => "required|numeric|min:1",
            "category_id" => "required",
            "brand_id" => "required",
        ]);

        try {
            $product_image = $product->get("product_image");
            if($request->hasFile("product_image")){
                $file = $request->file("product_image");
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();
                if(in_array($extName,$allow)){
                    $fileName = time().$file->getClientOriginalName(); //  lấy tên gốc original của file gửi lên từ client
                    $file->move(public_path("media"),$fileName); // đẩy file vào thư mục media với tên là fileName
                    //convert string to ProductImage
                    $product_image = "media/".$fileName; // lấy nguồn file
                }
            }
            $product->update([
                "product_name"=>$request->get("product_name"),
                "product_image"=>$product_image,
                "product_desc"=>$request->get("product_desc"),
                "product_color"=>$request->get("product_color"),
                "product_size"=>$request->get("product_size"),
                "price"=>$request->get("price"),
                "qty"=>$request->get("qty"),
                "category_id"=>$request->get("category_id"),
                "brand_id"=>$request->get("brand_id"),
            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("admin/list-product");
    }

    public function deleteProduct($id){
        $product = Product::findOrfail($id);
        try {
            $product->delete();
        }catch (\Exception $exception){

        }
        return redirect()->to("admin/list-product");
    }

    public function listProduct(){
//        $products = Product::leftJoin("categories","categories.id","=","products.category_id")
//                            ->leftJoin("brands","brands.id","=","products.brand_id")
//                            ->select("products.*","categories.category_name","brands.brand_name")
//                            ->paginate(20);
        $products = Product::with("Category")->with("Brand")->orderBy("id","desc")->paginate(20);
        return view("product.list",["products"=>$products]);
    }

    public function newProduct(){
        $categories = Category::all();
        $brands = Brand::all();
        return view("product.new",[
            "categories"=>$categories,
            "brands" => $brands
        ]);
    }

    public function saveProduct(Request $request){
        $request->validate([
            "product_name"=> "required",
            "product_desc"=> "required",
            "price"=> "required|numeric|min:0",
            "qty"=> "required|numeric|min:1",
            "category_id"=> "required",
            "brand_id"=> "required",
        ]);
        try {
            $productImage = null;
            // xu ly de dua anh len thu muc media trong public
            // sau do lay nguon file cho vao bien $productImage
            if($request->hasFile("product_image")){
                $file = $request->file("product_image");
                $allow = ["png","jpg","jpeg","gif"];
                $extName = $file->getClientOriginalExtension();// lay duoi file
                if(in_array($extName,$allow)){
                    // get fileName
                    $fileName = time().$file->getClientOriginalName();
                    // upload file into public/media
                    $file->move(public_path("media"),$fileName);
                    // convert string to productImage
                    $productImage = "media/".$fileName;
                }
            }
            Product::create([
                "product_name"=> $request->get("product_name"),
                "product_image"=>$productImage,
                "product_desc"=> $request->get("product_desc"),
                "price"=> $request->get("price"),
                "qty"=> $request->get("qty"),
                "category_id"=> $request->get("category_id"),
                "brand_id"=> $request->get("brand_id"),
            ]);
        }catch (\Exception $e){
            return redirect()->back();
        }
        return redirect()->to("admin/list-product");
    }

    //brand
    public function listBrand(){
        $brands =Brand::all();
        return view("brand.list",[
            "brands"=>$brands
        ]);
    }

    public function newBrand(){
        return view("/brand.new");
    }

    public function saveBrand(Request $request){
        $request->validate([
            "brand_name"=>"required|string|min:3|unique:brands",
        ]);

        try {
            $brand_image=null;
            if($request->hasFile("brand_image")){
                $file=$request->file("brand_image");
                $allow=["png","jpg","jpeg","gif"];
                $extName=$file->getClientOriginalExtension();
                if(in_array($extName,$allow)){
                    $fileName=time().$file->getClientOriginalName();
                    $file->move(public_path("media"),$fileName);
                    $brand_image="media/".$fileName;
                }
            }
            Brand::create([
                "brand_name"=>$request->get("brand_name"),
                "brand_image"=>$brand_image,
            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("admin/list-brand");
    }

    public function editBrand($id){
        $brand = Brand::findOrFail($id);
        return view("brand.edit",["brand"=>$brand]);
    }

    public function updateBrand($id,Request $request){
        $brand = Brand::findOrFail($id);
        $request->validate([
            "brand_name"=>"required|min:6|unique:brands,brand_name,{$id}"
        ]);
        try{
            $brand_image=$brand->get("brand_image");
            if($request->hasFile("brand_image")){
                $file=$request->file("brand_image");
                $allow=["png","jpg","jpeg","gif"];
                $extName=$file->getClientOriginalExtension();
                if(in_array($extName,$allow)){
                    $fileName=time().$file->getClientOriginalName();
                    $file->move(public_path("media"),$fileName);
                    $brand_image="media/".$fileName;
                }
            }
            $brand->update([
                "brand_name"=>$request->get("brand_name"),
                "brand_image"=>$brand_image,
            ]);
        }catch (\Exception $exception){
            return redirect()->back();
        }
        return redirect()->to("admin/list-brand");
    }

    public function deleteBrand($id){
        $brand = Brand::findOrFail($id);
        try {
            $brand->delete();
        }catch (\Exception $exception){

        }
        return redirect()->to("admin/list-brand");
    }

}
