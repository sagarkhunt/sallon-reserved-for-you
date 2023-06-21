<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Reserved4you</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Reserve conveniently and quickly - that's the new trend. How does our system work? It's simple: we create connections. We combine reservation, overview lists and delivery in four different areas. With that you are prepared for the future." name="description" />
    <meta content="Decodes Studio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{URL::to('storage/app/public/Adminassets/images/favicon.png')}}">

    <!-- App css -->
    <link href="{{URL::to('storage/app/public/Adminassets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('storage/app/public/Adminassets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('storage/app/public/Adminassets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .invalid-feedback{
            display: block;
        }
    </style>
</head>

<body class="authentication-bg">

<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-6 p-5">
                                <div class="mx-auto mb-5">
                                    <a href="{{URL::to('master-admin')}}">
                                        <img src="{{URL::to('storage/app/public/Adminassets/images/log.png')}}" alt="" height="50" />

                                    </a>
                                </div>

                                <h6 class="h5 mb-0 mt-4">Welcome back!</h6>
                                <p class="text-muted mt-1 mb-4">Enter your email address and password to
                                    access admin panel.</p>

                                {!! Form::open(array('url'=>'master-admin/login','method'=>'post','name'=>'login','class'=>'authentication-form')) !!}
                                    <div class="form-group">
                                        <label class="form-control-label">Email Address</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="icon-dual" data-feather="mail"></i>
                                                        </span>
                                            </div>

                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="hello@reserved4you.de" required >
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <label class="form-control-label">Password</label>

                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="icon-dual" data-feather="lock"></i>
                                                        </span>
                                            </div>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your Password" required>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Log In
                                        </button>
                                    </div>
                                {!! Form::close() !!}

                            </div>
                            <div class="col-lg-6 d-none d-md-inline-block">
                                <div class="auth-page-sidebar">
                                    <div class="overlay"></div>
                                    <div class="auth-user-testimonial">
                                        <p class="font-size-24 font-weight-bold text-white mb-1">I simply love it!</p>
                                        <p class="lead">"It's a elegent templete. I love it very much!"</p>
                                        <p>- Reserved4you</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->



            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<!-- Vendor js -->
<script src="{{URL::to('storage/app/public/Adminassets/js/vendor.min.js')}}"></script>

<!-- App js -->
<script src="{{URL::to('storage/app/public/Adminassets/js/app.min.js')}}"></script>

</body>

</html>
