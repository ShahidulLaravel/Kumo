<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Str;
use Image;

class SubCategoryController extends Controller
{
    function subcategory(){
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.category.subcategory', [
            'categories'=>$categories,
            'subcategories'=>$subcategories,
        ]);
    }

    function subcategory_store(Request $request){

        if($request->subcategory_image == ''){
            Subcategory::insert([
                'subcategory_name'=>$request->subcategory_name,
                'category_id'=>$request->category_id,
            ]);
        }
        else{
            $random_number = random_int(100000, 999998);
            $subcategory_image = $request->subcategory_image;
            $extension = $subcategory_image->getClientOriginalExtension();
            $file_name = Str::lower(str_replace(' ', '-', $request->subcategory_name)).'-'.$random_number.'.'.$extension;
           
            Image::make($subcategory_image)->save(public_path('uploads/subcategory/'.$file_name));
            Subcategory::insert([
                'subcategory_name'=>$request->subcategory_name,
                'category_id'=>$request->category_id,
                'subcategory_image'=>$file_name,
            ]);
        }        

        return back();
    }

    function subcategory_edit($subcategory_id){
        $categories = Category::all();
        $subcategory_info = Subcategory::find($subcategory_id);
        return view('admin.category.edit_subcategory',[
            'subcategory_info'=>$subcategory_info,
            'categories'=>$categories,
        ]);
    }

    function subcategory_update(Request $request){
        if($request->subcategory_image == ''){
            Subcategory::find($request->subcategory_id)->update([
                'subcategory_name'=>$request->subcategory_name,
                'category_id'=>$request->category_id,
            ]);
            return back();
        }
        else{

            $subcat_image = Subcategory::find($request->subcategory_id);
            if($subcat_image->subcategory_image != null){
                $delete_from = public_path('uploads/subcategory/'.$subcat_image->subcategory_image);
                unlink($delete_from);
            }
        
            $random_number = random_int(100000, 999998);
            $subcategory_image = $request->subcategory_image;
            $extension = $subcategory_image->getClientOriginalExtension();
            $file_name = Str::lower(str_replace(' ', '-', $request->subcategory_name)).'-'.$random_number.'.'.$extension;
           
            Image::make($subcategory_image)->save(public_path('uploads/subcategory/'.$file_name));
            Subcategory::find($request->subcategory_id)->update([
                'subcategory_name'=>$request->subcategory_name,
                'category_id'=>$request->category_id,
                'subcategory_image'=>$file_name,
            ]);

            return back();
        }
    }
}
