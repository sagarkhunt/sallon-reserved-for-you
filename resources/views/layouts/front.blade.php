<!doctype html>
<html dir="ltr" lang="en-US">

<head>
    <title>Reserved4you</title>
    <link type="image/x-icon" rel="shortcut icon" href="{{URL::to('storage/app/public/Frontassets/images/favicon.jpg')}}" />
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/font/stylesheet.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/all.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/jquery.fancybox.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/owl.carousel.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/nice-select.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/bootstrap-datepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/styles.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/responsive.css')}}" />

    @yield('front_css')
</head>

<body>

    <?php
    function chk_active($p)
    {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (strpos($actual_link, $p) !== false) {
            return true;
        } else {
            return false;
        }
    }
    ?>
    @include('Includes.Front.header')

    @yield('front_content')

    @include('Includes.Front.footer')
    <a onclick="topFunction()" id="myBtn" title="Go to top" style="display: block;"><i class="fas fa-arrow-up"></i></a>
    <!-- Optional JavaScript -->
    <script src="{{URL::to('storage/app/public/Frontassets/js/jquery.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/jquery.fancybox.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/owl.carousel.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Frontassets/js/custom.js')}}"></script>
    </body>

    <script>
    (function() {
        const burger = document.querySelector('.burger');
        burger.addEventListener('click', () => {
            burger.classList.toggle('burger_active');
        });
    }());
    </script>
    <script>
        $("body").addClass("footer-show");
        $('#index-filter-owl').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:3
            }
        }
        });
    $(".service-item-icon").click(function() {
        $('.service-item-icon').removeClass("active");
        $(this).addClass("active");
    });
    $('#digital-main-owl').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots:false,
        responsive:{
            0:{
                items:2
            },
            480:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })
    // service-item-owl //
    $('#service-item-owl2').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots:false,
        navText: ["<i class='fas fa-chevron-left'></i>", "<i class='fas fa-chevron-right'></i>"],
        responsive: {
            0: {
                items: 2
            },
            430: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
</script>
    @yield('front_js')
</body>

</html>
