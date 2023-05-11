<?php

namespace App\Http\Controllers;

use App\Models\Customerlogin;
use App\Models\CustomerVerify;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Notifications\CustomerEmailVerifyNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use Illuminate\Support\Facades\Notification;

class CustomerController extends Controller
{
    function customer_reg_log(){
        return view('frontend.customer.register_login');
    }
    function customer_register_store(Request $request){
        $request->validate([
            'email'=>'required|unique:customerlogins',
        ]);
        $customer_id = Customerlogin::insertGetId([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'created_at'=>Carbon::now(),
        ]);

        $customer = Customerlogin::find($customer_id);
        $info = CustomerVerify::create([
            'customer_id'=>$customer_id, 
            'token'=>uniqid(), 
            'created_at'=>Carbon::now(), 
        ]);

        Notification::send($customer, new CustomerEmailVerifyNotification($info));
        return back()->with('verify', 'We have sent you a email verification mail to your email please verify');
    }

    function customer_login(Request $request){
        if(Auth::guard('customerlogin')->attempt(['email'=>$request->email, 'password'=>$request->password])){
            if(Auth::guard('customerlogin')->user()->email_verified_at == null){
                Auth::guard('customerlogin')->logout();
                return back()->with('not_verified', 'Please Verify Your Email');
            }
            else{
                return redirect('/');
            }
        }
        else{
            return back()->with('wrong', 'Wrong Credential');
        }
    }
    function customer_logout(){
        Auth::guard('customerlogin')->logout();
        return redirect('/');
    }

    function customer_profile(){
        return view('frontend.customer.profile');
    }
    function customer_profile_update(Request $request){

        //if photo not exist
        if($request->photo == ''){
            if($request->password == ''){
                Customerlogin::find(Auth::guard('customerlogin')->id())->update([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'country'=>$request->country,
                    'address'=>$request->address,
                ]);
                return back();
            }
            else{
                if(Hash::check($request->old_password, Auth::guard('customerlogin')->user()->password)){
                    Customerlogin::find(Auth::guard('customerlogin')->id())->update([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'country'=>$request->country,
                        'address'=>$request->address,
                        'password'=>Hash::make($request->password),
                    ]);
                    return back();
                }
                else{
                    return back()->with('old', 'Current Password Wrong');
                }
            }
        }

        //if photo exist
        else{
            if($request->password == ''){
                $photo = $request->photo;
                $extension = $photo->getClientOriginalExtension();
                $file_name = Auth::guard('customerlogin')->id().'.'.$extension;

                Image::make($photo)->save(public_path('uploads/customer/'.$file_name));
                Customerlogin::find(Auth::guard('customerlogin')->id())->update([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'country'=>$request->country,
                    'address'=>$request->address,
                    'photo'=>$file_name,
                ]);
                return back();
            }
            else{
                if(Hash::check($request->old_password, Auth::guard('customerlogin')->user()->password)){
                    $photo = $request->photo;
                    $extension = $photo->getClientOriginalExtension();
                    $file_name = Auth::guard('customerlogin')->id().'.'.$extension;

                    Image::make($photo)->save(public_path('uploads/customer/'.$file_name));
                    Customerlogin::find(Auth::guard('customerlogin')->id())->update([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'country'=>$request->country,
                        'address'=>$request->address,
                        'password'=>Hash::make($request->password),
                        'photo'=>$file_name,
                    ]);
                    return back();
                }
                else{
                    return back()->with('old', 'Current Password Wrong');
                }
            }
        }
    }
    function myorder(){
        $myorders = Order::where('customer_id', Auth::guard('customerlogin')->id())->orderBy('created_at', 'DESC')->get();
        return view('frontend.customer.myorder', [
            'myorders'=>$myorders,
        ]);
    }

    function review_store(Request $request){
        OrderProduct::where('customer_id', Auth::guard('customerlogin')->id())->where('product_id', $request->product_id)->update([
            'review'=>$request->review,
            'star'=>$request->rating,
        ]);
        return back();
    }

    function customer_email_verify($token){
        $customer = CustomerVerify::where('token', $token)->firstOrFail();
        Customerlogin::find($customer->customer_id)->update([
            'email_verified_at'=>Carbon::now(),
        ]);
        return redirect()->route('customer.register.login')->with('verify_success', 'Your Email Verified Successfully, Now you can login');

    }

    function customer_email_verify_req(){
        return view('frontend.customer.emailverify_req');
    }


    function email_verify_req_send(Request $request){
        if(Customerlogin::where('email', $request->email)->exists()){
            $customer = Customerlogin::where('email', $request->email)->firstOrFail();            
            CustomerVerify::where('customer_id', $customer->id)->delete();
            $info = CustomerVerify::create([
                'customer_id'=>$customer->id,
                'token'=>uniqid(),
                'created_at'=>Carbon::now(),
            ]);
            Notification::send($customer, new CustomerEmailVerifyNotification($info));
            return back()->with('verify', 'We have sent you a email verification mail to your email please verify');
        }
        else{
            return back()->with('register', 'You did not register yet!');
        }
    }
}
