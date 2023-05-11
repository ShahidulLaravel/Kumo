<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPassUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Ui\Presets\React;
use Image;

class UserController extends Controller
{
    function users(){
        $users = User::where('id', '!=', Auth::id())->get();
        $total_user = User::count();
        return view('admin.users.users', compact('users', 'total_user'));
    }

    function user_delete($user_id){
        User::find($user_id)->delete();
        return back()->with('success', 'User Deleted!');
    }

    function user_edit(){
        return view('admin.users.edit_profile');
    }

    
    function user_profile_update(Request $request){
        User::find(Auth::id())->update([
            'name'=>$request->name,
            'email'=>$request->email,
        ]);

        return back();
    }

    function user_password_update(UserPassUpdate $request){
        if(Hash::check($request->old_password, Auth::user()->password)){
            User::find(Auth::id())->update([
                'password'=>bcrypt($request->password),
            ]);
            return back()->with('success', 'ja dilam password update koira');
        }
        else{
           return back()->with('old_wrong', 'Kire Chora, Ulta palta password des ken?');
        }
    }

    function user_photo_update(Request $request){
        $request->validate([
            'photo'=>['required', 'mimes:jpg,gif,png'],
            'photo'=>'file|max:512',
        ]);

        $prev_photo = public_path('uploads/user/'.Auth::user()->photo);
        unlink($prev_photo); 

        $uploaded_photo = $request->photo;
        $extension = $uploaded_photo->getClientOriginalExtension();
        $file_name = Auth::id().'.'.$extension;
        
        Image::make($uploaded_photo)->save(public_path('uploads/user/'.$file_name));

        User::find(Auth::id())->update([
            'photo'=>$file_name,
        ]);

        return back()->with('success_photo', 'ai ne tor photo update kore disi');

    }
    
}
