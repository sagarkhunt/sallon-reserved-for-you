<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>@yield('title') - Reserved4you</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Reserve conveniently and quickly - that's the new trend. How does our system work? It's simple: we create connections. We combine reservation, overview lists and delivery in four different areas. With that you are prepared for the future." name="description"/>
    <meta content="Decodes Studio" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{URL::to('storage/app/public/Adminassets/images/favicon.png')}}">

    <!-- plugins -->
    <link href="{{URL::to('storage/app/public/Adminassets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- App css -->
    <link href="{{URL::to('storage/app/public/Adminassets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('storage/app/public/Adminassets/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('storage/app/public/Adminassets/css/app.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .invalid-feedback{
            display: block;
        }
    </style>
    @yield('css')
</head>

<body>

<!-- Pre-loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
        </div>
    </div>
</div>
<!-- End Preloader-->

<!-- Begin page -->
<div id="wrapper">

@include('Includes.Admin.header')

<!-- ========== Left Sidebar Start ========== -->
@include('Includes.Admin.sidebar')

<!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div> <!-- content -->


        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        2020 &copy; Delemont Studio. All Rights Reserved. Crafted with <i
                            class='uil uil-heart text-danger font-size-12'></i> by <a href="https://www.delemont.com/"
                                                                                      target="_blank">Delemont Studio</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="{{URL::to('storage/app/public/Adminassets/js/vendor.min.js')}}"></script>

<!-- optional plugins -->
@yield('plugin')

<!-- App js -->
<script src="{{URL::to('storage/app/public/Adminassets/js/app.min.js')}}"></script>
@yield('js')

</body>

</html>
