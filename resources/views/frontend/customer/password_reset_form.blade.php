@extends('frontend.master')
@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Password Reset</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                    <form action="{{route('pass.reset.confirm')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">New Password</label>
                            <input type="hidden" name="token" class="form-control" value="{{$token}}">
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection