<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPasswordResetController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\StripePaymentController;

//frontend
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/product/details/{slug}', [FrontendController::class, 'details'])->name('details');
Route::post('/getSize', [FrontendController::class, 'getSize']);
Route::post('/getQuantity', [FrontendController::class, 'getQuantity']);
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('/search', [SearchController::class, 'search'])->name('search');

//others
Auth::routes();
Route::get('/admin/logout',[HomeController::class, 'logout'])->name('admin.logout');
Route::get('/home', [HomeController::class, 'index'])->name('home');

//Users
Route::get('/users', [UserController::class, 'users'])->name('users');
Route::get('/user/delete/{user_id}', [UserController::class, 'user_delete'])->name('user.delete');
Route::get('/user/edit', [UserController::class, 'user_edit'])->name('user.edit');
Route::post('/user/profile/update', [UserController::class, 'user_profile_update'])->name('update.profile.info');
Route::post('/user/password/update', [UserController::class, 'user_password_update'])->name('update.password');
Route::post('/user/photo/update', [UserController::class, 'user_photo_update'])->name('update.photo');

//Category
Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::post('/category/store', [CategoryController::class, 'category_store'])->name('category.store');
Route::get('/category/delete/{category_id}', [CategoryController::class, 'category_delete'])->name('category.delete');
Route::get('/category/edit/{category_id}', [CategoryController::class, 'category_edit'])->name('category.edit');
Route::post('/category/update', [CategoryController::class, 'category_update'])->name('category.update');
Route::get('/category/restore/{category_id}', [CategoryController::class, 'category_restore'])->name('category.restore');
Route::get('/category/permanent/delete/{category_id}', [CategoryController::class, 'category_del'])->name('category.del');
Route::post('/category/checked/delete', [CategoryController::class, 'category_checked_delete'])->name('check.delete');

//Sub Category
Route::get('/subcategory', [SubCategoryController::class, 'subcategory'])->name('subcategory');
Route::post('/subcategory/store', [SubCategoryController::class, 'subcategory_store'])->name('subcategory.store');
Route::get('/subcategory/edit/{subcategory_id}', [SubCategoryController::class, 'subcategory_edit'])->name('subcategory.edit');
Route::post('/subcategory/update', [SubCategoryController::class, 'subcategory_update'])->name('subcategory.update');

//Brand
Route::get('/brand', [BrandController::class, 'brand'])->name('brand');
Route::post('/brand/store', [BrandController::class, 'brand_store'])->name('brand.store');
Route::post('/brand/store', [BrandController::class, 'brand_store'])->name('brand.store');

//Product
Route::get('/add/product', [ProductController::class, 'add_product'])->name('add.product');
Route::post('/getSubcategory', [ProductController::class, 'getSubcategory']);
Route::post('/product/store', [ProductController::class, 'product_store'])->name('product.store');
Route::get('/product/list', [ProductController::class, 'product_list'])->name('product.list');
Route::get('/product/edit', [ProductController::class, 'product_edit'])->name('product.edit');
Route::post('/product/update', [ProductController::class, 'product_update'])->name('product.update');


//Variation
Route::get('/variation', [InventoryController::class, 'variation'])->name('variation');
Route::post('/variation/store', [InventoryController::class, 'variation_store'])->name('variation.store');

//Inventory
Route::get('/product/inventory/{product_id}', [InventoryController::class, 'product_inventory'])->name('product.inventory');
Route::post('/inventory/store', [InventoryController::class, 'inventory_store'])->name('inventory.store');

//Cart
Route::post('/cart/store', [CartController::class, 'cart_store'])->name('cart.store');
Route::get('/remove/cart/{cart_id}', [CartController::class, 'remove_cart'])->name('remove.cart');
Route::post('/cart/update', [CartController::class, 'cart_update'])->name('cart.update');

//Coupon
Route::get('/coupon', [CouponController::class, 'coupon'])->name('coupon');
Route::post('/coupon/store', [CouponController::class, 'coupon_store'])->name('coupon.store');


//Customer
Route::get('/customer/register/login', [CustomerController::class, 'customer_reg_log'])->name('customer.register.login');
Route::post('/customer/register/store', [CustomerController::class, 'customer_register_store'])->name('customer.register.store');
Route::post('/customer/login', [CustomerController::class, 'customer_login'])->name('customer.login');
Route::get('/customer/logout', [CustomerController::class, 'customer_logout'])->name('customer.logout');
Route::get('/customer/profile', [CustomerController::class, 'customer_profile'])->name('customer.profile');
Route::post('/customer/profile/update', [CustomerController::class, 'customer_profile_update'])->name('customer.update');
Route::get('/myorder', [CustomerController::class, 'myorder'])->name('myorder');

//checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/getCity', [CheckoutController::class, 'getCity']);
Route::post('/order/store', [CheckoutController::class, 'order_store'])->name('order.store');
Route::get('/order/success/{order_id}', [CheckoutController::class, 'order_success'])->name('order.success');

//Order
Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::post('/status/update', [OrderController::class, 'status_update'])->name('status.update');
Route::get('/download/invoice/{order_id}', [OrderController::class, 'download_invoice'])->name('download.invoice');

// SSLCOMMERZ Start
Route::get('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

// Stripe
Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});

//Review
Route::post('/review', [CustomerController::class, 'review_store'])->name('review.store');

//Reset Password
Route::get('/forgot/password', [CustomerPasswordResetController::class, 'forgot_password'])->name('forgot.password');
Route::post('/password/reset/req/send', [CustomerPasswordResetController::class, 'password_reset_req_send'])->name('password.reset.req.send');
Route::get('/password/reset/form/{token}', [CustomerPasswordResetController::class, 'password_reset_form'])->name('pass.reset.form');
Route::post('pass/reset/confirm', [CustomerPasswordResetController::class, 'pass_reset_confirm'])->name('pass.reset.confirm');

//Email Verify
Route::get('customer/email/verify/{token}', [CustomerController::class, 'customer_email_verify'])->name('customer.email.verify');
Route::get('customer/verify/req', [CustomerController::class, 'customer_email_verify_req'])->name('customer.emailverify.req');
Route::post('email/verify/req/send', [CustomerController::class, 'email_verify_req_send'])->name('email.verify.req.send');