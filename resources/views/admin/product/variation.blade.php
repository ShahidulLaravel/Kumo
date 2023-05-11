@extends('layouts.dashboard')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Product Variation</a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h3>Color List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Color Name</th>
                        <th>Color</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($colors as $color)                        
                    <tr>
                        <td>{{$color->color_name}}</td>
                        <td><span class="badge" style="background:{{$color->color_code}}; color:transparent;">Primary</span></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-icon">
                                <i data-feather="trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row mt-3">
            @foreach ($categories as $category)
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h3>{{$category->category_name}}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Size Name</th>
                                <th>Action</th>
                            </tr>
                            @foreach (App\Models\Size::where('category_id', $category->id)->get() as $size)                        
                            <tr>
                                <td>{{$size->size_name}}</td>
                                <td>
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
            @endforeach
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h3>Add New Color</h3>
            </div>
            <div class="card-body">
                <form action="{{route('variation.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Color Name</label>
                        <input type="text" name="color_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Color Code</label>
                        <input type="text" name="color_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button name="btn" value="1" type="submit" class="btn btn-primary">Add Color</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-header">
                <h3>Add New Size</h3>
            </div>
            <div class="card-body">
                <form action="{{route('variation.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Select Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($categories as $category)                                
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach◘
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Size Name</label>
                        <input type="text" name="size_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button name="btn" value="2" type="submit" class="btn btn-primary">Add Size</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection