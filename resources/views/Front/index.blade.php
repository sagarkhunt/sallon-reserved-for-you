@extends('layouts.front')
@section('front_title')
    Home
@endsection
@section('front_css')
@endsection
@section('front_content')
    <!-- Home-banner -->
    <section class="home-banner-section">
        <div class="home-banner">
            <div class="home-banner-img">
                <img src="{{URL::to('storage/app/public/Frontassets/images/home-banner.jpg')}}" alt="">
            </div>
            <div class="home-banner-info">
            <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z" />
                </svg></span>
                <input type="text" placeholder="What are you looking for ?">
                <a href="javascript:void(0)" class="btn btn-red">Search</a>
            </div>
        </div>
        <span class="home-banner-dish"><img src="{{URL::to('storage/app/public/Frontassets/images/dish-3.png')}}" alt=""></span>
    </section>

    <!-- Our Services -->
    <section class="service-section">
        <div class="container">
            <div class="title-text">
                <h6>Our</h6>
                <h3>Services</h3>
            </div>
            <div class="owl-carousel owl-theme" id="service-owl">
                <div class="item">
                    <div class="service-item-main active">
                        <div class="service-item">
                            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-1.png')}}" alt=""></span>
                            <h6 onclick="location.reload();location.href='{{URL::to('/')}}'">Catering</h6>
                        </div>
                        <div class="service-item-info">
                            <ul>
                                <li>
                                    <a href="javascript:void(0)">
                                    <span>
                                        <svg id="Layer_1" enable-background="new 0 0 512 512" viewBox="0 0 512 512"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="m509.184 109.04c-1.899-1.96-4.45-3.04-7.18-3.04-13.21 0-25.84 4.93-35.561 13.88-9.71 8.95-15.659 21.13-16.76 34.3-1.43 17.29-4.08 42.48-8.58 64.22-.55 2.67-.83 5.37-.83 8.04 0 5.26 1.04 10.38 3.09 15.23.63 1.494 1.354 2.935 2.153 4.33h-39.513v-47c0-16.542-13.458-30-30-30h-37.073s-29.621-45.549-29.661-45.594c-1.847-2.73-4.928-4.406-8.266-4.406h-90.001c-3.385 0-6.54 1.712-8.385 4.55l-29.543 45.45h-37.073c-16.542 0-30 13.458-30 30v47h-39.505c4.765-8.303 6.373-18.059 4.402-27.595-3.606-17.459-6.493-39.068-8.581-64.225-1.094-13.169-7.045-25.349-16.758-34.297-9.716-8.953-22.345-13.883-35.559-13.883-2.704 0-5.292 1.095-7.176 3.035-1.883 1.94-2.9 4.56-2.819 7.262.122 4.138 3.182 100.17 21.995 141.919v36.991l-15.874 99.212c-.873 5.453 2.84 10.581 8.294 11.454.535.085 1.067.127 1.592.127 4.827 0 9.076-3.503 9.862-8.421l14.653-91.58h80.947l14.653 91.58c.786 4.918 5.035 8.421 9.862 8.421.524 0 1.057-.042 1.592-.127 5.453-.873 9.167-6.001 8.294-11.455l-15.874-99.212v-29.432c12.872-1.332 19.657-8.868 23.452-13.107 3.335-3.726 5.969-6.668 12.548-6.668v30c0 16.542 13.458 30 30 30h40v30c0 11.028-8.972 20-20 20h-10c-22.056 0-40 17.944-40 40 0 5.523 4.478 10 10 10s10-4.477 10-10c0-11.028 8.972-20 20-20h10c11.938 0 22.665-5.264 30-13.585 7.336 8.321 18.062 13.585 30 13.585h10c11.028 0 20 8.972 20 20 0 5.523 4.478 10 10 10s10-4.477 10-10c0-22.056-17.944-40-40-40h-10c-11.028 0-20-8.972-20-20v-30h40c16.542 0 30-13.458 30-30v-30c6.579 0 9.213 2.943 12.55 6.67 3.793 4.237 10.578 11.773 23.45 13.105v29.432l-15.874 99.212c-.873 5.454 2.841 10.582 8.294 11.455.535.085 1.067.127 1.592.127 4.827 0 9.076-3.503 9.862-8.421l14.653-91.58h80.947l14.653 91.58c.786 4.918 5.035 8.421 9.862 8.421.524 0 1.057-.042 1.592-.127 5.453-.873 9.167-6.001 8.294-11.455l-15.874-99.212v-36.991c1.122-2.485 2.193-5.148 3.25-8.096.899-2.52.76-5.23-.38-7.65-1.15-2.41-3.16-4.24-5.681-5.13-1.09-.39-2.22-.59-3.359-.59-4.221 0-8 2.67-9.42 6.64-.2.56-.4 1.11-.601 1.63-2.939-.96-5.58-2.6-7.83-4.86-3.68-3.72-5.71-8.59-5.71-13.72 0-1.31.141-2.66.42-3.99 3.761-18.24 6.761-40.65 8.92-66.619 1.07-12.89 9.65-23.9 21.861-28.09-.51 9.75-1.22 19.97-2.05 29.57-.23 2.66.58 5.25 2.3 7.3 1.72 2.04 4.13 3.3 6.79 3.53 2.8.24 5.55-.7 7.61-2.58 1.859-1.69 3-4.01 3.22-6.52 2.06-23.76 2.63-41.97 2.65-42.74.092-2.73-.918-5.31-2.808-7.26zm-208.182 38.309 14.074 21.651h-28.147zm-84.574-8.349h66.147l-19.5 30h-66.147zm-174.042 16.833c2.154 25.957 5.158 48.371 8.927 66.617 1.189 5.759-.269 11.689-3.999 16.269-2.425 2.977-5.6 5.137-9.144 6.299-11.934-31.546-16.278-91.6-17.636-117.235 11.847 4.125 20.767 14.982 21.852 28.05zm77.615 130.166h-78.001v-20h78.001zm186.001 0h-100.001c-5.514 0-10-4.486-10-10v-16.298c4.596 3.367 10.982 6.298 20 6.298 15.52 0 23.28-8.671 27.452-13.331 3.335-3.726 5.969-6.668 12.548-6.668s9.213 2.943 12.55 6.67c4.17 4.658 11.931 13.33 27.45 13.33 9.018 0 15.403-2.931 20-6.298v16.298c.001 5.513-4.485 9.999-9.999 9.999zm57.451-46.67c-4.17-4.658-11.931-13.33-27.45-13.33s-23.28 8.671-27.452 13.331c-3.335 3.726-5.969 6.668-12.548 6.668s-9.213-2.943-12.55-6.67c-4.17-4.658-11.931-13.33-27.45-13.33s-23.28 8.671-27.452 13.331c-3.335 3.726-5.969 6.668-12.548 6.668s-9.213-2.943-12.55-6.67c-4.17-4.658-11.931-13.33-27.45-13.33s-23.28 8.671-27.452 13.331c-3.335 3.726-5.969 6.668-12.548 6.668-3.601 0-7.443-2.349-10-4.369v-42.627c0-5.514 4.486-10 10-10h240.002c5.514 0 10 4.486 10 10v42.645c-2.503 1.984-6.333 4.354-10 4.354-6.581 0-9.215-2.943-12.552-6.67zm106.551 46.67h-78.001v-20h78.001z" />
                                                <circle cx="494.056" cy="202.93" r="10" />
                                            </g>
                                        </svg></span>
                                        <h6>Reservation</h6>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                    <span>
                                        <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 512 440.38">
                                            <path
                                                d="M426.13,275.11a194.15,194.15,0,0,0,.17-19.52c-1.45-36.12-16.43-70.42-32.28-106.73-2.73-6.24-5.51-12.62-8.26-19.1h1.83a20.6,20.6,0,0,0,20.58-20.58V62.13a20.6,20.6,0,0,0-20.58-20.57H316.93A44.15,44.15,0,0,0,274,75.66H239.71a10,10,0,0,0,0,20H274a44.21,44.21,0,0,0,31.39,32.55l21.55,96.07c6.81,30.31-5,48.22-16.16,57.91-17.26,15-44.52,19.8-66.31,11.62-18.89-7.09-30.9-22.73-33.81-44l-.83-6h8.47a10,10,0,0,0,10-10V206.1a34.3,34.3,0,0,0-24.24-32.74V10a10,10,0,0,0-10-10H22.19a10,10,0,0,0-10,10V51a10,10,0,1,0,20,0V20H67.43V80.19A10,10,0,0,0,82.68,88.7L108.1,73,133.53,88.7a10,10,0,0,0,5.24,1.49,10,10,0,0,0,10-10V20h35.28V171.87H32.19V140.82a10,10,0,0,0-20,0v89.69A68.42,68.42,0,0,0,0,269.6v78.75a29.4,29.4,0,0,0,29.36,29.38H53.29a72.65,72.65,0,0,0,143.91,0h150a72.64,72.64,0,0,0,143.9,0H502a10,10,0,0,0,10-10,93,93,0,0,0-85.87-92.62ZM128.77,62.27l-15.42-9.5a10,10,0,0,0-10.49,0l-15.43,9.5V20h41.34Zm236.31-.71H387.6a.61.61,0,0,1,.57.57v47.06a.62.62,0,0,1-.57.57H365.08A63.08,63.08,0,0,1,365.08,61.56Zm-50.25,48.1-.53,0A24.12,24.12,0,0,1,292.85,86.4c0-.24,0-.49,0-.74s0-.5,0-.75a24.12,24.12,0,0,1,24.08-23.35h26.94a81.33,81.33,0,0,0,0,48.2H316.93C316.22,109.76,315.52,109.72,314.83,109.66ZM32.19,191.87H194.05a14.25,14.25,0,0,1,14.24,14.23v17.64H32.19Zm93,228.51a52.72,52.72,0,0,1-51.68-42.65H176.93A52.73,52.73,0,0,1,125.23,420.38ZM53.07,357.73a72.86,72.86,0,0,1,144.34,0Zm264,0H217.57a92.87,92.87,0,0,0-184.66,0H29.36A9.38,9.38,0,0,1,20,348.35V269.6a48.5,48.5,0,0,1,7.43-25.86h162.2l1.2,8.76c3.9,28.49,20.88,50.38,46.6,60a84.51,84.51,0,0,0,29.75,5.29c20.65,0,41.35-7.16,56.72-20.54,21.54-18.75,29.54-46.23,22.55-77.38l-20.22-90.14h37.83c3.86,9.29,7.8,18.33,11.63,27.1,15.74,36,29.33,67.17,30.63,99.53,1.36,34.08-6.38,60.13-23,77.44-15.24,15.86-37.53,23.9-66.26,23.9Zm102.08,62.65a52.72,52.72,0,0,1-51.68-42.65H470.82A52.73,52.73,0,0,1,419.12,420.38ZM386.2,357.73a86.89,86.89,0,0,0,11.52-10.05C410.88,334,419.55,316.35,423.67,295a73,73,0,0,1,67.65,62.72Z" />
                                            <path d="M22.19,106a10,10,0,1,0-10-10V96A10,10,0,0,0,22.19,106Z" />
                                        </svg>
                                    </span>
                                        <h6>Delivery service</h6>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                    <span>
                                        <svg id="Capa_1" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 514 514">
                                            <defs>
                                            </defs>
                                            <path class="cls-1"
                                                  d="M95.74,297.18A30,30,0,1,0,139.28,333H217a30,30,0,0,1,0,60H97a50,50,0,0,0,0,100H197a10,10,0,0,0,0-20H97a30,30,0,0,1,0-60H217a50,50,0,0,0,0-100H139.28a30.18,30.18,0,0,0-13-15.82l76.37-125.31a110,110,0,1,0-183.26,0ZM111,333a10,10,0,1,1,10-10A10,10,0,0,1,111,333Zm0-312a90,90,0,0,1,74.88,139.94l-.22.35L111,283.79S36.19,161.05,36.11,160.94A90,90,0,0,1,111,21Z" />
                                            <path class="cls-1"
                                                  d="M111,161a50,50,0,1,0-50-50A50.06,50.06,0,0,0,111,161Zm0-80a30,30,0,1,1-30,30A30,30,0,0,1,111,81Z" />
                                            <path class="cls-1"
                                                  d="M403,167a110,110,0,0,0-91.58,171l76.1,119.36A30.24,30.24,0,0,0,374.72,473H287a10,10,0,0,0,0,20h87.72a30,30,0,1,0,43.76-35.69L494.58,338A110,110,0,0,0,403,167Zm0,326a10,10,0,1,1,10-10A10,10,0,0,1,403,493Zm74.89-166.06L403,444.4S328.15,327,328.11,326.94a90,90,0,1,1,149.78,0Z" />
                                            <path class="cls-1"
                                                  d="M403,227a50,50,0,1,0,50,50A50.06,50.06,0,0,0,403,227Zm0,80a30,30,0,1,1,30-30A30,30,0,0,1,403,307Z" />
                                            <circle class="cls-1" cx="242" cy="483" r="10" />
                                        </svg>
                                    </span>
                                        <h6>Reservation</h6>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="service-item-main" >
                        <div class="service-item">
                            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-2.png')}}" alt=""></span>
                            <h6 onclick="location.reload();location.href='cosmetic'">Cosmetics</h6>
                        </div>
                        <div class="service-item-info">
                            <ul>
                                @forelse($data ?? '' as $row)
                                    <li>
                                        <a href="{{URL::to('cosmetic-area/'.strtolower($row->slug))}}">
                                    <span>
                                        <?php echo file_get_contents(URL::to("storage/app/public/category/".$row->image)); ?>
                                    </span>
                                            <h6>{{$row->name}}</h6>
                                        </a>
                                    </li>
                                @empty
                                @endforelse

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="service-item-main">
                        <div class="service-item">
                            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-3.png')}}" alt=""></span>
                            <h6>Health</h6>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="service-item-main">
                        <div class="service-item">
                            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-4.png')}}" alt=""></span>
                            <h6>Law And Advice</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-work-section">
        <div class="container">
            <div class="title-text">
                <h6>How</h6>
                <h3>It Works</h3>
            </div>
            <div class="row how-it-row">
                <div class="col-xl-6 col-lg-6">
                    <div class="how-it-work-img">
                        <img src="{{URL::to('storage/app/public/Frontassets/images/cetering-service.png')}}" alt="">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="how-it-work-info">
                        <h5>Cetering Service</h5>
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
            <div class="row how-it-row">
                <div class="col-xl-6 col-lg-6">
                    <div class="how-it-work-info">
                        <h5>Online Booking</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
                            consectetur adipiscing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                            veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                            commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        <a href="javascript:void(0)">Read More...</a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 order">
                    <div class="how-it-work-img text-right">
                        <img src="{{URL::to('storage/app/public/Frontassets/images/online-booking.png')}}" alt="">
                    </div>
                </div>
            </div>
            <div class="row how-it-row">
                <div class="col-xl-6 col-lg-6">
                    <div class="how-it-work-img">
                        <img src="{{URL::to('storage/app/public/Frontassets/images/cetering-service-2.png')}}" alt="">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="how-it-work-info">
                        <h5>Cetering Service</h5>
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
        </div>
    </section>

    <!-- Where Do You Find Us -->
    <section class="find-us-section">
        <div class="container">
            <div class="title-text">
                <h6>Where</h6>
                <h3>Do You Find Us</h3>
            </div>
            <div class="owl-carousel owl-theme" id="find-us-owl">
                <div class="item">
                    <div class="find-us-item">
                        <div class="find-us-img">
                        <span class="color-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-1.png')}}" alt="">
                        </span>
                            <span class="black-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-1.png')}}" alt="">
                        </span>
                        </div>
                        <a href="javascript:void(0)">Barlin</a>
                    </div>
                </div>
                <div class="item">
                    <div class="find-us-item find-us-item2">
                        <div class="find-us-img">
                        <span class="color-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-2.png')}}" alt="">
                        </span>
                            <span class="black-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-2.png')}}" alt="">
                        </span>
                        </div>
                        <a href="javascript:void(0)">Munich</a>
                    </div>
                </div>
                <div class="item">
                    <div class="find-us-item find-us-item3">
                        <div class="find-us-img">
                        <span class="color-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-3.png')}}" alt="">
                        </span>
                            <span class="black-img">
                            <img src="{{URL::to('storage/app/public/Frontassets/images/find-us-3.png')}}" alt="">
                        </span>
                        </div>
                        <a href="javascript:void(0)">Hamburg</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What is The Advantage -->
    <section class="advantage-main-section">
        <div class="container">
            <div class="title-text">
                <h6>What is </h6>
                <h3>The Advantage</h3>
            </div>
            <ul class="advantage-ul">
                <li>
                    <div class="advantage-li-wrap">
                        <div class="advantage-circle-main">
                            <span class="advantage-img"><img src="{{URL::to('storage/app/public/Frontassets/images/advantage-1.svg')}}" alt=""></span>
                            <span class="advantage-circle"></span>
                        </div>
                        <div class="advantage-sub-info">
                            <p>Register now and use our functions
                                to your own Advantage!</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="advantage-li-wrap advantage-li-left">
                        <div class="advantage-sub-info">
                            <p>Use our service around around the clock, when and where you wan</p>
                        </div>
                        <div class="advantage-circle-main">
                            <span class="advantage-img"><img src="{{URL::to('storage/app/public/Frontassets/images/advantage-2.svg')}}" alt=""></span>
                            <span class="advantage-circle"></span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="advantage-li-wrap">
                        <div class="advantage-circle-main">
                            <span class="advantage-img"><img src="{{URL::to('storage/app/public/Frontassets/images/advantage-1.svg')}}" alt=""></span>
                            <span class="advantage-circle"></span>
                        </div>
                        <div class="advantage-sub-info">
                            <p>Register now and use our functions
                                to your own Advantage!</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="advantage-li-wrap advantage-li-left">
                        <div class="advantage-sub-info">
                            <p>Register now and use our functions
                                to your own Advantage!</p>
                        </div>
                        <div class="advantage-circle-main">
                            <span class="advantage-img"><img src="{{URL::to('storage/app/public/Frontassets/images/advantage-1.svg')}}" alt=""></span>
                            <span class="advantage-circle"></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <!-- What is The Advantage -->
    <section class="advantage-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="advantage-info">
                        <h6>Easily and Fast Reserved</h6>
                        <h3>reserved4you App <span>Available in both <br />
                            Platform</span></h3>
                        <span>
                        <a href="javascript:void(0)"><img src="{{URL::to('storage/app/public/Frontassets/images/google-play.svg')}}" alt=""></a>
                        <a href="javascript:void(0)"><img src="{{URL::to('storage/app/public/Frontassets/images/apple-play.svg')}}" alt=""></a>
                    </span>
                    </div>
                </div>
                <div class="col-lg-6 img-order">
                    <div class="advantage-img">
                        <img src="{{URL::to('storage/app/public/Frontassets/images/phone.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('front_js')
@endsection
