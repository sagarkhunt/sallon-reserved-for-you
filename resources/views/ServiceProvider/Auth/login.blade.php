<!doctype html>
<html dir="ltr" lang="en-US">

<head>
    <title>reserved4you</title>
    <link type="image/x-icon" rel="shortcut icon"
        href="{{URL::to('storage/app/public/Frontassets/images/favicon.jpg')}}" />
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/font/stylesheet.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/all.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet"
        href="{{URL::to('storage/app/public/Frontassets/css/service-provider.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/responsive.css')}}" />
    <style>
    .invalid-feedback {
        display: block;
    }
    </style>
</head>

<body>

    <section class="login-screen p-0">
        <!-- <div class="col-lg-6">
        <div class="login-screen-img">
            <img src="{{URL::to('storage/app/public/Frontassets/images/service-provider/login-bg.jpg')}}" alt="">
        </div>
    </div> -->
        <div class="login-video">
            <!-- <video src="{{URL::to('storage/app/public/Frontassets/images/service-provider/login-bg.mp4')}}" autoplay
                loop>
                <source src=""
                    type="video/mp4">
                <source src="{{URL::to('storage/app/public/Frontassets/images/service-provider/login-bg.ogg')}}"
                    type="video/ogg">
            </video> -->
            <video autoplay muted loop>
                <source src="{{URL::to('storage/app/public/Frontassets/images/service-provider/login-bg.mp4')}}"
                    type="video/mp4">
                Your browser does not support HTML5 video.
            </video>
        </div>
        <div class="login-screen-info">
            <a href="{{URL::to('/')}}" class="login-logo"><img
                    src="{{URL::to('storage/app/public/Frontassets/images/logo.png')}}" alt=""></a>

            {!!
            Form::open(array('url'=>'service-provider/login','method'=>'post','name'=>'login','class'=>'authentication-form'))
            !!}
            <div class="white-box login-box">


                <div class="login-box-input">
                    <label for="mail">
                        <span>
                            <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/mail.svg')); ?>
                        </span> Email Address</label>
                    <input type="email" id="mail" placeholder="Enter Your Email"
                        class="@error('email') is-invalid @enderror" name="email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="login-box-input">
                    <label for="password">
                        <span>
                            <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/password.svg')); ?>
                        </span>
                        Password</label>
                    <input type="password" id="password" placeholder="Enter Your Password"
                        class="@error('password') is-invalid @enderror" name="password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>
            <a href="javascript:void(0)" class="forgot-link">Forgot Password?</a>
            <button class="btn btn-block btn-black btn-black-white" type="submit" name="login">Login</button>
            {!! Form::close() !!}
        </div>
    </section>

    <!-- Optional JavaScript -->

    <script src="{{URL::to('storage/app/public/Frontassets/js/jquery.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/bootstrap.bundle.min.js')}}"></script>

</body>

</html>