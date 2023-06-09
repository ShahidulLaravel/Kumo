@extends('frontend.master')
@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Email Verify Request</h3>
                </div>
                <div class="card-body">
                    @if (session('invalid'))
                        <div class="alert alert-danger">{{session('invalid')}}</div>
                    @endif
                    @if (session('verify'))
                        <div class="alert alert-danger">{{session('verify')}}</div>
                    @endif
                    <form action="{{route('email.verify.req.send')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">Your Email Address</label>
                            <input type="text" name="email" class="form-control">
                            @error('email')                                
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection