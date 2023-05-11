@extends('layouts.dashboard')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Brand</a></li>
    </ol>
  </nav>
  <div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>Brand List <span class="float-right"></span></h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>SL</th>
                        <th>Brand Name</th>
                        <th>Logo</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($brands as $sl=>$brand)    
                    <tr>
                        <td>{{$sl+1}}</td>
                        <td>{{$brand->brand_name}}</td>
                        <td>
                            @if ($brand->brand_logo == null)
                                <img src="{{ Avatar::create($brand->brand_name)->toBase64() }}" />
                            @else
                                <img src="{{asset('uploads/brand')}}/{{$brand->brand_logo}}" alt="profile">
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>

                {{$brands->links()}}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Add New Brand</h6>
                <form class="forms-sample" action="{{route('brand.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputUsername1">Brand Name</label>
                        <input type="text" name="brand_name" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Brand Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Brand Logo</label>
                        <input type="file" name="brand_logo" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Brand Logo">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                </form>
            </div>
        </div>  
    </div>
  </div>
@endsection