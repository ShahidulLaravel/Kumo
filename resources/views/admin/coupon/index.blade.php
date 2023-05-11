@extends('layouts.dashboard')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Coupon</a></li>
    </ol>
  </nav>
  <div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>Coupon List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Coupon</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Expire</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($coupons as $coupon)                        
                    <tr>
                        <td>{{$coupon->coupon_name}}</td>
                        <td>{{$coupon->type==1?'Percentage':'Fixed'}}</td>
                        <td>{{$coupon->amount}}</td>
                        <td>{{ Carbon\Carbon::now()->diffInDays($coupon->expire_date, false); }} days remaining</td>
                        <td>
                            <a href="" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3>Add New Coupon</h3>
            </div>
            <div class="card-body">
                <form action="{{route('coupon.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="coupon_name" class="form-control" placeholder="Coupon Name">
                    </div>
                    <div class="mb-3">
                        <select name="type" class="form-control">
                            <option value="">-Select Type -</option>
                            <option value="1">Percentage</option>
                            <option value="2">Fixed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="amount" class="form-control" placeholder="Amount">
                    </div>
                    <div class="mb-3">
                        <input type="date" name="expire_date" class="form-control" placeholder="expire_date">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
@endsection