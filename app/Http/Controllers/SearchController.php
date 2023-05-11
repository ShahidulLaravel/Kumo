<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function search(Request $request){
        $data = $request->all();

        $sorting = 'created_at';
        $type = 'DESC';

        if (!empty($data['sort']) && $data['sort'] != '' && $data['sort'] != 'undefined') {
            if ($data['sort'] == 1) {
                $sorting = 'product_name';
                $type = 'ASC';
            } else if ($data['sort'] == 2) {
                $sorting = 'product_name';
                $type = 'DESC';
            } else if ($data['sort'] == 3) {
                $sorting = 'after_discount';
                $type = 'ASC';
            } else if ($data['sort'] == 4) {
                $sorting = 'after_discount';
                $type = 'DESC';
            }
        }
        
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();
        $searched_product = Product::where(function($q) use ($data){

            $min = 0;
            if(!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined'){
               $min = $data['min'];
            }
            else{
                $min = 1;
            }
            $max = 0;
            if(!empty($data['max']) && $data['max'] != '' && $data['max'] != 'undefined'){
               $max = $data['max'];
            }
            else{
                $max = 10000000;
            }

            if(!empty($data['q']) && $data['q'] != '' && $data['q'] != 'undefined'){
                $q->where(function($q) use ($data){
                    $q->where('product_name', 'like', '%'.$data['q'].'%');
                    $q->orWhere('long_desp', 'like', '%'.$data['q'].'%');
                });
            }

            if(!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined' || !empty($data['max']) && $data['max'] != '' && $data['max'] != 'undefined'){
                $q->whereBetween('after_discount', [$min, $max]);
            }

            if(!empty($data['category_id']) && $data['category_id'] != '' && $data['category_id'] != 'undefined'){
                $q->where('category_id', $data['category_id']);
            }
            if(!empty($data['brand_id']) && $data['brand_id'] != '' && $data['brand_id'] != 'undefined'){
                $q->where('brand', $data['brand_id']);
            }
            if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined' || !empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined'){
                $q->whereHas('rel_to_inventory', function ($q) use ($data){
                    if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined'){
                        $q->whereHas('rel_to_color', function ($q) use ($data){
                            $q->where('colors.id', $data['color_id']);
                        });
                    }
                    if(!empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined'){
                        $q->whereHas('rel_to_size', function ($q) use ($data){
                            $q->where('sizes.id', $data['size_id']);
                        });
                    }
                });
            }

        })->orderBy($sorting, $type)->get();
        return view('frontend.search', [
            'searched_product'=>$searched_product,
            'categories'=>$categories,
            'brands'=>$brands,
            'colors'=>$colors,
            'sizes'=>$sizes,
        ]);
    }
}
