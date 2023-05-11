@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Edit Profile</h6>
            </div>
            <div class="card-body">
                <form class="forms-sample" action="{{route('update.profile.info')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputUsername1">Name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputUsername1" autocomplete="off" value="{{Auth::user()->name}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" value="{{Auth::user()->email}}">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Update Password</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                @endif
                <form class="forms-sample" action="{{route('update.password')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputUsername1">Old Password</label>
                        <input type="password" name="old_password" class="form-control" id="exampleInputUsername1" autocomplete="off" >
                        @error('old_password')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                        @if (session('old_wrong'))
                            <strong class="text-danger">{{session('old_wrong')}}</strong>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">New Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputUsername1" autocomplete="off" >
                        @error('password')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="exampleInputUsername1" autocomplete="off" >
                        @error('password_confirmation')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Update Password</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Edit Profile Photo</h6>
            </div>
            <div class="card-body">
                @if (session('success_photo'))
                    <div class="alert alert-success">{{session('success_photo')}}</div>
                @endif
                <form class="forms-sample" action="{{route('update.photo')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Upload Photo</label>
                        <input type="file" name="photo" class="form-control" id="exampleInputEmail1" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        @error('photo')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    <div class="my-2">
                        <img src="" width="200" id="blah" alt="">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Update Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection