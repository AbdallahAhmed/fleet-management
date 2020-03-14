@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">

                <div class="col-md-6">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! implode(' <br /> ', $errors->all()) !!}
                        </div>
                    @endif
                    <div class="login-box">
                        <div class="login-logo">
                            <a href="#">Login</a>
                        </div>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group has-feedback">
                                <input type="text"
                                       class="form-control"
                                       name="name" value="{{ old('name') }}" required placeholder="Name">
                            </div>
                            <div class="form-group has-feedback">
                                <input type="email"
                                       class="form-control"
                                       name="email" value="{{ old('email') }}" required placeholder="Email">
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password"
                                       class="form-control"
                                       name="password" required placeholder="Password">
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <button type="submit"  class="btn btn-primary btn-block btn-flat">Sign up</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="login" class="btn btn-primary btn-block btn-flat">Login</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#login').on('click',function () {
            window.location = '{{route('login')}}';
        })
    </script>
@endpush
