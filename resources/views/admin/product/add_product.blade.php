@extends('layouts.dashboard')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Add New Product</a></li>
    </ol>
</nav>

<div>
    <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3>Add New Product</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Price</label>
                            <input type="number" class="form-control" name="price">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Discount</label>
                            <input type="number" class="form-control" name="discount">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Select Any --</option>
                                @foreach ($categories as $category)                                    
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                @endforeach◘
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Select Sub Category</label>
                            <select name="subcategory_id" id="subcategory" class="form-control">
                                <option value="">-- Select Any --</option>
                                @foreach ($subcategories as $subcategory)                                    
                                    <option value="{{$subcategory->id}}">{{$subcategory->subcategory_name}}</option>
                                @endforeach◘
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Brand</label>
                            <select name="brand" class="form-control">
                                <option value="">-- Select Any --</option>
                                @foreach ($brands as $brand)                                    
                                    <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach◘
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="" class="form-label">Short Description</label>
                            <input type="text" class="form-control" name="short_desp">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Long Description</label>
                            <textarea id="summernote" name="long_desp"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Aditional Information</label>
                            <textarea id="summernote2" name="additional_info"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Preview</label>
                            <input type="file" class="form-control" name="preview">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Product Gallery</label>
                            <input type="file" multiple class="form-control" name="gallery[]">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-5 m-auto">
                        <div class="mb-5 mt-5">
                            <button type="submit" class="btn btn-primary form-control">Add Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer_script')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
        $('#summernote2').summernote();
    });
</script>

<script>
    $('#category_id').change(function(){
        var category_id = $(this).val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url:'/getSubcategory',
            data:{'category_id':category_id},
            success:function(data){
                $('#subcategory').html(data);
            }
        });

    });
</script>
@endsection