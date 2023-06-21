@extends('layouts.front')
@section('front_title')
Become Partner
@endsection
@section('front_css')
@endsection
@section('front_content')

<!-- Home-banner -->
<section class="home-banner-section">
    <div class="home-banner bpartners-banner cosmetics-banner">
        <div class="home-banner-img cosmetics-banner-imgs">
            <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-bpartners-banner.jpg')}}" alt="">
        </div>
        <div class="banner-text-info">
            <h3>Become Partner</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum soluta quos ipsum dolore suscipit
                reiciendis fugit quasi error tenetur pariatur!</p>
        </div>
    </div>
</section>

<!-- Our Advantage -->
<section class="our-advantage-section">
    <div class="container">

        <div class="service-advantages-owl">
            <div class="item wow slideInLeft">
                <div  class="service-item-main">
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
                <div onclick="location.reload();location.href='{{URL::to("cosmetic-area")}}'" class="service-item-main active">
                    <div class="service-item">
                        <span>
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
                <div  class="service-item-main">
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

        <div class="title-text mt-5">
            <h6>Our</h6>
            <h3>Advantage</h3>
        </div>

        <div class="our-advantage-owl2">
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/pay.svg')); ?>
                    </div>
                    <p>No payment per
                        customer or reservation</p>
                </div>
            </div>
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/calendar.svg")); ?>
                    </div>
                    <p>Monthly fixed costs</p>
                </div>
            </div>
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/wallet.svg")); ?>
                    </div>
                    <p>Higher turnover through
                        us</p>
                </div>
            </div>
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/flexible.svg")); ?>
                    </div>
                    <p>Can be canceled
                        monthly (flexible terms)</p>
                </div>
            </div>
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/planner.svg")); ?>
                    </div>
                    <p>More efficiency in
                        planning and time
                        management</p>
                </div>
            </div>
            <div class="item">
                <div class="our-advantage-item">
                    <div class="our-advantage-img">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/discount.svg")); ?>
                    </div>
                    <p>Large reach thanks to
                        an innovative advertising
                        concept.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Our Advantage 2 -->
<section class="how-it-work-section our-advantage2-section">
    <div class="container">
        <div class="row how-it-row how-it-row2">
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInLeft">
                <div class="how-it-work-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-img-1.png')}}"
                        alt="">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInRight">
                <div class="how-it-work-info">
                    <h5>Listing and Rating</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
                        consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
        </div>
        <div class="row how-it-row how-it-row2">
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInRight">
                <div class="how-it-work-info">
                    <h5>Order and Payment</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
                        consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 order wow slideInLeft">
                <div class="how-it-work-img text-right">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-img-2.png')}}"
                        alt="">
                </div>
            </div>
        </div>
        <div class="row how-it-row how-it-row2">
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInLeft">
                <div class="how-it-work-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-img-3.png')}}"
                        alt="">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInRight">
                <div class="how-it-work-info">
                    <h5>Reservation and Delivery</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
                        consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
        </div>
        <div class="row how-it-row how-it-row2">
            <div class="col-xl-6 col-lg-6 col-md-6 wow slideInRight">
                <div class="how-it-work-info">
                    <h5>Marketing and extra service</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
                        consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <a href="javascript:void(0)">Read More...</a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 order wow slideInLeft">
                <div class="how-it-work-img text-right">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantage-img-4.png')}}"
                        alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Offers -->
