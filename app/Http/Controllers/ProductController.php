<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;
use Image;

class ProductController extends Controller
{
    function add_product(){
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        return view('admin.product.add_product',[
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'brands'=>$brands,
        ]);
    }

    function getSubcategory(Request $request){
        $subcategories = Subcategory::where('category_id', $request->category_id)->get();
        $str = '<option value="">-- Select Any --</option>';

        foreach($subcategories as $subcategory){
            $str .= '<option value="'.$subcategory->id.'">'.$subcategory->subcategory_name.'</option>';
        }

        echo $str;
    }

    function product_store(Request $request){
        
    
        $random_number = random_int(10000, 99999);
        $random_number2 = random_int(1000000, 9999999);
        $slug = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2;
        $sku = Str::Upper(str_replace(' ', '-', substr($request->product_name, 0,2))).'-'.$random_number;

        $product_id = Product::insertGetId([
            'product_name'=>$request->product_name,
            'price'=>$request->price,
            'discount'=>$request->discount,
            'after_discount'=>$request->price - ($request->price*$request->discount)/100,
            'category_id'=>$request->category_id,
            'subcategory_id'=>$request->subcategory_id,
            'brand'=>$request->brand,
            'short_desp'=>$request->short_desp,
            'long_desp'=>$request->long_desp,
            'additional_info'=>$request->additional_info,
            'sku'=>$sku,
            'slug'=>$slug,
            'created_at'=>Carbon::now(),
        ]);

        $preview_image = $request->preview;
        if($preview_image != ''){
            $extension = $preview_image->getClientOriginalExtension();
            $file_name = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.'.'.$extension;

            Image::make($preview_image)->save(public_path('uploads/product/preview/'.$file_name));
            Product::find($product_id)->update([
                'preview'=>$file_name,
            ]);
        }
        
        $gallery_images = $request->gallery;

        if($gallery_images != ''){
            foreach($gallery_images as $sl=>$gallery){
                $extension_gallery = $gallery->getClientOriginalExtension();
                $file_name_gallery = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.$sl.'.'.$extension_gallery;
                Image::make($gallery)->save(public_path('uploads/product/gallery/'.$file_name_gallery));
    
                ProductGallery::insert([
                    'product_id'=>$product_id,
                    'gallery'=>$file_name_gallery,
                    'created_at'=>Carbon::now(),
                ]);
            }
        }

        return back();
    }

    function product_list(){
        $all_products = Product::all();
        return view('admin.product.product_list', [
            'all_products'=>$all_products,
        ]);
    }
    function product_edit(Request $request){
        $product_info = Product::find($request->product_id);
        $gallery_images = ProductGallery::where('product_id', $request->product_id)->get();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        return view('admin.product.product_edit', [
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'brands'=>$brands,
            'product_info'=>$product_info,
            'gallery_images'=>$gallery_images,
        ]);
    }

    function product_update(Request $request){
        $gallery_images = $request->gallery;
        $random_number2 = random_int(1000000, 9999999);

        //if preview empty
        if($request->preview == ''){
            //if gallery empty
            if($request->gallery == ''){
                Product::find($request->product_id)->update([
                    'product_name'=>$request->product_name,
                    'price'=>$request->price,
                    'discount'=>$request->discount,
                    'after_discount'=>$request->price - ($request->price*$request->discount)/100,
                    'category_id'=>$request->category_id,
                    'subcategory_id'=>$request->subcategory_id,
                    'brand'=>$request->brand,
                    'short_desp'=>$request->short_desp,
                    'long_desp'=>$request->long_desp,
                    'additional_info'=>$request->additional_info,
                    'created_at'=>Carbon::now(),
                ]);
            }
            //if gallery not empty
            else{

                $present_gallery = ProductGallery::where('product_id',$request->product_id)->get();
                foreach($present_gallery as $gal){
                    $delete_from = public_path('uploads/product/gallery/'.$gal->gallery);
                    unlink($delete_from);

                    ProductGallery::where('product_id', $gal->product_id)->delete();
                }

                foreach($gallery_images as $sl=>$gallery){
                    $extension_gallery = $gallery->getClientOriginalExtension();
                    $file_name_gallery = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.$sl.'.'.$extension_gallery;
                    Image::make($gallery)->save(public_path('uploads/product/gallery/'.$file_name_gallery));
        
                    ProductGallery::insert([
                        'product_id'=>$request->product_id,
                        'gallery'=>$file_name_gallery,
                        'created_at'=>Carbon::now(),
                    ]);
                }
            }
        }

        //if preview not empty
        else{
            //if gallery empty
            if($request->gallery == ''){
                $prev_image = Product::find($request->product_id);
                $delete_from = public_path('uploads/product/preview/'.$prev_image->preview);
                unlink($delete_from);

                $preview_image = $request->preview;
                $extension = $preview_image->getClientOriginalExtension();
                $file_name = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.'.'.$extension;

                Image::make($preview_image)->save(public_path('uploads/product/preview/'.$file_name));
                Product::find($request->product_id)->update([
                    'product_name'=>$request->product_name,
                    'price'=>$request->price,
                    'discount'=>$request->discount,
                    'after_discount'=>$request->price - ($request->price*$request->discount)/100,
                    'category_id'=>$request->category_id,
                    'subcategory_id'=>$request->subcategory_id,
                    'brand'=>$request->brand,
                    'short_desp'=>$request->short_desp,
                    'long_desp'=>$request->long_desp,
                    'additional_info'=>$request->additional_info,
                    'preview'=>$file_name,
                    'created_at'=>Carbon::now(),
                ]);
            }
            //if gallery not empty
            else{

                $prev_image = Product::find($request->product_id);
                $delete_from = public_path('uploads/product/preview/'.$prev_image->preview);
                unlink($delete_from);

                $preview_image = $request->preview;
                $extension = $preview_image->getClientOriginalExtension();
                $file_name = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.'.'.$extension;

                Image::make($preview_image)->save(public_path('uploads/product/preview/'.$file_name));

                $present_gallery = ProductGallery::where('product_id',$request->product_id)->get();
                foreach($present_gallery as $gal){
                    $delete_from = public_path('uploads/product/gallery/'.$gal->gallery);
                    unlink($delete_from);

                    ProductGallery::where('product_id', $gal->product_id)->delete();
                }

                foreach($gallery_images as $sl=>$gallery){
                    $extension_gallery = $gallery->getClientOriginalExtension();
                    $file_name_gallery = Str::lower(str_replace(' ', '-', $request->product_name)).'-'.$random_number2.$sl.'.'.$extension_gallery;
                    Image::make($gallery)->save(public_path('uploads/product/gallery/'.$file_name_gallery));
        
                    ProductGallery::insert([
                        'product_id'=>$request->product_id,
                        'gallery'=>$file_name_gallery,
                        'created_at'=>Carbon::now(),
                    ]);
                }
                Product::find($request->product_id)->update([
                    'product_name'=>$request->product_name,
                    'price'=>$request->price,
                    'discount'=>$request->discount,
                    'after_discount'=>$request->price - ($request->price*$request->discount)/100,
                    'category_id'=>$request->category_id,
                    'subcategory_id'=>$request->subcategory_id,
                    'brand'=>$request->brand,
                    'short_desp'=>$request->short_desp,
                    'long_desp'=>$request->long_desp,
                    'additional_info'=>$request->additional_info,
                    'preview'=>$file_name,
                    'created_at'=>Carbon::now(),
                ]);
            }
        }
        return back();
    }
}
