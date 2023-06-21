@extends('layouts.front')
@section('front_title')
Cosmetic Advantages
@endsection
@section('front_css')
@endsection
@section('front_content')
<!-- Home-banner -->
<section class="home-banner-section">
    <div class="home-banner bpartners-banner cosmetics-banner">
        <div class="home-banner-img cosmetics-banner-imgs">
            <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantages-banner.jpg')}}" alt="">
        </div>
        <div class="banner-text-info">
            <h3>Cosmetic Advantages</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum soluta quos ipsum dolore suscipit
                reiciendis fugit quasi error tenetur pariatur!</p>
        </div>
    </div>
</section>

<section class="cosmetics-advantages-section">
    <div class="title-text">
        <h6>What is it</h6>
        <h3>reserved4you</h3>
    </div>
    <div class="container">
        <div class="cosmetics-advantages-info">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                voluptate
                velit esse cillum dolore eu fugiat nulla pariatur.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                voluptate
                velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
                laborum. Aliquet porttitor lacus luctus accumsan tortor
                posuere ac ut consequat. Nunc scelerisque viverra mauris in aliquam sem fringilla ut morbi. Et leo duis
                ut
                diam quam nulla porttitor massa.
                Etiam sit amet nisl purus in mollis nunc. </p>
            <ul>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/saloon.svg")); ?></span>
                        <h6>4 areas
                            combined</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/planner.svg")); ?></span>
                        <h6>Save your favorites in
                            your profile</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/test.svg")); ?></span>
                        <h6>Always and everywhere!
                            mobile version for all
                            your devices</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/calendar.svg")); ?></span>
                        <h6>Manage all your
                            appointments in the
                            individual calendar</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/review.svg")); ?></span>
                        <h6>Give feedback and
                            look at reviews from
                            others</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/reminder.svg")); ?></span>
                        <h6>Don't miss an
                            appointment with the
                            reminder functions!</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/discount.svg")); ?></span>
                        <h6>Always be up to date
                            with our newsletter</h6>
                    </div>
                </li>
                <li>
                    <div class="cosmetics-advantages-box">
                        <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/start-up.svg")); ?></span>
                        <h6>#staytuned through
                            our social media
                            channels</h6>
                    </div>
                </li>
            </ul>
            <div class="service-advantages-owl">
                <div class="item wow slideInLeft">
                    <div class="service-item-main">
                        <div class="service-item">
                            <span class="gray-effect">
                                <img class="gray-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-1.png')}}" alt="">
                                <img class="color-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-1.png')}}" alt="">
                            </span>
                            <!-- <h6>Catering</h6> -->
                        </div>
                    </div>
                </div>
                <div class="item wow slideInLeft">
                    <div class="service-item-main active">
                        <div class="service-item">
                            <span class="">
                                <img class="gray-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-2.png')}}" alt="">
                                <img class="color-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/service-2.png')}}" alt="">
                            </span>
                            <h6>Cosmetics</h6>
                        </div>
                    </div>
                </div>
                <div class="item wow slideInRight">
                    <div class="service-item-main">
                        <div class="service-item">
                            <span class="gray-effect">
                                <img class="gray-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-3.png')}}" alt="">
                                <img class="color-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-3.png')}}" alt="">
                            </span>
                            <!-- <h6>Health</h6> -->
                        </div>
                    </div>
                </div>
                <div class="item wow slideInRight">
                    <div class="service-item-main">
                        <div class="service-item">
                            <span class="gray-effect">
                                <img class="gray-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-4.png')}}" alt="">
                                <img class="color-img"
                                    src="{{URL::to('storage/app/public/Frontassets/images/g-service-4.png')}}" alt="">
                            </span>
                            <!-- <h6>Law And Advice</h6> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="c-advantage-info-secton how-it-work-section pt-0">

    <div class="container">
        <div class="row how-it-row">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="how-it-work-img wow slideInLeft">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-info-1.png')}}"
                        alt="">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="how-it-work-info wow slideInRight">
                    <h5>Employee and service selection</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam,</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
        </div>
        <div class="row how-it-row">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="how-it-work-info wow slideInLeft">
                    <h5>Appointment Booking</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam,</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 order">
                <div class="how-it-work-img text-right wow slideInRight">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-info-2.png')}}"
                        alt="">
                </div>
            </div>
        </div>
        <div class="row how-it-row">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="how-it-work-img wow slideInLeft">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-info-3.png')}}"
                        alt="">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="how-it-work-info wow slideInRight">
                    <h5>Cetering Service</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam,</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="register-cosmetic-section ">
    <div class="container">
        <div class="register-cosmetic-areas">
            <span class="register-cosmetic-lines"><img
                    src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
            <span class="register-cosmetic-lines2"><img
                    src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
            <div class="register-cosmetic-areas-info">
                @if(!Auth::check())
                <h6>Register here to be able to use all the advantages</h6>

                <a href="javascript:void(0)" class="btn btn-white" data-toggle="modal"
                    data-target="#register-modal">Register Now</a>

                @endif
            </div>
        </div>
    </div>
</section>

@endsection
@section('front_js')
<script>
$("body").addClass("cosmetics-body");
$("body").addClass("cosmetics-area-body");
</script>
@endsection
