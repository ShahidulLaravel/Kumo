<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Str;
use Image;

class BrandController extends Controller
{
    function brand(){
        $brands = Brand::Paginate(5);
        return view('admin.brand.add_brand', [
            'brands'=>$brands,
        ]);
    }
    function brand_store(Request $request){
        $brand_id = Brand::insertGetId([
            'brand_name'=>$request->brand_name,
        ]);

        if($request->brand_logo != ''){
            $random_number = random_int(100000, 999998);
            $brand_logo = $request->brand_logo;
            $extension = $brand_logo->getClientOriginalExtension();
            $file_name = Str::lower(str_replace(' ', '-', $request->brand_name)).'-'.$random_number.'.'.$extension;
            Image::make($brand_logo)->save(public_path('uploads/brand/'.$file_name));

            Brand::find($brand_id)->update([
                'brand_logo'=>$file_name,
            ]);
        }

        return back();
    }
}
