<?php

namespace App\Http\Controllers;

use App\Mail\CustomerInvocieMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    function checkout(){
        $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
        $countries = Country::all();
        return view('frontend.checkout', [
            'countries'=>$countries,
            'carts'=>$carts,
        ]);
    }

    function getCity(Request $request){
        $str = '<option value="">-- Select City --</option>';
        $cities = City::where('country_id', $request->country_id)->get();
        foreach($cities as $city){
            $str .= '<option value="'.$city->id.'">'.$city->name.'</option>';
        }
        echo $str;
    }
    function order_store(Request $request){
        $random_number2 = random_int(1000000, 9999999);
        $city = City::find($request->city_id);
        $order_id = '#'.Str::upper(substr($city->name, 0,3)).'-'.$random_number2;
        if($request->payment_method == 1){
            Order::insert([
                'order_id'=>$order_id,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'subtotal'=>$request->sub_total,
                'total'=>$request->sub_total+$request->charge - ($request->discount),
                'charge'=>$request->charge,
                'discount'=>$request->discount,
                'payment_method'=>$request->payment_method,
                'created_at'=>Carbon::now(),
            ]);
    
            BillingDetails::insert([
                'order_id'=>$order_id,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'name'=>Auth::guard('customerlogin')->user()->name,
                'email'=>Auth::guard('customerlogin')->user()->email,
                'mobile'=>$request->billing_mobile,
                'company'=>$request->company,
                'address'=>Auth::guard('customerlogin')->user()->address,
                'created_at'=>Carbon::now(),
            ]);
    
            ShippingDetails::insert([
                'order_id'=>$order_id,
                'name'=>$request->name,
                'email'=>$request->email,
                'mobile'=>$request->mobile,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'address'=>$request->address,
                'zip'=>$request->zip,
                'notes'=>$request->notes,
                'created_at'=>Carbon::now(),
            ]);
    
            $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
    
            foreach($carts as $cart){
                OrderProduct::insert([
                    'order_id'=>$order_id,
                    'customer_id'=>Auth::guard('customerlogin')->id(),
                    'product_id'=>$cart->product_id,
                    'price'=>$cart->rel_to_product->after_discount,
                    'color_id'=>$cart->color_id,
                    'size_id'=>$cart->size_id,
                    'quantity'=>$cart->quantity,
                    'created_at'=>Carbon::now(),
                ]);
    
                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
    
                // Cart::find($cart->id)->delete();
            }
            $mail = Auth::guard('customerlogin')->user()->email;
            Mail::to($mail)->send(new CustomerInvocieMail($order_id));
    
            //sms
            $total= $request->sub_total+$request->charge - ($request->discount);
            // $url = "http://bulksmsbd.net/api/smsapi";
            // $api_key = "wzm9wcY9M3IHYvlY65SZ";
            // $senderid = "alamin123";
            // $number = $request->billing_mobile;
            // $message = "Congratulations! Your order has been placed! Please ready TK ".$total;
            
            // $data = [
            //     "api_key" => $api_key,
            //     "senderid" => $senderid,
            //     "number" => $number,
            //     "message" => $message
            // ];
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // $response = curl_exec($ch);
            // curl_close($ch);
            // $url = "http://66.45.237.70/api.php";
            // $number=$request->billing_mobile;
            // $text="Congratulations! Your order has been placed! Please ready TK ".$total;
            // $data= array(
            // 'username'=>"01834833973",
            // 'password'=>"TE47RSDM",
            // 'number'=>"$number",
            // 'message'=>"$text"
            // );
            
            // $ch = curl_init(); // Initialize cURL
            // curl_setopt($ch, CURLOPT_URL,$url);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $smsresult = curl_exec($ch);
            // $p = explode("|",$smsresult);
            // $sendstatus = $p[0];
    
            $order_id_new = substr($order_id,1);
            return redirect()->route('order.success', $order_id_new)->withOrdersuccess('Cart Added!');
        }
        elseif($request->payment_method == 2){
            $data = $request->all();
            return redirect('/pay')->with('data', $data);
        }
        else{
            $data = $request->all();
            return redirect('/stripe')->with('data', $data);
        }
    }
    function order_success($order_id){
        if(session('ordersuccess')){
            return view('frontend.order_success', compact('order_id'));
        }
        else{
            abort('404');
        }
        
    }
}
