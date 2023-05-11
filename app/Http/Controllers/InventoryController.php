<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    function variation(){
        $colors = Color::all();
        $sizes = Size::all();
        $categories = Category::all();
        return view('admin.product.variation',[
            'colors'=>$colors,
            'sizes'=>$sizes,
            'categories'=>$categories,
        ]);
    }
    function variation_store(Request $request){
        if($request->btn == 1){
            Color::insert([
                'color_name'=>$request->color_name,
                'color_code'=>$request->color_code,
                'created_at'=>Carbon::now(),
            ]);
            return back();
        }
        else{
            Size::insert([
                'category_id'=>$request->category_id,
                'size_name'=>$request->size_name,
                'created_at'=>Carbon::now(),
            ]);
            return back();
        }
        
    }


    function product_inventory($product_id){
        $colors = Color::all();
        $product_info = Product::find($product_id);
        $sizes = Size::where('category_id', $product_info->category_id)->get();
        $inventories = Inventory::where('product_id', $product_id)->get();
        return view('admin.product.inventory', [
            'colors'=>$colors,
            'sizes'=>$sizes,
            'product_info'=>$product_info,
            'inventories'=>$inventories,
        ]);
    }

    function inventory_store(Request $request){
        if(Inventory::where('product_id', $request->product_id)->where('color_id', $request->color_id)->where('size_id', $request->size_id)->exists()){
            Inventory::where('product_id', $request->product_id)->where('color_id', $request->color_id)->where('size_id', $request->size_id)->increment('quantity', $request->quantity);
            return back();
        }
        else{
            Inventory::insert([
                'product_id'=>$request->product_id,
                'color_id'=>$request->color_id,
                'size_id'=>$request->size_id,
                'quantity'=>$request->quantity,
                'created_at'=>Carbon::now(),
            ]);
            return back();
        }
    }
}
