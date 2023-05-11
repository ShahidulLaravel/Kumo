@extends('layouts.dashboard')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Product list</a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3>Product List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>After Discount</th>
                        <th>Preview</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($all_products as $product)                        
                    <tr>
                        <td>{{$product->product_name}}</td>
                        <td>&#2547; {{$product->price}}</td>
                        <td>{{$product->discount==null?'0':$product->discount}}%</td>
                        <td>&#2547; {{$product->after_discount}}</td>
                        <td><img src="{{asset('uploads/product/preview')}}/{{$product->preview}}" alt=""></td>
                        <td>
                            <a href="{{route('product.inventory', $product->id)}}" class="btn btn-success btn-icon">
                                <i data-feather="layers"></i>
                            </a>
                            <form action="{{route('product.edit')}}" class="d-inline" method="GET">
                                @csrf
                                <button name="product_id" value="{{$product->id}}" class="btn btn-primary btn-icon">
                                    <i data-feather="edit"></i>
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-danger btn-icon">
                                <i data-feather="trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection