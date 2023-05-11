@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Subcategory List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Subcategory</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($subcategories as $sl=>$subcategory)                            
                        <tr>
                            <td>{{$sl+1}}</td>
                            <td>{{$subcategory->subcategory_name}}</td>
                            <td>{{$subcategory->rel_to_category->category_name}}</td>
                            {{-- <td>{{$subcategory->category_id == ''?'not Assigned':$subcategory->rel_to_category->category_name}}</td> --}}
                            <td><img width="100" src="{{asset('uploads/subcategory')}}/{{$subcategory->subcategory_image}}" alt=""></td>
                            <td>
                                <a href="{{route('subcategory.edit', $subcategory->id)}}" class="btn btn-info">Edit</a>
                                <a href="" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="5">No data Found</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3>Add New Subcategory</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('subcategory.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Subcategory Name</label>
                            <input type="text" name="subcategory_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Select Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">--Select Category--</option> 
                                @foreach ($categories as $category)                                    
                                    <option  value="{{$category->id}}">{{$category->category_name}}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">SubCategory Image</label>
                            <input type="file" name="subcategory_image" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="SubCategory Image">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Add Subcategory</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-5">
        @foreach ($categories as $category)
            <div class="col-lg-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h5>{{$category->category_name}}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Subcategory_name</th>
                                <th>Subcategory Image</th>
                                <th>Action</th>
                            </tr>
                            @foreach (App\Models\Subcategory::where('category_id', $category->id)->get() as $subcategory)                                        
                            <tr>
                                <td>{{$subcategory->subcategory_name}}</td>
                                <td><img width="100" src="{{asset('uploads/subcategory')}}/{{$subcategory->subcategory_image}}" alt=""></td>
                                <td>
                                    <a href="{{route('subcategory.edit', $subcategory->id)}}" class="btn btn-info">Edit</a>
                                    <a href="" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
    </div>
@endsection