<section class="our-offers">
    <div class="container">
        <div class="title-text">
            <h6>Our</h6>
            <h3>Offers</h3>
        </div>
        <div class="row justify-content-center offers-row">
            <div class="col-xl-4 col-lg-4 col-md-6">
                <a href="javascript:void(0)" class="offers-item">
                    <div class="offer-header">
                        <span><img src="{{URL::to('storage/app/public/Frontassets/images/c-free.svg')}}" alt=""></span>
                        <h5>BASIC</h5>
                        <h6>Auflistung + Profilgestaltung</h6>
                    </div>
                    <!-- <div class="offer-wrap">
                        <div>
                            <span>12 Monate</span>
                            <h6>29,99 €/ Monat</h6>
                        </div>
                        <div>
                            <span>1 Monate</span>
                            <h6>39,99 €/ Monat</h6>
                        </div>
                    </div> -->
                    <i class="offer-wrap-i">+50 € Grundgebiihr (einmalig). zzgl. MwSt.</i>
                    <ul>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/staff.svg')); ?></span>Auflistung
                            auf
                            reserved4you inkl. Profilgestaltung
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/devices.svg')); ?></span>Optimiert
                            fur
                            mobile
                            Endgerate
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/support.svg')); ?></span>Support
                            24/7
                            -
                            Live
                            Chat, E-Mail
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/news.svg')); ?></span>Individueller
                            Newsletter
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/bullhorn.svg')); ?></span>Werbung
                            auf
                            reserved4you + Social Media
                        </li>
                    </ul>
                </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <a href="javascript:void(0)" class="offers-item">
                    <div class="offer-header">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/c-add.svg')); ?></span>

                        <h5>BASIC PLUS</h5>
                        <h6>Auflistung + Reservierungssystem </h6>
                    </div>
                    <!-- <div class="offer-wrap">
                        <div>
                            <span>12 Monate</span>
                            <h6>29,99 €/ Monat</h6>
                        </div>
                        <div>
                            <span>1 Monate</span>
                            <h6>39,99 €/ Monat</h6>
                        </div>
                    </div> -->
                    <i class="offer-wrap-i">+50 € Grundgebiihr (einmalig). zzgl. MwSt.</i>
                    <ul>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/staff.svg')); ?></span>Auflistung
                            auf
                            reserved4you inkl. Profilgestaltung
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/devices.svg')); ?></span>Optimiert
                            fur
                            mobile
                            Endgerate
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/dedication.svg')); ?></span>Management-Tools
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/reception.svg')); ?></span>Reservierungssystem
                            + kostenlose Schulung
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/support.svg')); ?></span>Support
                            24/7
                            -
                            Live
                            Chat, E-Mail
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/news.svg')); ?></span>Individueller
                            Newsletter
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/bullhorn.svg')); ?></span>Werbung
                            auf
                            reserved4you + Social Media
                        </li>
                    </ul>
                </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 offers-item-active">
                <a href="javascript:void(0)" class="offers-item" >
                    <div class="offer-header">

                        <span><img src="{{URL::to('storage/app/public/Frontassets/images/c-job.svg')}}" alt=""></span>
                        <h5>BUSINESSSS</h5>
                        <h6>Auflistung + reserved4you</h6>
                    </div>
                    <!-- <div class="offer-red-btn offer-wrap">
                        <h3>Free</h3>
                    </div> -->
                    <i class="offer-wrap-i">+50 € Grundgebiihr (einmalig). zzgl. MwSt.</i>
                    <ul>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/staff.svg')); ?></span>Auflistung
                            auf
                            reserved4you inkl. Profilgestaltung
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/devices.svg')); ?></span>Optimiert
                            fur
                            mobile
                            Endgerate
                        </li>
                        <li><span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/support.svg')); ?></span>Support
                            24/7
                            -
                            Live
                            Chat, E-Mail
                        </li>
                    </ul>
                </a>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="javascript:void(0)" class="btn btn-red" data-toggle="modal"
                    data-target="#new-appointment-modal">Become Partner</a>
        </div>
    </div>
</section>

<!-- New-Appointment -->
<div class="modal fade" id="new-appointment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-radius">
            <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-body modal-bg">
                <form class="appointment-form">
                    <h5>New Appointment</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" placeholder="First name">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" placeholder="Last name">
                        </div>
                        <div class="col-lg-12">
                            <input type="email" placeholder="Email">
                        </div>
                        <div class="col-lg-12">
                            <input type="text" placeholder="Phone no.">
                        </div>
                        <div class="col-lg-12">
                            <input type="text" placeholder="Store name">
                        </div>
                        <div class="col-lg-12">
                            <div class="location-input">
                                <input type="text" placeholder="Location">
                                <!-- <a href="javascript:void(0)"><i class="fas fa-location"></i></a> -->
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3 mb-2">
                        <a href="javascript:void(0)" class="btn btn-red btn-appointment-modal">Save</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('front_js')
<script>
$("body").addClass("cosmetics-body");
</script>
@endsection
