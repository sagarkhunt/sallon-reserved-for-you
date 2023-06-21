@extends('layouts.front')
@section('front_title')
    {{$store['store_name']}}
@endsection
@section('front_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
          crossorigin="anonymous"/>
    <style>
        .beauty-lashes-profile a.active i {
            color: #c99;
            font-weight: bold
        }

        .ElementsApp {
            height: 100% !important;
        }
    </style>
@endsection
@section('front_content')
    <!-- Home-banner -->
    <section class="home-banner-section about-home-banner-section">
        <div class="home-banner bpartners-banner cosmetics-banner">
            <div class="home-banner-img">
                <img src="{{URL::to('storage/app/public/store/banner/'.$store['store_banner'])}}" alt="">
            </div>
        </div>
    </section>
    <section class="beauty-lashes-section">
        <div class="container">
            <div class="beauty-lashes-profile">
                @if(file_exists(storage_path('app/public/store/'.$store['store_profile'])) && $store['store_profile'] != '')
                    <img src="{{URL::to('storage/app/public/store/'.$store['store_profile'])}}" alt="">

                @else
                    <img src="{{URL::to('storage/app/public/default/default_store.jpeg')}}" alt="">
                @endif
                <a href="javascript:void(0)" class="favorite_icon {{$store['isFavorite'] == 'true' ? 'active' : ''}}"
                   data-id="{{$store['id']}}"><i class="far fa-heart"></i></a>
            </div>
            <div class="beauty-lashes-info">
                <h6>{{$store['store_name']}}</h6>
                <p>{{$store['store_address']}}.</p>

                <ul>
                    {!! \BaseFunction::getRatingStar(ceil($store['rating'])) !!}
                    <span>({{count($feedback)}} Feedbacks)</span>
                </ul>
            </div>
            <div class="about-contact-box">
                <div class="about-contact-item">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/hair-salon.svg')}}" alt=""></span>
                    <h6>Services</h6>
                    <p>{{implode(',',$catlist)}}</p>
                </div>
                <div class="about-contact-item">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/clock.svg')}}" alt=""></span>
                    <h6>Time</h6>
                    <p>@if(@$storeToday['is_off'] != 'on'){{@$storeToday['start_time']}}
                        - {{@$storeToday['end_time']}}@else - @endif
                        <br/>
                        @if($sstatus == 'on' && @$storeToday['is_off'] != 'on')
                            <i style="color:green;">(Opened now) </i>
                        @else
                            <i style="color:red;"> (Closed now)</i>
                        @endif
                    </p>
                </div>
                <div class="about-contact-item">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/telephone.svg')}}" alt=""></span>
                    <h6>Contact</h6>
                    <p><a href="tel:{{$store['store_contact_number']}}"> {{$store['store_contact_number']}}</a></p>
                </div>
            </div>
            @if($store['store_active_plan'] =='business')
                <div class="service-reservation">
                    <h4>Service Reservation</h4>
                    <ul>
                        <li>
                    <span class="service-reservation-icon">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/room-service.svg")) ?>
                    </span>
                            {{Form::select('service',$serviceList,'',array('class'=>'select service_list'))}}

                        </li>
                        <li>
                    <span class="service-reservation-icon">
                        <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/teamwork.svg")) ?>
                    </span>
                            {{Form::select('employee_list',['' =>'Select Employee'] + array(),'',array('class'=>'select employee_list'))}}

                        </li>
                        <li>
                    <span
                        class="service-reservation-icon"><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/date-calendar.svg")) ?></span>

                            <input type="text" id="date_booking" name="date" class="datepicker date_select"
                                   autocomplete="off"
                                   placeholder="Select Date" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </li>

                        <li>
                    <span
                        class="service-reservation-icon"><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/clock.svg")) ?></span>
                            {{Form::select('time',['' =>'Select Time'] +array(),'',array('class'=>'select time_select'))}}
                        </li>

                        <li>
                            <a href="javascript:void(0)" class="btn btn-red paybooking">Book</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </section>
    <section class="service-reservation-section">
        <div class="container">
            <nav>
                <div class="nav nav-tabs reservation-tabs" id="nav-tab" role="tablist">
                    @if($store['store_active_plan'] =='business')
                        <a class="nav-link" id="nav-services-tab" data-toggle="tab" href="#nav-services"
                           role="tab"
                           aria-controls="nav-services" aria-selected="false">Services</a>
                    @endif
                    <a class="nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab"
                       aria-controls="nav-about" aria-selected="true">About us</a>
                    @if($store['store_active_plan'] !='basic')
                        <a class="nav-link" id="nav-gallery-tab" data-toggle="tab" href="#nav-gallery" role="tab"
                           aria-controls="nav-gallery" aria-selected="false">Gallery</a>
                        <a class="nav-link" id="nav-feedback-tab" data-toggle="tab" href="#nav-feedback" role="tab"
                           aria-controls="nav-feedback" aria-selected="false">Feedback</a>
                    @endif
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                @if($store['store_active_plan'] =='business')
                    <div class="tab-pane fade show" id="nav-services" role="tabpanel"
                         aria-labelledby="nav-services-tab">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="services-white-box">
                                    <div class="nav flex-column nav-pills services-nav-pills" id="v-pills-tab"
                                         role="tablist"
                                         aria-orientation="vertical">
                                        <div style="display: none">{{$i= 1}}</div>
                                        @foreach($cas as $row =>$key)

                                            <a class="nav-link @if($i == 1) active @endif"
                                               id="v-{{$row}}-lash-extension-tab"
                                               data-toggle="pill" href="#v-{{$row}}-extension" role="tab"
                                               aria-controls="v-{{$row}}-extension" aria-selected="true"><span><img
                                                        src="{{URL::to('storage/app/public/category/'.\App\Helpers\BaseFunction::getCategoryDate($row)['image'])}}"
                                                        alt=""></span> {{\App\Helpers\BaseFunction::getCategoryDate($row)['name']}}
                                            </a>

                                            <div style="display: none">{{$i++}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div style="display: none">{{$i= 1}}</div>
                                    @forelse($cas as $row =>$key)
                                        <div class="tab-pane fade show @if($i == 1) active @endif"
                                             id="v-{{$row}}-extension"
                                             role="tabpanel" aria-labelledby="v-pills-lash-extension-tab">
                                            <h4 class="services-header-title mt-0">Popular Services</h4>
                                            @forelse($key as $value)
                                                @if($value->is_popular == 'yes')
                                                    <div class="services-box-item discount-box">
                                                        <div class="services-box-item-img">
                                                            <img
                                                                src="{{URL::to('storage/app/public/service/'.$value['image'])}}"
                                                                alt="">
                                                            @if($value->discount_type != null && $value->discount != '0')
                                                                <span
                                                                    class="discount">{{$value->discount}}{{$value->discount_type == 'percentage' ? '%' : '€'}}
                                                                    OFF</span>
                                                            @endif
                                                        </div>
                                                        <div class="services-box-item-info">
                                                            <h6>{{$value->service_name}}</h6>
                                                            <p>{{$value->description}}
                                                            </p>

                                                            <ul>
                                                                <li class="services-box-item-time">{{$value->duration_of_service}}
                                                                    min
                                                                </li>

                                                                @if($value->discount_type != null && $value->discount != '0')
                                                                    <li class="orignal-price">{{$value->price}}€</li>
                                                                    <li>{{\BaseFunction::finalPrice($value->id)}}€</li>
                                                                @else
                                                                    <li>{{$value->price}}€</li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                        <span class="services-box-item-extra"></span>
                                                        @if($store['store_active_plan'] =='business')
                                                            <p class="services-box-item-price"><a
                                                                    href="javascript:void(0)"
                                                                    class="btn btn-red booking"
                                                                    data-id="{{$value->id}}">Book</a>
                                                            </p>
                                                        @endif

                                                    </div>
                                                @endif
                                            @empty
                                                <div class="text-center">
                                                    No service Found.
                                                </div>
                                            @endforelse


                                            <h4 class="services-header-title">All Services</h4>
                                            @forelse($key as $value)
                                                <div class="services-box-item">
                                                    <div class="services-box-item-img">
                                                        <img
                                                            src="{{URL::to('storage/app/public/service/'.$value['image'])}}"
                                                            alt="">
                                                        @if($value->discount_type != null && $value->discount != '0')
                                                            <span
                                                                class="discount">{{$value->discount}}{{$value->discount_type == 'percentage' ? '%' : '€'}}
                                            OFF</span>
                                                        @endif
                                                    </div>
                                                    <div class="services-box-item-info">
                                                        <h6>{{$value->service_name}}</h6>
                                                        <p>{{$value->description}}</p>

                                                        <ul>
                                                            <li class="services-box-item-time">{{$value->duration_of_service}}
                                                                min
                                                            </li>
                                                            @if($value->discount_type != null && $value->discount != '0')
                                                                <li class="orignal-price">{{$value->price}}€</li>
                                                                <li>{{\BaseFunction::finalPrice($value->id)}}€</li>
                                                            @else
                                                                <li>{{$value->price}}€</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <span class="services-box-item-extra"></span>
                                                    @if($store['store_active_plan'] =='business')
                                                        <p class="services-box-item-price"><a href="javascript:void(0)"
                                                                                              class="btn btn-red booking"
                                                                                              data-id="{{$value->id}}">Book</a>
                                                        </p>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="text-center">
                                                    No service Found.
                                                </div>
                                            @endforelse

                                        </div>
                                        <div style="display: none">{{$i++}}</div>
                                    @empty
                                        <div class="text-center">
                                            No service Found.
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="tab-pane fade show active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                    <div class="row filters-rows">
                        <div class="col-lg-4">
                            <div class="reservation-about-info">
                                <div class="reservation-about-map" id="map">
                                </div>
                                <div class="reservation-about-media">
                                <span>
                                    <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/restaurant.svg")) ?>
                                </span>
                                    <div class="reservation-about-media-info">
                                        <h6>District</h6>
                                        <p>{{$store['store_district']}}</p>
                                    </div>
                                </div>
                                <div class="reservation-about-media">
                                <span>
                                    <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/location.svg")) ?>
                                </span>

                                    <div class="reservation-about-media-info">
                                        <h6>Public Transportation</h6>
                                        <p>Nearest stop</p>
                                        @foreach($transport as $row)
                                            <div class="location-address-wrap mt-1">
                                                <p>
                                                    <span><?php echo file_get_contents(URL::to("storage/app/public/store/transportation/" . $row->image)) ?></span>
                                                    {{$row->transportation_no}}
                                                </p>
                                                <span><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/uturn.svg")) ?></span>
                                                <p>{{$row->title}}</p>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="reservation-about-media">
                                <span>
                                    <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/clock.svg")) ?>
                                </span>
                                    <div class="reservation-about-media-info">
                                        <h6>Opening Hours</h6>
                                        @foreach($storeTiming as $row)
                                            <p>{{$row->day}} : <span>@if($row->is_off == null){{$row->start_time}}
                                                    - {{$row->end_time}} @else Store Close @endif</span></p>
                                        @endforeach
                                        {{--                                        <p>Friday - Saturday : <span>09:30am - 8:00pm</span></p>--}}
                                    </div>
                                </div>
                                <div class="reservation-about-media">
                                <span>
                                    <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/parking-sign.svg")) ?>
                                </span>
                                    <div class="reservation-about-media-info">
                                        @foreach($parking as $row)
                                            <p>- {{$row->parking_name}}</p>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="reservation-about-media">
                                <span>
                                    <?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/home.svg")) ?>
                                </span>
                                    <div class="reservation-about-media-info">
                                        <h6>Homepage</h6>
                                        <a href="{{$store['store_link_id']}}">{{$store['store_link_id']}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="reservation-about-info2">
                                <p>{!! $store['store_description'] !!}</p>

                                @if(count($advantages) > 0)
                                    <h5>Advantages</h5>
                                @endif
                                <div class="row">
                                    @foreach($advantages as $row)
                                        <div class="col-md-6">
                                            <div class="reservation-about-info2-item">
                                                <span><?php echo file_get_contents(URL::to("storage/app/public/store/advantage/" . $row->image)) ?></span>
                                                <h6>{{$row->title}}</h6>
                                                <p>{{$row->description}}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                @if($store['store_active_plan'] =='business')
                                    @if(count($expert)>0)
                                        <div class="choose-expert-header-wrap">
                                            <h5>Out service Expert</h5>
                                        </div>

                                        <div class="owl-carousel owl-theme" id="expert-slider">
                                            @foreach($expert as $row)
                                                <div class="item">
                                                    <div class="experts-main-item">
                                        <span>
                                            <img src="{{URL::to('storage/app/public/store/employee/'.$row->image)}}"
                                                 alt="">
                                        </span>
                                                        <h6>{{$row->emp_name}}</h6>
                                                        <a href="javascript:void(0)" class="btn btn-red expert_book"
                                                           data-id="{{$row->id}}">Book</a>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($store['store_active_plan'] !='basic')
                    <div class="tab-pane fade" id="nav-gallery" role="tabpanel" aria-labelledby="nav-gallery-tab">
                        <div class="row">
                            @forelse($storeGallery as $row)
                                <div class="col-lg-3">
                                    <div class="c-gallery-item">
                                        <a href="{{URL::to('storage/app/public/store/gallery/'.$row->file)}}"
                                           data-fancybox="gallery">
                                            <img src="{{URL::to('storage/app/public/store/gallery/'.$row->file)}}"
                                                 alt="">
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div>
                                    <p class="text-center">No Images Found.</p>
                                </div>
                            @endforelse

                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-feedback" role="tabpanel" aria-labelledby="nav-feedback-tab">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="review-main-box services-white-box">
                                    <div class="review-main-top">
                                        <ul>
                                            {!! \BaseFunction::getRatingStar(ceil($store['rating'])) !!}
                                        </ul>
                                        <h4>{{$store['rating']}}</h4>
                                        <p>({{count($feedback)}} Feedbacks)</p>
                                    </div>
                                    <div class="review-main-bottom">
                                        <a href="javascript:void(0)" class="btn btn-black feedback">Give Feedback</a>
                                        <ul>
                                            <li>
                                                <p>Service & staff</p>
                                                <span>{{number_format($rating['service_rate'],1)}}/5</span>
                                            </li>
                                            <li>
                                                <p>Ambiance</p>
                                                <span>{{number_format($rating['ambiente'],1)}}/5</span>
                                            </li>
                                            <li>
                                                <p>Price-Performance ratio</p>
                                                <span>{{number_format($rating['preie_leistungs_rate'],1)}}/5</span>
                                            </li>
                                            <li>
                                                <p>Waiting period</p>
                                                <span>{{number_format($rating['wartezeit'],1)}}/5</span>
                                            </li>
                                            <li>
                                                <p>Atmosphere</p>
                                                <span>{{number_format($rating['atmosphare'],1)}}/5</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="review-box-header-wrap">
                                    <h6>Customer Reviews</h6>
                                    <select class="select reviews" data-id="{{$store['id']}}">
                                        <option value="5">5 Star</option>
                                        <option value="4">4 Star</option>
                                        <option value="3">3 Star</option>
                                        <option value="2">2 Star</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                </div>
                                <div class="review_data">
                                    @forelse($feedback as $row)
                                        <div class="review-box-item">
                                            <div class="review-profile">
                                                @if(file_exists(storage_path('app/public/user/'.@$row->userDetails->profile_pic)) &&
                                                @$row->userDetails->profile_pic != '')
                                                    <img
                                                        src="{{URL::to('storage/app/public/user/'.@$row->userDetails->profile_pic)}}"
                                                        alt="user">
                                                @else
                                                    <img
                                                        src="{{URL::to('storage/app/public/default/default-user.png')}}"
                                                        alt="user">
                                                @endif
                                            </div>
                                            <div class="review-info">
                                                <div class="review-info-head">
                                                    <h5>{{$row->userDetails->first_name}} {{$row->userDetails->last_name}}

                                                    </h5>
                                                    <div class="review-star">
                                                        <span>{{$row->total_avg_rating}} <i
                                                                class="fas fa-star"></i></span>
                                                        <i>{{\Carbon\Carbon::parse($row->updated_at)->diffForHumans()}}</i>
                                                    </div>

                                                </div>
                                                <p>{!! $row->write_comment !!}</p>
                                                <a href="javascript:void(0)">Read More</a>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center">
                                            No Feedback found.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="register-cosmetic-section">
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
                           data-target="#register-modal"
                           data-backdrop="static" data-keyboard="false">Register Now</a>

                    @endif
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">

                    <form action="{{URL::to('submit-payment-booking')}}" method="post"
                          class="payment-form require-validation"
                          name="charge"
                          id="payment-form">
                        @csrf
                        <h5>Service Information </h5>
                        <div class="service-modal-box">
                            <div class="service-box-img">
                                <img
                                    src="https://decodes-studio.com/reserved4you/storage/app/public/service/Service-6022c18fd9887.png"
                                    alt="" class="pay_service_image">
                            </div>
                            <div class="service-box-info">
                                <h5 class="pay_service">Hair cut service</h5>
                                <h6><i class="fas fa-user"></i> <span class="pay_emp">-</span></h6>
                                <h6><i class="fas fa-store"></i> <span
                                        class="success_service_store">{{$store['store_name']}}</span>
                                </h6>
                                <ul>
                                    <li>
                                        <p><i class="fas fa-calendar-day"></i> <span
                                                class="pay_date">jason statham</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-clock"></i> <span class="pay_time">2:32 AM</span></p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-euro-sign"></i> <span class="pay_price">55</span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h5>Payment Methods</h5>
                        @csrf
                        <div class="payment-mathod-wrap">
                            <label for="master-card" class="payment-mathod-label">
                                <input type="radio" name="payment" class="payment_btn" id="master-card"
                                       data-id="master_card" value="stripe">
                                <div>
                                    <img src="{{URL::to("storage/app/public/Frontassets/images/master-card.png")}}"
                                         alt="">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </label>
                            <label for="paypal" class="payment-mathod-label">
                                <input type="radio" name="payment" id="paypal" class="payment_btn" data-id="paypal"
                                       value="paypal">
                                <div>
                                    <img src="{{URL::to("storage/app/public/Frontassets/images/paypal.png")}}" alt="">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </label>
                            <label for="klarna" class="payment-mathod-label">
                                <input type="radio" name="payment" id="klarna" class="payment_btn" data-id="klarna"
                                       value="klarna">
                                <div>
                                    <img src="{{URL::to("storage/app/public/Frontassets/images/klarna.png")}}" alt="">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </label>
                            <label for="visa" class="payment-mathod-label">
                                <input type="radio" name="payment" id="visa" class="payment_btn" data-id="visa"
                                       value="stripe">
                                <div>
                                    <img src="{{URL::to("storage/app/public/Frontassets/images/visa.png")}}" alt="">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </label>
                            <label for="cash" class="payment-mathod-label">
                                <input type="radio" name="payment" id="cash" class="payment_btn" data-id="cash"
                                       value="cash">
                                <div>
                                    <img src="{{URL::to("storage/app/public/Frontassets/images/cash.png")}}" alt="">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </label>

                        </div>
                        <div class="payment_mothod">
                            <h5>Payment Details</h5>

                            <div class="row">
                                <div class="col-12">
                                    <input type="text" placeholder="Card Holder Name" name="name">
                                </div>
                                <div class="col-12">
                                    <input type="email" placeholder="Card Holder Email Address" name="email">
                                </div>
                                <input type="hidden" name="final_service" class="final_service">
                                <input type="hidden" name="final_date" class="final_date">
                                <input type="hidden" name="final_time" class="final_time">
                                <input type="hidden" name="final_employee" class="final_employee">
                                <input type="hidden" name="usertype" class="usertype">
                                <input type="hidden" name="user_f_name" class="user_f_name">
                                <input type="hidden" name="user_l_name" class="user_l_name">
                                <input type="hidden" name="user_email" class="user_email">
                                <input type="hidden" name="user_phone" class="user_phone">
                                <div class="col-12 payment-cardd">
                                    <div id="card-element" class="mt-3">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-book-modal mt-3">
                            <button class="btn btn-red paynow" disabled>Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- chooes-expert-modal -->
    <div class="modal fade" id="book-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">
                    <div class="book-order-info">
                    <span>
                        <img src="{{URL::to("storage/app/public/Frontassets/images/saloon-img-1.jpg")}}" alt=""
                             class="service_image">
                    </span>
                        <h6 class="service_name">Russian Volumme</h6>
                        <p class="service_price">45€</p>
                    </div>
                    <div class="choose-expert-box">
                        <h6>Choose Expert</h6>
                        <div class="empty-msg">
                            <p>Employee not available please chooes
                                another time & Date</p>
                        </div>
                        <div class="owl-carousel owl-theme choose-expert-box-wrap expert_owl" style="display: none"
                             id="expert-owl-2">
                            <div class="item">
                                <label for="choose-expert-1">
                                    <input type="radio" name="choose-expert" id="choose-expert-1">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Michel Doe</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-2">
                                    <input type="radio" name="choose-expert" id="choose-expert-2">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-3">
                                    <input type="radio" name="choose-expert" id="choose-expert-3">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Robert Paul</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-4">
                                    <input type="radio" name="choose-expert" id="choose-expert-4">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-5">
                                    <input type="radio" name="choose-expert" id="choose-expert-5">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="choose-book-date-time-white">
                        <div class="custom-date-wrap">
                            <input type="hidden" name="service_id" class="service_id">
                            <input type="text" id="datepicker" name="service_date" class="datepicker_modal"
                                   autocomplete="off" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="custom-time-wrap">
                            <label class="custom-time-label" for="time-1">
                                <input type="radio" name="custom-time" id="time-1">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label" for="time-2">
                                <input type="radio" name="custom-time" id="time-2">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label disable" for="time-2">
                                <input type="radio" name="custom-time" id="time-2">
                                <p>12:30</p>
                            </label>
                        </div>
                    </div>

                    <div class="btn-book-modal">
                        <a href="javascript:void(0)" class="btn btn-red paybookings">Book now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- chooes-service-modal -->
    <div class="modal fade" id="service-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">
                    <div class="book-order-info">
                    <span>
                        <img src="{{URL::to("storage/app/public/Frontassets/images/saloon-img-1.jpg")}}" alt=""
                             class="expert_image">
                    </span>
                        <h6 class="export_name">Russian Volumme</h6>
                        <input type="hidden" name="emp_id" class="emp_id">
                    </div>
                    <div class="choose-expert-box">
                        <h6>Choose Service</h6>
                        <div class="empty-msg">
                            <p>Service not available please chooes
                                another time & Date</p>
                        </div>
                        <div class="owl-carousel owl-theme choose-expert-box-wrap service_owl" style="display: none"
                             id="expert-owl-3">
                            <div class="item">
                                <label for="choose-expert-1">
                                    <input type="radio" name="choose-expert" id="choose-expert-1">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Michel Doe</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-2">
                                    <input type="radio" name="choose-expert" id="choose-expert-2">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-3">
                                    <input type="radio" name="choose-expert" id="choose-expert-3">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Robert Paul</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-4">
                                    <input type="radio" name="choose-expert" id="choose-expert-4">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="item">
                                <label for="choose-expert-5">
                                    <input type="radio" name="choose-expert" id="choose-expert-5">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to("storage/app/public/Frontassets/images/c-gallery-img-5.jpg")}}"
                                                alt=""></div>
                                        <p>Elizabeth Lisa</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="choose-book-date-time-white">
                        <div class="custom-date-wrap">
                            <input type="hidden" name="service_id" class="service_id">
                            <input type="text" id="datepicker" name="service_date" class="datepicker_exprt"
                                   autocomplete="off" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="custom-time-wrap expert-time emp_b_time">
                            <label class="custom-time-label" for="time-1">
                                <input type="radio" name="custom-time" id="time-1">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label" for="time-2">
                                <input type="radio" name="custom-time" id="time-2">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label disable" for="time-2">
                                <input type="radio" name="custom-time" id="time-2">
                                <p>12:30</p>
                            </label>
                        </div>
                    </div>

                    <div class="btn-book-modal">
                        <a href="javascript:void(0)" class="btn btn-red paybookingEmp">Book now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- rating-modal -->
    <div class="modal fade" id="rating-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">
                    <form class="submit-rating" name="submit-rating" id="submit-rating" method="post"
                          action="{{URL::to('submit-rating')}}">
                        @csrf
                        {{Form::hidden('store_id',$store['id'])}}
                        <h5 class="rating-modal-title">Rate you<span>Experience</span></h5>
                        <div class="rating-wrap">
                            <p>Services</p>

                            <div class="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating__input rating__input--none" checked name="service_rate"
                                           id="service_rate-0"
                                           value="0" type="radio">
                                    <label aria-label="0 stars" class="rating__label"
                                           for="service_rate-0">&nbsp;</label>
                                    <label aria-label="0.5 stars" class="rating__label rating__label--half"
                                           for="rating2-05"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-05" value="0.5"
                                           type="radio">

                                    <label aria-label="1 star" class="rating__label" for="service_rate-10"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-10" value="1"
                                           type="radio">

                                    <label aria-label="1.5 stars" class="rating__label rating__label--half"
                                           for="service_rate-15"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-15" value="1.5"
                                           type="radio">

                                    <label aria-label="2 stars" class="rating__label" for="service_rate-20"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-20" value="2"
                                           type="radio">

                                    <label aria-label="2.5 stars" class="rating__label rating__label--half"
                                           for="service_rate-25"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-25" value="2.5"
                                           type="radio"
                                           checked>

                                    <label aria-label="3 stars" class="rating__label" for="service_rate-30"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-30" value="3"
                                           type="radio">

                                    <label aria-label="3.5 stars" class="rating__label rating__label--half"
                                           for="service_rate-35"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-35" value="3.5"
                                           type="radio">

                                    <label aria-label="4 stars" class="rating__label" for="service_rate-40"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-40" value="4"
                                           type="radio">

                                    <label aria-label="4.5 stars" class="rating__label rating__label--half"
                                           for="service_rate-45"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-45" value="4.5"
                                           type="radio">

                                    <label aria-label="5 stars" class="rating__label" for="service_rate-50"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="service_rate" id="service_rate-50" value="5"
                                           type="radio">
                                </div>
                            </div>

                        </div>
                        <div class="rating-wrap">
                            <p>Ambiente</p>
                            <div class="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating__input rating__input--none" checked name="ambiente"
                                           id="ambiente-0"
                                           value="0" type="radio">
                                    <label aria-label="0 stars" class="rating__label" for="rating2-0">&nbsp;</label>
                                    <label aria-label="0.5 stars" class="rating__label rating__label--half"
                                           for="ambiente-05"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-05" value="0.5"
                                           type="radio">
                                    <label aria-label="1 star" class="rating__label" for="ambiente-10"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-10" value="1"
                                           type="radio">
                                    <label aria-label="1.5 stars" class="rating__label rating__label--half"
                                           for="ambiente-15"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-15" value="1.5"
                                           type="radio">
                                    <label aria-label="2 stars" class="rating__label" for="ambiente-20"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-20" value="2"
                                           type="radio">
                                    <label aria-label="2.5 stars" class="rating__label rating__label--half"
                                           for="ambiente-25"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-25" value="2.5"
                                           type="radio"
                                           checked>
                                    <label aria-label="3 stars" class="rating__label" for="ambiente-30"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-30" value="3"
                                           type="radio">
                                    <label aria-label="3.5 stars" class="rating__label rating__label--half"
                                           for="ambiente-35"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-35" value="3.5"
                                           type="radio">
                                    <label aria-label="4 stars" class="rating__label" for="ambiente-40"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-40" value="4"
                                           type="radio">
                                    <label aria-label="4.5 stars" class="rating__label rating__label--half"
                                           for="ambiente-45"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-45" value="4.5"
                                           type="radio">
                                    <label aria-label="5 stars" class="rating__label" for="ambiente-50"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="ambiente" id="ambiente-50" value="5"
                                           type="radio">
                                </div>
                            </div>
                        </div>
                        <div class="rating-wrap">
                            <p>Preis - Leistungs Verhaltnis</p>
                            <div class="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating__input rating__input--none" checked name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-0"
                                           value="0" type="radio">
                                    <label aria-label="0 stars" class="rating__label" for="preie_leistungs_rate-0">&nbsp;</label>

                                    <label aria-label="0.5 stars" class="rating__label rating__label--half"
                                           for="preie_leistungs_rate-05"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-05" value="0.5" type="radio">

                                    <label aria-label="1 star" class="rating__label" for="preie_leistungs_rate-10"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-10" value="1" type="radio">

                                    <label aria-label="1.5 stars" class="rating__label rating__label--half"
                                           for="preie_leistungs_rate-15"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-15" value="1.5" type="radio">

                                    <label aria-label="2 stars" class="rating__label" for="preie_leistungs_rate-20"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-20" value="2" type="radio">

                                    <label aria-label="2.5 stars" class="rating__label rating__label--half"
                                           for="preie_leistungs_rate-25"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-25" value="2.5" type="radio"
                                           checked>

                                    <label aria-label="3 stars" class="rating__label" for="preie_leistungs_rate-30"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-30" value="3" type="radio">

                                    <label aria-label="3.5 stars" class="rating__label rating__label--half"
                                           for="preie_leistungs_rate-35"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-35" value="3.5" type="radio">

                                    <label aria-label="4 stars" class="rating__label" for="preie_leistungs_rate-40"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-40" value="4" type="radio">

                                    <label aria-label="4.5 stars" class="rating__label rating__label--half"
                                           for="preie_leistungs_rate-45"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-45" value="4.5" type="radio">

                                    <label aria-label="5 stars" class="rating__label" for="preie_leistungs_rate-50"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="preie_leistungs_rate"
                                           id="preie_leistungs_rate-50" value="5" type="radio">
                                </div>
                            </div>
                        </div>
                        <div class="rating-wrap">
                            <p>Wartezeit</p>
                            <div class="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating__input rating__input--none" checked name="wartezeit"
                                           id="wartezeit-0"
                                           value="0" type="radio">
                                    <label aria-label="0 stars" class="rating__label" for="wartezeit-0">&nbsp;</label>
                                    <label aria-label="0.5 stars" class="rating__label rating__label--half"
                                           for="wartezeit-05"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-05" value="0.5"
                                           type="radio">
                                    <label aria-label="1 star" class="rating__label" for="wartezeit-10"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-10" value="1"
                                           type="radio">
                                    <label aria-label="1.5 stars" class="rating__label rating__label--half"
                                           for="wartezeit-15"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-15" value="1.5"
                                           type="radio">
                                    <label aria-label="2 stars" class="rating__label" for="wartezeit-20"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-20" value="2"
                                           type="radio">
                                    <label aria-label="2.5 stars" class="rating__label rating__label--half"
                                           for="rating2-25"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-25" value="2.5"
                                           type="radio"
                                           checked>
                                    <label aria-label="3 stars" class="rating__label" for="wartezeit-30"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-30" value="3"
                                           type="radio">

                                    <label aria-label="3.5 stars" class="rating__label rating__label--half"
                                           for="wartezeit-35"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-35" value="3.5"
                                           type="radio">

                                    <label aria-label="4 stars" class="rating__label" for="wartezeit-40"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-40" value="4"
                                           type="radio">

                                    <label aria-label="4.5 stars" class="rating__label rating__label--half"
                                           for="wartezeit-45"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-45" value="4.5"
                                           type="radio">

                                    <label aria-label="5 stars" class="rating__label" for="wartezeit-50"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="wartezeit" id="wartezeit-50" value="5"
                                           type="radio">
                                </div>
                            </div>
                        </div>
                        <div class="rating-wrap">
                            <p>Atmosphare</p>
                            <div class="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating__input rating__input--none" checked name="atmosphare"
                                           id="atmosphare-0"
                                           value="0" type="radio">
                                    <label aria-label="0 stars" class="rating__label" for="atmosphare-0">&nbsp;</label>

                                    <label aria-label="0.5 stars" class="rating__label rating__label--half"
                                           for="atmosphare-05"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-05" value="0.5"
                                           type="radio">

                                    <label aria-label="1 star" class="rating__label" for="atmosphare-10"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-10" value="1"
                                           type="radio">

                                    <label aria-label="1.5 stars" class="rating__label rating__label--half"
                                           for="atmosphare-15"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-15" value="1.5"
                                           type="radio">

                                    <label aria-label="2 stars" class="rating__label" for="atmosphare-20"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-20" value="2"
                                           type="radio">

                                    <label aria-label="2.5 stars" class="rating__label rating__label--half"
                                           for="atmosphare-25"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-25" value="2.5"
                                           type="radio"
                                           checked>

                                    <label aria-label="3 stars" class="rating__label" for="atmosphare-30"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-30" value="3"
                                           type="radio">

                                    <label aria-label="3.5 stars" class="rating__label rating__label--half"
                                           for="atmosphare-35"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-35" value="3.5"
                                           type="radio">

                                    <label aria-label="4 stars" class="rating__label" for="atmosphare-40"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-40" value="4"
                                           type="radio">

                                    <label aria-label="4.5 stars" class="rating__label rating__label--half"
                                           for="atmosphare-45"><i
                                            class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-45" value="4.5"
                                           type="radio">

                                    <label aria-label="5 stars" class="rating__label" for="atmosphare-50"><i
                                            class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="atmosphare" id="atmosphare-50" value="5"
                                           type="radio">
                                </div>
                            </div>
                        </div>

                        <div class="rating-textarea">
                            <textarea row="10" placeholder="Write Comment" name="write_comment"></textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-black" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- sucessed-modal -->
    <div class="modal fade" id="sucessed-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">
                    <div class="sucessed-modal">
                        <div class="sucessed-img">
                            <img src="{{URL::to("storage/app/public/Frontassets/images/sucessed.svg")}}" alt="">
                        </div>
                        <h5>Your payment has been
                            sucessed.</h5>
                        <div class="service-modal-box">
                            <div class="service-box-img">
                                <img
                                    src="https://decodes-studio.com/reserved4you/storage/app/public/service/Service-6022c18fd9887.png"
                                    alt="" class="success_servive_image">

                            </div>
                            <div class="service-box-info service-box-success-info">
                                <div class="service-box-span-wrap">
                                    <p>Payment id : <span class="success_order_id"> #36696313</span></p>
                                    <p>Payment type : <span class="success_payment_type"> Cash</span></p>
                                </div>
                                <h5 class="success_service_name">Hair cut service</h5>
                                <h6><i class="fas fa-store"></i> <span
                                        class="">{{$store['store_name']}}</span>
                                </h6>
                                <h6><i class="fas fa-user"></i> <span
                                        class="success_service_emp_name">jason statham</span>
                                </h6>
                                <ul>
                                    <li>
                                        <p><i class="fas fa-calendar-day"></i> <span class="success_date">jason
                                            statham</span></p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-clock"></i> <span class="success_time"> 2:32 AM</span></p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-dollar-sign"></i> <span class="success_Price"> 55</span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- failed-modal -->
    <div class="modal fade" id="failed-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">
                    <div class="sucessed-modal">
                        <div class="sucessed-img">
                            <img src="{{URL::to("storage/app/public/Frontassets/images/failed.svg")}}" alt="">
                        </div>
                        <h5>Your payment has been
                            failed.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('front_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" crossorigin="anonymous">
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $('.datepicker_modal').datepicker({
            startDate: new Date(),
            inline: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
            min: 0
        });
        $('.datepicker').datepicker({
            startDate: new Date(),
            inline: true,
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            min: 0
        });

        $('.datepicker_exprt').datepicker({
            startDate: new Date(),
            inline: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
            min: 0
        });


        var stripe = Stripe('pk_test_pyAji6er6sj1KeM06MlYOTsy00dkDuHTU2');
        var elements = stripe.elements();
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
                height: '50px !important',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {
            style: style
        });

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        var form = document.getElementById('payment-form');


        form.addEventListener('submit', function () {
            // event.preventDefault();
            var radioValue = $(".payment_btn:checked").val();
            // alert(radioValue);
            if (radioValue == 'stripe') {
                stripe.createToken(card).then(function (result) {
                    console.log(result)
                    return false;
                    if (result.error) {
                        // Inform the customer that there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            } else if (radioValue != '' && radioValue != undefined) {
                form.submit();
            }

        });


        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            if (token.id != undefined) {
                // Submit the form
                form.submit();
            }

        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSItHxCbk9qBcXp1XTysVLYcJick5K8mU&callback=initMap">
    </script>
    <script>
        var lat = "{{$store['latitude']}}";
        var long = "{{$store['longitude']}}";

        var sessions = "{{Session::get('payment_status')}}";
        var paymentData = <?php echo json_encode(Session::get('payment_data'))?>;


        if (sessions == 'success') {
            $('#sucessed-modal').modal('toggle');
            $('#sucessed-modal').modal({
                backdrop: 'static',
                keyboard: false
            })

            $('.success_servive_image').attr('src', paymentData.image);
            $('.success_service_name').text(paymentData.service_name);
            $('.success_service_store').text(paymentData.store_name);
            $('.success_service_emp_name').text(paymentData.emp_name);
            $('.success_date').text(paymentData.date);
            $('.success_time').text(paymentData.time);
            $('.success_Price').text(paymentData.price + '€');
            $('.success_order_id').text('#' + paymentData.order_id);
            $('.success_payment_type').text(paymentData.payment_type);

            {{Session::forget('payment_status')}}
        } else if (sessions == 'failed') {
            $('#failed-modal').modal('toggle');
            $('#failed-modal').modal({
                backdrop: 'static',
                keyboard: false
            })

            $('.success_servive_image').attr('src', paymentData.image);
            $('.success_service_name').text(paymentData.service_name);
            $('.success_service_store').text(paymentData.store_name);
            $('.success_service_emp_name').text(paymentData.emp_name);
            $('.success_date').text(paymentData.date);
            $('.success_time').text(paymentData.time);
            $('.success_Price').text(paymentData.price + '€');
            $('.success_order_id').text('#' + paymentData.order_id);
            $('.success_payment_type').text(paymentData.payment_type);

            {{Session::forget('payment_status')}}
        }

        function initMap() {
            var mapOptions = {
                zoom: 10,
                center: new google.maps.LatLng(parseFloat(lat), parseFloat(long)),
                mapTypeId: 'roadmap',
                fullscreenControl: false,
                StreetViewControlOptions: false,
                streetViewControl: false,
                streetView: false,

            };
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var goldenGatePosition = {
                lat: parseFloat(lat),
                lng: parseFloat(long)
            };
            var marker = new google.maps.Marker({
                position: goldenGatePosition,
                map: map
            });

        }
    </script>
    <script>
        $("body").addClass("cosmetics-body");
        $("body").addClass("cosmetics-area-body");

        $('.payment_mothod').css('display', 'none');

        /**
         * Open Booking modal get service Details
         */
        $(document).on('click', '.booking', function () {
            var id = $(this).data('id');
            var auth = "{{Auth::check()}}";

            var userType = localStorage.getItem('loginuser');

            if (auth != '' || userType == 'guest') {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('get-service-details')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        service: id
                    },
                    beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var data = response.data;
                        if (status == 'true') {

                            $('.service_id').val(data.id);
                            $('.service_name').text(data.service_name);
                            $('.service_price').text(data.price + '€');
                            $('.service_image').attr('src', data.image);
                            $('.datepicker_modal').datepicker('setDate', null);

                            getEmpForBooking(data.id);
                            $('.service_date').val('');
                            $('.service_time').val('');
                            $('.custom-time-wrap').css('display', 'none');
                            $('.choose-expert-box-wrap').css('display', 'none');
                            $('.empty-msg').css('display', 'block');

                            $('#book-modal').modal('toggle');
                            $('#book-modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            })
                        } else {
                        }
                        $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            } else {

                localStorage.setItem('loginredirect', 'book-modal');
                localStorage.setItem('loginredirectid', id);
                $('#login-modal').modal('toggle');
                $('#login-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

            }
        });

        /**
         * Get Employee list based on service on modal
         * @param service
         */
        function getEmpForBooking(service) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-employee-list')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var html = '';
                    if (status == 'true') {

                        $(response.data).each(function (i, item) {
                            html += ' <div class="item"><label for="choose-expert-' + i + '">\n' +
                                '                                <input type="radio" name="choose-expert" id="choose-expert-' +
                                i + '" value="' + item.id + '" class="choose-expert_q" data-value="' + item.emp_name + '">\n' +
                                '                                <div class="expert-lable-div">\n' +
                                '                                    <div class="expert-img"><img\n' +
                                '                                            src="' + item.image + '"\n' +
                                '                                            alt=""></div>\n' +
                                '                                    <p>' + item.emp_name + '</p>\n' +
                                '                                    <span><i class="far fa-check"></i></span>\n' +
                                '                                </div>\n' +
                                '                            </label> </div>';
                        });

                        $('.expert_owl').html(html);
                        $('.expert_owl').css('display', 'block');
                        $('.empty-msg').css('display', 'none');
                        $('#expert-owl-2').trigger('destroy.owl.carousel');
                        $('#expert-owl-2').owlCarousel({
                            loop: false,
                            nav: false,
                            dots: false,
                            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
                            responsive: {
                                0: {
                                    items: 1
                                },
                                600: {
                                    items: 3
                                },
                                1000: {
                                    items: 3.2
                                }
                            }
                        })


                    } else {

                        $('.empty-msg').css('display', 'block');
                        $('.expert_owl').css('display', 'none');

                    }

                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        }

        /**
         * Select Service From Drop down
         * Get Employee list for Drop down
         */
        $(document).on('change', '.service_list', function () {
            var service = $(this).val();
            var date = $('.date_select').val();


            if (service != '') {
                getEmployeeListForBooking(service);
            }
            if (service != '' && date != '') {
                getEmployeeList(service, date);
            }
            var emp = $('.employee_list').val();

            if (service != '' && date != '' && emp != null) {
                getTimeslot(date, emp, service);
            }

        });

        /**
         * Get Employee list for Drop down
         */
        function getEmployeeListForBooking(service) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-employee-list')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var html = '<option value="">Select Employee</option>';
                    if (status == 'true') {

                        $(response.data).each(function (i, item) {
                            html += '<option value="' + item.id + '">' + item.emp_name + '</option>';
                        });

                        $('.employee_list').html(html);
                        $('select.employee_list').niceSelect('update');

                    } else {

                        $('.employee_list').html(html);
                        $('select.employee_list').niceSelect('update');
                    }

                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        }

        /**
         * Booking Date Select Dropdown
         */
        $(document).on('change', '.date_select', function () {

            var service = $('.service_list').val();
            var date = $(this).val();
            var emp = $('.employee_list').val();

            if (service != '' && date != '' && emp == '') {
                getAvailableTimeDirect(date, service);
            }
            if (service != '' && date != '' && emp != null && emp != '') {
                getTimeslot(date, emp, service);
            }

            if (service != '' && date != '' && emp == '') {
                getEmployeeList(service, date);
            }

        });

        /**
         * Get time based on service and Date For drop down
         * @param date
         * @param service
         */
        function getAvailableTimeDirect(date, service) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-available-time-direct')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;

                    var html = '<option value="">Select Timeslot</option>';

                    if (status == 'true') {
                        $(response.data).each(function (i, item) {
                            if (item.time !== '00:00') {
                                if (item.flag == 'Available') {

                                    html += '<option value="' + item.time + '">' + item.time + '</option>';
                                } else {
                                    html += '<option value="' + item.time + '" disabled>' + item.time +
                                        '</option>';
                                }
                            }

                        });

                        $('.time_select').html(html);
                        $('select.time_select').niceSelect('update');

                    } else {

                        $('.time_select').html(html);
                        $('select.time_select').niceSelect('update');
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }

        /**
         * change employee and get data based on for Dropdown
         */
        $(document).on('change', '.employee_list', function () {
            var service = $('.service_list').val();
            var date = $('.date_select').val();
            var emp = $(this).val();

            if (service != '' && date != '' && emp != '') {
                getTimeslot(date, emp, service);
            }
            //
            // if (service != '' && emp != '') {
            //     setDatepicker(service, emp);
            // }
        });

        /**
         * Get Timeslot based on date service and employee for drop down
         * @param date
         * @param emp
         * @param service
         */
        function getTimeslot(date, emp, service) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-timeslot')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date,
                    emp: emp,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var html = '<option value="">Select Timeslot</option>';

                    if (status == 'true') {
                        $(response.data).each(function (i, item) {
                            if (item.time !== '00:00') {

                                if (item.flag == 'Available') {

                                    html += '<option value="' + item.time + '">' + item.time + '</option>';
                                } else {
                                    html += '<option value="' + item.time + '" disabled>' + item.time +
                                        '</option>';
                                }
                            }
                        });

                        $('.time_select').html(html);
                        $('select.time_select').niceSelect('update');

                    } else {

                        $('.time_select').html(html);
                        $('select.time_select').niceSelect('update');
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }

        /**
         * Get Employee list dropdon based on serive and date
         * @param service
         * @param date
         */
        function getEmployeeList(service, date) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-employee')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var html = '<option value="">Select Employee</option>';
                    if (status == 'true') {


                        $(response.data).each(function (i, item) {
                            html += '<option value="' + item.id + '">' + item.emp_name + '</option>';
                        });

                        $('.employee_list').html(html);
                        $('select.employee_list').niceSelect('update');

                    } else {

                        $('.employee_list').html(html);
                        $('select.employee_list').niceSelect('update');
                    }

                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });

        }

        /**
         *Set Datepicker
         * @param service
         * @param emp
         */
        function setDatepicker(service, emp) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-datepicker')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    emp: emp,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;

                    if (status == 'true') {
                        $('.datepicker').datepicker({
                            startDate: new Date(),
                            inline: true,
                            format: "dd-mm-yyyy",
                            autoclose: true,
                            daysOfWeekDisabled: response.data
                        });


                    }

                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        }

        /**
         * open payment modal click on book button dropdown
         */
        $(document).on('click', '.paybooking', function () {

            var auth = "{{Auth::check()}}";

            var userType = localStorage.getItem('loginuser');

            if (auth != '' || userType == 'guest') {
                var service = $('.service_list').val();
                var date = $('.date_select').val();
                var time = $('.time_select').val();
                var employee_list = $('.employee_list').val();
                if (service != '' && date != '' && time != '') {

                    $('.final_service').val(service);
                    $('.final_date').val(date);
                    $('.final_time').val(time);
                    $('.final_employee').val(employee_list);
                    $('.pay_date').html(date);
                    $('.pay_time').html(time);
                    userDetails();
                    getServiceData(service, employee_list);
                    // $('#book-modal').modal();
                    $('#payment-modal').modal();
                    $('#payment-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })

                }
            } else {
                $('#login-modal').modal('toggle');
                $('#login-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

            }

        });

        /**
         * Open payment modal click on booking modal
         */
        $(document).on('click', '.paybookings', function () {
            var service = $('.service_id').val();
            var date = $('.datepicker_modal').val();
            var time = $('.custome_time:checked').val();
            var employee_list = $("input[name='choose-expert']:checked").val();

            if (service != '' && date != '' && time != '' && time != undefined) {
                $('.final_service').val(service);
                $('.final_date').val(date);
                $('.final_time').val(time);
                $('.final_employee').val(employee_list);
                var service_name = $('.service_name').text();
                var service_image = $('.service_image').attr('src');
                $('.pay_service').text(service_name);
                $('.pay_service_image').attr('src', service_image);
                var emp_name = $("input[name='choose-expert']:checked").data('value');

                $('.pay_date').html(date);
                $('.pay_time').html(time);
                $('.pay_emp').html(emp_name);
                $('.pay_price').html($('.service_price').text());

                userDetails();


                $('#book-modal').modal('toggle');
                $('#payment-modal').modal();
                $('#payment-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            }
        })

        /**
         * Change service date into modal
         */
        $(document).on('change', '.service_date', function () {
            var service = $('.service_id').val();
            var date = $(this).val();
            var time = $('.service_time').val();

            if (service != '' && date != '' && time != '') {
                getEmployeeListForService(service, date, time);
            }
        });

        /**
         * Change Exprt based on servie time and date
         */
        $(document).on('change', '.service_time', function () {
            var service = $('.service_id').val();
            var date = $('.service_date').val();
            var time = $(this).val();

            if (service != '' && date != '' && time != '') {
                getEmployeeListForService(service, date, time);
            }
        });

        /**
         * Get employee list based on service date and time
         * @param service
         * @param date
         * @param time
         */
        function getEmployeeListForService(service, date, time) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-available-employee')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date,
                    time: time,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;

                    var html = '';
                    if (status == 'true') {


                        $(response.data).each(function (i, item) {
                            html += ' <label for="choose-expert-' + i + '">\n' +
                                '                                <input type="radio" name="choose-expert" id="choose-expert-' +
                                i + '" value="' + item.id + '">\n' +
                                '                                <div class="expert-lable-div">\n' +
                                '                                    <div class="expert-img"><img\n' +
                                '                                            src="' + item.image + '"\n' +
                                '                                            alt=""></div>\n' +
                                '                                    <p>' + item.emp_name + '</p>\n' +
                                '                                    <span><i class="far fa-check"></i></span>\n' +
                                '                                </div>\n' +
                                '                            </label>';
                        });

                        $('.choose-expert-box-wrap').html(html);
                        $('.choose-expert-box-wrap').css('display', 'block');
                        $('.empty-msg').css('display', 'none');


                    } else {

                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }

        /**
         * Favorite and unfavorite value
         */
        $(document).on('click', '.favorite_icon', function () {
            var auth = '{{Auth::check()}}';

            if (auth == '1') {
                var id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('favorite-store')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var type = response.data;
                        if (status == 'true') {
                            if (type == 'remove') {
                                $('.favorite_icon[data-id=' + id + ']').removeClass('active');
                            } else if (type == 'add') {
                                $('.favorite_icon[data-id=' + id + ']').addClass('active');
                            }
                        } else {

                        }
                        $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            } else {
                $('#login-modal').modal('toggle');
            }
        });

        /**
         * Get User Details
         */
        function userDetails() {
            var userType = localStorage.getItem('loginuser');
            if (userType == 'guest') {
                var first_name = localStorage.getItem('first_name');
                var last_name = localStorage.getItem('last_name');
                var email = localStorage.getItem('email');
                var phone_number = localStorage.getItem('phone_number');

                $('.usertype').val(userType);
                $('.user_f_name').val(first_name);
                $('.user_l_name').val(last_name);
                $('.user_email').val(email);
                $('.user_phone').val(phone_number);

            }
        }

        /**
         * Get Avialble Time slot in Modal based on the date time and emp
         */
        $(document).on('change', '.datepicker_modal', function () {
            var value = $(this).val();
            var service = $('.service_id').val();
            var emp = $('.choose-expert_q:checked').val();


            if (value != '' && service != '' && emp == null) {
                getAvailableTime(value, service);
            }


            if (emp != null && service != '' && value != "") {
                getTimeFromEmp(service, value, emp);
            }
        });

        /**
         * Choose expert valu
         */
        $(document).on('click', '.choose-expert_q', function () {
            var value = $('.datepicker_modal').val();
            var service = $('.service_id').val();
            var emp = $('.choose-expert_q:checked').val();
            if (emp != null && service != '' && value != "") {
                getTimeFromEmp(service, value, emp);
            }
        });

        function getTimeFromEmp(service, date, emp) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-timeslot')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date,
                    emp: emp,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;

                    var html = '';
                    if (status == 'true') {
                        var data = response.data
                        var lengh = data.length;

                        $(response.data).each(function (i, item) {
                            if (item.time !== '00:00') {
                                if (item.flag == 'Available') {
                                    html += ' <label class="custom-time-label" for="time-' + i + '">\n' +
                                        ' <input type="radio" name="service_time" id="time-' + i +
                                        '" class="custome_time" value="' + item.time + '">\n' +
                                        ' <p>' + item.time + '</p>\n' +
                                        ' </label>';
                                } else {
                                    html += ' <label class="custom-time-label disable" for="time-' + i +
                                        '">\n' +
                                        ' <input type="radio" name="service_time" id="time-' + i +
                                        '" class="custome_time" value="' + item.time + '">\n' +
                                        ' <p>' + item.time + '</p>\n' +
                                        ' </label>';
                                }
                            } else if (lengh <= 1) {
                                html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                            }


                        });

                        $('.custom-time-wrap').html(html);
                        $('.custom-time-wrap').css('display', 'block');

                    } else {
                        var html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                        $('.custom-time-wrap').html(html);
                    }
                    $('#loading').css('display', 'none');


                },
                error: function (e) {

                }
            });
        }

        function getAvailableTime(date, service) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-available-time')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    date: date,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;

                    var html = '';
                    if (status == 'true') {
                        var data = response.data

                        var lengh = data.length;

                        $(response.data).each(function (i, item) {
                            if (item.time != '00:00') {
                                if (item.flag == 'Available') {
                                    html += ' <label class="custom-time-label" for="time-' + i + '">\n' +
                                        ' <input type="radio" name="service_time" id="time-' + i +
                                        '" class="custome_time" value="' + item.time + '">\n' +
                                        ' <p>' + item.time + '</p>\n' +
                                        ' </label>';
                                } else {
                                    html += ' <label class="custom-time-label disable" for="time-' + i +
                                        '">\n' +
                                        ' <input type="radio" name="service_time" id="time-' + i +
                                        '" class="custome_time" value="' + item.time + '">\n' +
                                        ' <p>' + item.time + '</p>\n' +
                                        ' </label>';
                                }
                            } else if (lengh <= 1) {
                                html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                            }


                        });

                        $('.custom-time-wrap').html(html);
                        $('.custom-time-wrap').css('display', 'block');

                    } else {
                        var html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                        $('.custom-time-wrap').html(html);
                    }
                    $('#loading').css('display', 'none');


                },
                error: function (e) {

                }
            });
        }

        function getServiceData(service, employee_list) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-service-data')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: service,
                    employee_list: employee_list,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    if (status == 'true') {

                        $('.pay_service').text(data.service_name);
                        $('.pay_service_image').attr('src', data.image);
                        $('.pay_price').html(data.discount_price);
                        $('.pay_emp').html(data.emp_name);
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }

        $(document).on('click', '.paynow', function () {
            var checked = $('.payment_btn:checked').val();

            if (checked != null) {
                $('#loading').css('display', 'block');
                $('.payment-form').submit();
            }

        });
    </script>
    <script>
        $(document).on('click', '.feedback', function () {


            var auth = "{{Auth::check()}}";

            if (auth != '') {
                $('#rating-modal').modal('toggle');
            } else {
                localStorage.setItem('loginredirect', 'rating-modal');
                $('#login-modal').modal('toggle');
            }

        });

        /**
         * Select Payment Method
         */
        $(document).on('click', '.payment_btn', function () {
            var id = $(this).data('id');

            if (id == 'paypal' || id == 'cash' || id == "klarna") {
                $('.payment_mothod').css('display', 'none');
                $('.paynow').removeAttr('disabled');
            }
            if (id == 'master_card' || id == 'visa') {
                $('.payment_mothod').css('display', 'block');
                $('.paynow').removeAttr('disabled');
                // $('.paynow').attr('disabled','disabled');
            }

        });
    </script>
    <script>
        $(document).on('click', '.expert_book', function () {
            var emp = $(this).data('id');

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-employee-data')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    emp: emp
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    if (status == 'true') {

                        $('.emp_id').val(data.id);
                        $('.export_name').text(data.emp_name);
                        $('.expert_image').attr('src', data.image);
                        serviceList(emp);
                        $('#service-modal').modal('toggle');

                    } else {
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        });

        function serviceList(id) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-service-value-data')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    emp: id
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    var html = '';
                    if (status == 'true') {

                        $(response.data).each(function (i, item) {
                            html += ' <div class="item"><label for="choose-experts-' + i + '">\n' +
                                '                                <input type="radio" name="choose-experts" class="choose-experts" id="choose-experts-' + i + '"' +
                                ' value="' + item.id + '" data-value="' + item.service_name + '" data-image="' + item.image + '">\n' +
                                '                                <div class="expert-lable-div">\n' +
                                '                                    <div class="expert-img"><img\n' +
                                '                                            src="' + item.image + '"\n' +
                                '                                            alt=""></div>\n' +
                                '                                    <p>' + item.service_name + '</p>\n' +
                                '                                    <span><i class="far fa-check"></i></span>\n' +
                                '                                </div>\n' +
                                '                            </label> </div>';
                        });

                        $('.service_owl').html(html);
                        $('.service_owl').css('display', 'block');
                        $('.empty-msg').css('display', 'none');
                        $('#expert-owl-3').trigger('destroy.owl.carousel');
                        $('#expert-owl-3').owlCarousel({
                            loop: false,
                            nav: false,
                            dots: false,
                            responsive: {
                                0: {
                                    items: 1
                                },
                                600: {
                                    items: 3
                                },
                                1000: {
                                    items: 3.2
                                }
                            }
                        })

                    } else {
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }

        $(document).on('change', '.datepicker_exprt', function () {
            var date = $(this).val();
            var emp = $('.emp_id').val();
            var service = $('.choose-experts:checked').val();

            if (date != '' && emp != '' && service != '' && service != undefined) {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('get-timeslot')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        service: service,
                        date: date,
                        emp: emp,
                    },
                    beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var html = '';

                        var data = response.data
                        var lengh = data.length;

                        if (status == 'true') {
                            $(response.data).each(function (i, item) {
                                if (item.time !== '00:00') {
                                    if (item.flag == 'Available') {
                                        html += ' <label class="custom-time-label" for="times-' + i + '">\n' +
                                            ' <input type="radio" name="service_time_b" id="times-' + i +
                                            '" class="custome_timeb" value="' + item.time + '">\n' +
                                            ' <p>' + item.time + '</p>\n' +
                                            ' </label>';
                                    } else {
                                        html += ' <label class="custom-time-label disable" for="times-' + i +
                                            '">\n' +
                                            ' <input type="radio" name="service_time_b" id="times-' + i +
                                            '" class="custome_timeb" value="' + item.time + '" disabled>\n' +
                                            ' <p>' + item.time + '</p>\n' +
                                            ' </label>';
                                    }
                                } else if (lengh <= 1) {
                                    html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                                }


                            });
                            $('.emp_b_time').html(html);
                        } else {

                            html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                            $('.emp_b_time').html(html);
                        }
                        $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }
        });

        $(document).on('click', '.paybookingEmp', function () {
            var service = $('.choose-experts:checked').val();
            var date = $('.datepicker_exprt').val();
            var time = $('.custome_timeb:checked').val();
            var employee_list = $(".emp_id").val();

            if (service != '' && date != '' && time != '' && time != undefined) {
                $('.final_service').val(service);
                $('.final_date').val(date);
                $('.final_time').val(time);
                $('.final_employee').val(employee_list);
                var service_name = $('.choose-experts:checked').data('value');
                var service_image = $('.choose-experts:checked').data('image');
                $('.pay_service').text(service_name);
                $('.pay_service_image').attr('src', service_image);
                var emp_name = $(".export_name").text();

                $('.pay_date').html(date);
                $('.pay_time').html(time);
                $('.pay_emp').html(emp_name);
                $('.pay_price').html($('.service_price').text());

                userDetails();


                $('#service-modal').modal('toggle');
                $('#payment-modal').modal();
                $('#payment-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            }
        })
    </script>
    <script>
        $(document).on('change', '.reviews', function () {
            var value = $(this).val();
            var store_id = $(this).data('id');

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('rating-filter')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    store_id: store_id,
                    value: value,
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    var html = '';
                    if (status == 'true') {
                        $(data).each(function (i, item) {
                            html += ' <div class="review-box-item">\n' +
                                '                                            <div class="review-profile">\n' +
                                '                                               \n' +
                                '                                                    <img\n' +
                                '                                                        src="' + item.profile_pic + '"\n' +
                                '                                                        alt="user">\n' +
                                '                                    \n' +
                                '                                            </div>\n' +
                                '                                            <div class="review-info">\n' +
                                '                                                <div class="review-info-head">\n' +
                                '                                                    <h5>' + item.user_name + '</h5>\n' +
                                '                                                    <div class="review-star">\n' +
                                '                                                        <span>' + item.total_avg_rating + ' <i\n' +
                                '                                                                class="fas fa-star"></i></span>\n' +
                                '                                                        <i>' + item.time + '</i>\n' +
                                '                                                    </div>\n' +
                                '\n' +
                                '                                                </div>\n' +
                                '                                                <p>' + item.write_comment + '</p>\n' +
                                '                                                \n' +
                                '                                            </div>\n' +
                                '                                        </div>';
                        });
                    } else {
                        html += '<div class="text-center">No Feedback Found.</div>';
                    }

                    $('.review_data').html(html);
                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        })

        function redirectUser(id) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-service-details')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: id
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    if (status == 'true') {

                        $('.service_id').val(data.id);
                        $('.service_name').text(data.service_name);
                        $('.service_price').text(data.price + '€');
                        $('.service_image').attr('src', data.image);
                        $('.datepicker_modal').datepicker('setDate', null);

                        getEmpForBooking(data.id);
                        $('.service_date').val('');
                        $('.service_time').val('');
                        $('.custom-time-wrap').css('display', 'none');
                        $('.choose-expert-box-wrap').css('display', 'none');
                        $('.empty-msg').css('display', 'block');

                        $('#book-modal').modal('toggle');
                        $('#book-modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        })
                    } else {
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        }
    </script>

@endsection
