@extends('layouts.serviceProvider')
@section('service_title')
    Appointment List
@endsection
@section('service_css')
@endsection
@section('service_content')
    <div class="body-content">
        <div class="appointments-navs-wrap">
            <ul class="nav nav-pills statistics-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-id="appointment" id="pills-appointments-tab" data-toggle="pill" href="#pills-appointments"
                       role="tab" aria-controls="pills-appointments" aria-selected="true">My Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-id="past" id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab"
                       aria-controls="pills-order" aria-selected="false">Order History</a>
                </li>
            </ul>
            <ul class="list-calander-wrap">
                <li>
                    <a href="javascript:void(0)" class="viewC">
                        <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/calendar.svg')); ?>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="viewL active">
                        <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/list.svg')); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content appointments-order-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-appointments" role="tabpanel"
                 aria-labelledby="pills-appointments-tab">
                <!-- <input type="text" id="datepicker"> -->
                <div class="heading-appointment-wrap">
                    <h5 class="page-title">My Appointments ({{count($order)}})</h5>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#new-appointment-modal"
                       class="btn btn-black"> + &nbsp; New Appointment</a>
                </div>
                <div class="appointment-searchbar">
                    <input placeholder="Search here" type="text" name="search" value="" class="search_word" autocomplete="off" autofill="off">
                    <button class="btn btn-black search">Search</button>
                </div>
                <div class="appointments-details-view">

                    <div class="app_datas">

                        @forelse($order as $row)
                            <div class="appointments-item-wrap">
                                <div class="appointments-profile">

                                    @if(file_exists(storage_path('app/public/service/'.@$row->serviceDetails->image)) && @$row->serviceDetails->image != '')
                                        <img
                                            src="{{URL::to('storage/app/public/service/'.@$row->serviceDetails->image)}}"
                                            alt=""
                                        >
                                    @else
                                        <img src="{{URL::to('storage/app/public/default/store_default.png')}}"
                                             alt=""
                                        >
                                    @endif
                                </div>
                                <div class="appointments-main-info">
                                    <div class="appointments-sub-wrap">
                                        <div class="appointments-info">
                                            <h5>{{$row->service_name}}</h5>
                                            <h6>{{@$row->serviceDetails->CategoryData->name}}</h6>
                                            <ul>
                                                <li>
                                                    <p>Appointment Date : <span>{{\Carbon\Carbon::parse($row->appo_date)->format('d M, Y')}}, {{\Carbon\Carbon::parse($row->appo_time)->format('H:i')}}</span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>Expert : <span>{{@$row->employeeDetails->emp_name}}</span></p>
                                                </li>
                                            </ul>
                                            <h6 class="mt-1">Store Name :
                                                <span>{{@$row->storeDetails->store_name}}</span></h6>
                                        </div>
                                        <p class="appointments-status">Status : <span>{{$row->status}}</span></p>
                                        @if($row->status == 'booked' || $row->status == 'reschedule')
                                            <div class="appointments-action">
                                                <a href="javascript:void(0)" class="btn btn-black cancel_appointment"
                                                   data-id="{{$row->id}}">Cancel Appointment</a>
                                                <a href="javascript:void(0)" class="btn btn-gray postpond_appointment"
                                                   data-id="{{$row->id}}">Postpone Appointment</a>
                                            </div>
                                        @elseif($row->status == 'running')
                                            <div class="appointments-action">
                                                <a href="javascript:void(0)" class="btn btn-black complete_appointment"
                                                   data-id="{{$row->id}}">Complete Appointment</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>Order ID : <span>#{{$row->order_id}}</span></p>
                                        <p>Total Paid : <span>{{$row->price}}€</span></p>
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>User Name:
                                            <span>{{$row->user_id != '' ? @$row->userDetails->first_name.' '.@$row->userDetails->last_name: $row->first_name.' '.$row->last_name}}</span>
                                        </p>
                                        <p>Payment Method :
                                            <span>{{$row->payment_method != '' ?  $row->payment_method :'-'}}</span></p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center;margin-top: 50px;">No Appointment Found.</div>
                        @endforelse
                    </div>
                </div>
                <div class="calander-view" style="display: none">
                    <div id='calendar'></div>
                    <div class="modal fade calcView" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-content-radius">
                                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="modal-body">
                                    <div class="appointment-modal-profile">
                                    <span>
                                        <img src="{{URL::to('storage/app/public/Frontassets/images/saloon-img-1.jpg')}}"
                                             alt="">
                                    </span>
                                        <h6>Deniana Omul</h6>
                                        <p>Berlin</p>
                                    </div>
                                    <div class="appointment-modal-info">
                                        <div class="appointment-d-flex">
                                            <p>Order ID :<span>#26672107231</span></p>
                                            <p>Appt.Date :<span>Nov 7, 2020 10:45PM</span></p>
                                        </div>
                                        <p class="appointment-modal-status">Status : <span>Running</span></p>
                                        <div class="appointment-box-main">
                                            <div class="appointment-box-wrap">
                                                <div>
                                                    <h5>Color Retouch,Bleach & Tone</h5>
                                                    <h6>Expert : <span>Expert : </span></h6>
                                                </div>
                                                <p>10:00 - 11:00</p>
                                                <h6>45€</h6>
                                            </div>
                                            <div class="appointment-box-wrap">
                                                <div>
                                                    <h5>Color Retouch,Bleach & Tone</h5>
                                                    <h6>Expert : <span>Expert : </span></h6>
                                                </div>
                                                <p>10:00 - 11:00</p>
                                                <h6>45€</h6>
                                            </div>
                                        </div>
                                        <div class="appointment-d-flex">
                                            <h6>Discount</h6>
                                            <h6>5€</h6>
                                        </div>
                                        <div class="appointment-d-flex appointment-total">
                                            <h5>TOTAL BILL PAID <span>(Via Credit card)</span></h5>
                                            <h4>$85</h4>
                                        </div>
                                        <div class="btn-modal-wrap">
                                            <a href="javascript:void(0)" class="btn btn-black">Cancel Appointment</a>
                                            <a href="javascript:void(0)" class="btn btn-gray">postpone Appointment</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-order" role="tabpanel" aria-labelledby="pills-order-tab">
                <h5 class="page-title">Order History ({{count($pastOrder)}})</h5>
                <input type="text" name="search" value="" class="search_word_recent" autocomplete="off"
                       autofill="off">
                <button class="btn btn-black search_recent">Search</button>
                <div class="order-details-view">

                    <div class="search_rece">
                        @forelse($pastOrder as $row)
                            <div class="appointments-item-wrap">
                                <div class="appointments-profile">
                                    <img src="{{URL::to('storage/app/public/service/'.@$row->serviceDetails->image)}}"
                                         alt="">
                                </div>
                                <div class="appointments-main-info">
                                    <div class="appointments-sub-wrap">
                                        <div class="appointments-info">
                                            <h5>{{$row->service_name}}</h5>
                                            <h6>{{@$row->serviceDetails->CategoryData->name}}</h6>
                                            <ul>
                                                <li>
                                                    <p>Appointment Date : <span>{{\Carbon\Carbon::parse($row->appo_date)->format('d M, Y')}}, {{\Carbon\Carbon::parse($row->appo_time)->format('H:i')}}</span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>Expert : <span>{{@$row->employeeDetails->emp_name}}</span></p>
                                                </li>
                                            </ul>
                                            <h6 class="mt-1">Store Name :
                                                <span>{{@$row->storeDetails->store_name}}</span>
                                            </h6>
                                        </div>
                                        <p class="appointments-status order-status">Status :
                                            <span>{{$row->status}}</span>
                                        </p>
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>Order ID : <span>#{{$row->order_id}}</span></p>
                                        <p>Total Paid : <span>{{$row->price}}€</span></p>
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>User Name:
                                            <span>{{$row->user_id != '' ? @$row->userDetails->first_name.' '.@$row->userDetails->last_name: $row->first_name.' '.$row->last_name}}</span>
                                        </p>
                                        <p>Payment Method :
                                            <span>{{$row->payment_method != '' ?  $row->payment_method :'-'}}</span></p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center;margin-top: 50px;">No Appointment Found.</div>
                        @endforelse
                    </div>
                </div>
                <div class="calander-view-order" style="display: none">
                    <div id='calendar-order'></div>
                    <div class="modal fade order_calc" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-content-radius">
                                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="modal-body">
                                    <div class="appointment-modal-profile">
                                    <span>
                                        <img src="{{URL::to('storage/app/public/Frontassets/images/saloon-img-1.jpg')}}"
                                             alt="">
                                    </span>
                                        <h6>Deniana Omul</h6>
                                        <p>Berlin</p>
                                    </div>
                                    <div class="appointment-modal-info">
                                        <div class="appointment-d-flex">
                                            <p>Order ID :<span>#26672107231</span></p>
                                            <p>Appt.Date :<span>Nov 7, 2020 10:45PM</span></p>
                                        </div>
                                        <p class="appointment-modal-status">Status : <span>Running</span></p>
                                        <div class="appointment-box-main">
                                            <div class="appointment-box-wrap">
                                                <div>
                                                    <h5>Color Retouch,Bleach & Tone</h5>
                                                    <h6>Expert : <span>Expert : </span></h6>
                                                </div>
                                                <p>10:00 - 11:00</p>
                                                <h6>45€</h6>
                                            </div>
                                            <div class="appointment-box-wrap">
                                                <div>
                                                    <h5>Color Retouch,Bleach & Tone</h5>
                                                    <h6>Expert : <span>Expert : </span></h6>
                                                </div>
                                                <p>10:00 - 11:00</p>
                                                <h6>45€</h6>
                                            </div>
                                        </div>
                                        <div class="appointment-d-flex">
                                            <h6>Discount</h6>
                                            <h6>5€</h6>
                                        </div>
                                        <div class="appointment-d-flex appointment-total">
                                            <h5>TOTAL BILL PAID <span>(Via Credit card)</span></h5>
                                            <h4>$85</h4>
                                        </div>
                                        <div class="btn-modal-wrap">
                                            <a href="javascript:void(0)" class="btn btn-black">Cancel Appointment</a>
                                            <a href="javascript:void(0)" class="btn btn-gray">postpone Appointment</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- cancel-appointment-modal -->
    <div class="modal fade" id="cancel-appointment-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                {{Form::open(array('url'=>'service-provider/cancel-appointment','method'=>'post','name'=>'cancel_appointment','class'=>'cancel_appointment'))}}
                <div class="modal-body modal-bg">
                    <div class="user-profile-info">
                        <span class="user-profile-imgs"><img src="../assets/images/saloon-img-1.jpg" alt=""
                                                             class="service_image_c"></span>
                        <h6 class="service_name_c">Brazilian Wax</h6>
                        <p>Order ID :<span class="order_number_c">#26672107231</span></p>
                        <p>Expert : <span class="text-c99 service_export_c"></span></p>
                    </div>
                    <input type="hidden" name="user_id" class="user_id_c">
                    <input type="hidden" name="appointment_id" class="appointment_id_c">
                    <input type="hidden" name="service_id" class="service_id_c">
                    <input type="hidden" name="emp_id" class="emp_id_c">
                    <div class="cancle-text-box">
                        <textarea rows="10" placeholder="Write Reason..." name="reason" class="reason"></textarea>
                    </div>
                    <div class="btn-book-modal text-center">
                        <a href="javascript:void(0)" class="btn btn-cosmetics cancel_appt">Cancel Appointment</a>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <!-- reschedule-appointment-modal -->
    <div class="modal fade" id="reschedule-appointment-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body modal-bg">

                    <div class="user-profile-info">
                        <span class="user-profile-imgs"><img src="../assets/images/saloon-img-1.jpg"
                                                             class="service_image" alt=""></span>
                        <h6 class="service_name">Brazilian Wax</h6>
                        <p>Order ID :<span class="order_number">#26672107231</span></p>
                        <p>Expert : <span class="text-c99 service_export"></span></p>
                    </div>
                    <div class="new-appointment-form">
                        <input type="text" placeholder="Customer name" name="username" class="user_name" readonly>

                    </div>
                    <div class="choose-expert-box choose-extra-box-padding">

                        <div class="custom-date-wrap">
                            <!-- <input type="text" id="datepicker" name="date"
                                   class="datepicker datepicker_postpond datepicker_modal"
                                   autocomplete="off" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}"> -->
                                   <input type="text" id="datepicker" name="date" class="datepicker_postpond"
                                   autocomplete="off"
                                   placeholder="Select Date" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="custom-time-wrap postpond_time">
                            <label class="custom-time-label" for="time-4">
                                <input type="radio" name="custom-time" id="time-4">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label" for="time-5">
                                <input type="radio" name="custom-time" id="time-5">
                                <p>12:30</p>
                            </label>
                            <label class="custom-time-label disable" for="time-6">
                                <input type="radio" name="custom-time" id="time-6">
                                <p>12:30</p>
                            </label>
                        </div>
                        <input type="hidden" name="user_id" class="user_id">
                        <input type="hidden" name="appointment_id" class="appointment_id">
                        <input type="hidden" name="service_id" class="service_id">
                        <input type="hidden" name="emp_id" class="emp_id">

                    </div>

                    <div class="btn-book-modal2 text-center mt-3">
                        <a href="javascript:void(0)" class="btn btn-cosmetics pst_appo">Postpond Appointment</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- new-appointment-modal -->
    <div class="modal fade" id="new-appointment-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                {{Form::open(array('url'=>'service-provider/create-appointment','method'=>'post','name'=>"create-appointment",'class'=>'create-appointment'))}}
                <div class="modal-body modal-bg">
                    <div class="new-appointment-form">
                        <h5>New Appointment</h5>
                        <input type="text" name="first_name" placeholder="Customer First name">
                        <input type="text" name="last_name" placeholder="Customer Last name">
                        <input type="email" name="email" placeholder="Customer Email">
                        <input type="text" name="phone_number" placeholder="Customer Phone number">
                    </div>
                    <div class="choose-expert-box">
                        <h6>Choose Services</h6>
                        <div class="choose-expert-box-wrap owl-carousel owl-theme" id="expert-owl-2">
                            @forelse($service as $row)
                                <label for="choose-service-{{$row->id}}">
                                    <input type="radio" name="choose_service" id="choose-service-{{$row->id}}"
                                           value="{{$row->id}}" class="choose-service">
                                    <div class="expert-lable-div">
                                        <div class="expert-img"><img
                                                src="{{URL::to('storage/app/public/service/'.$row['image'])}}" alt="">
                                        </div>
                                        <p>{{$row['service_name']}}</p>
                                        <span><i class="far fa-check"></i></span>
                                    </div>
                                </label>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="choose-expert-box choose-extra-box-padding">
                        <h6>Choose Expert</h6>
                        <div class="choose-expert-box-wrap owl-carousel owl-theme expert_owl" id="expert-owl">
                            <label for="choose-expert-1">
                                <input type="radio" name="choose_expert" id="choose-expert-1" class="choose-expert">
                                <div class="expert-lable-div">
                                    <div class="expert-img"><img src="../assets/images/user-1.jpg" alt=""></div>
                                    <p>Michel Doe</p>
                                    <span><i class="far fa-check"></i></span>
                                </div>
                            </label>
                            <label for="choose-expert-2">
                                <input type="radio" name="choose-expert" id="choose-expert-2">
                                <div class="expert-lable-div">
                                    <div class="expert-img"><img src="../assets/images/user-1.jpg" alt=""></div>
                                    <p>Elizabeth Lisa</p>
                                    <span><i class="far fa-check"></i></span>
                                </div>
                            </label>
                            <label for="choose-expert-3">
                                <input type="radio" name="choose-expert" id="choose-expert-3">
                                <div class="expert-lable-div">
                                    <div class="expert-img"><img src="../assets/images/user-1.jpg" alt=""></div>
                                    <p>Robert Paul</p>
                                    <span><i class="far fa-check"></i></span>
                                </div>
                            </label>
                            <label for="choose-expert-4">
                                <input type="radio" name="choose-expert" id="choose-expert-4">
                                <div class="expert-lable-div">
                                    <div class="expert-img"><img src="../assets/images/user-1.jpg" alt=""></div>
                                    <p>Robert Paul</p>
                                    <span><i class="far fa-check"></i></span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="choose-book-date-time-white">
                        <div class="custom-date-wrap">
                            <!-- <input type="text" id="datepicker" name="date"
                                   class="datepicker date_select datepicker_modal"
                                   autocomplete="off" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}"> -->
                                   <input type="text" id="datepicker" name="date" class="datepicker date_select"
                                   autocomplete="off"
                                   placeholder="Select Date" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="custom-time-wrap time_slot">
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

                    <div class="btn-book-modal2 text-center">
                        <a href="javascript:void(0)" class="btn btn-cosmetics book_appt">Book Appointment</a>
                    </div>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>

@endsection
@section('service_js')

    <script>
        $('.create-appointment').validate({ // initialize the plugin
            rules: {
                first_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                last_name: {
                    required: true,
                    minlength: 5
                },
                phone_number: {
                    required: true
                },
                choose_service: {
                    required: true
                },
                date: {
                    required: true
                },
                service_time: {
                    required: true
                },
            },
            // Specify validation error messages
            messages: {
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                email: "Please enter a valid email address"
            },
        });

        $(document).on('click', '.book_appt', function () {
            if ($('.create-appointment').valid()) {

                $('.create-appointment').submit();
            }
        });

        $(document).on('click','.viewL',function (){

            $('.viewC').removeClass('active');
            $('.viewL').addClass('active');

            var activeTab = $('.nav-link.active').data('id');

            console.log(activeTab);

            if(activeTab == 'appointment'){

                $('.appointments-details-view').css('display','block');
                $('.calander-view').css('display','none');

            }
            if(activeTab =='past'){
                $('.order-details-view').css('display','block');
                $('.calander-view-order').css('display','none');

            }

        });

        $(document).on('click','.nav-link',function (){
            $('.viewC').removeClass('active');
            $('.viewL').addClass('active');
        });

        $(document).on('click','.viewC',function (){

            $('.viewC').addClass('active');
            $('.viewL').removeClass('active');

            var calData = <?php echo json_encode($calander); ?>;
            var orderData = <?php echo json_encode($oCalander); ?>;

            var activeTab = $('.nav-link.active').data('id');

            console.log(activeTab);

            if(activeTab == 'appointment'){
                $('.appointments-details-view').css('display','none');
                $('.calander-view').css('display','block');
                appointmentCal(calData);
            }
            if(activeTab =='past'){
                $('.order-details-view').css('display','none');
                $('.calander-view-order').css('display','block');
                appointmentOrder(orderData);
            }
        });


        function appointmentCal(calData){
            $("#calendar").fullCalendar({
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "month,agendaWeek,agendaDay"
                },

                defaultView: "month",
                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                selectHelper: true,
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: calData,
                eventClick: function(calEvent, jsEvent) {
                    // Display the modal and set event values.
                    console.log(calEvent);
                    $(".calcView").modal("show");
                    $(".calcView")
                        .find("#title")
                        .val(calEvent.title);
                    $(".calcView")
                        .find("#starts-at")
                        .val(calEvent.start);
                    $(".calcView")
                        .find("#ends-at")
                        .val(calEvent.end);
                }
            });
        }
        function appointmentOrder(orderData){
            $("#calendar-order").fullCalendar({
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "month,agendaWeek,agendaDay"
                },

                defaultView: "month",
                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                selectHelper: true,
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: orderData,
                eventClick: function(calEvent, jsEvent) {
                    // Display the modal and set event values.
                    console.log(calEvent);
                    $(".order_calc").modal("show");
                    $(".order_calc")
                        .find("#title")
                        .val(calEvent.title);
                    $(".order_calc")
                        .find("#starts-at")
                        .val(calEvent.start);
                    $(".order_calc")
                        .find("#ends-at")
                        .val(calEvent.end);
                }
            });
        }
    </script>
    <script>
        $('.datepicker').datepicker({
            startDate: new Date(),
            inline: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
            min: 0
        });
        $('.datepicker_postpond').datepicker({
            startDate: new Date(),
            inline: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
            min: 0
        });
        $(document).on('click', '.choose-service', function () {
            var value = $('.choose-service:checked').val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('get-employee-list')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service: value
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var html = '';
                    if (status == 'true') {

                        $(response.data).each(function (i, item) {
                            html += ' <div class="item"><label for="choose-expert-' + item.id + '">' +
                                '<input type="radio" name="choose_expert" id="choose-expert-' + item.id + '" value="' + item.id + '" class="choose-expert">' +
                                '<div class="expert-lable-div">' +
                                '<div class="expert-img"><img src="' + item.image + '" alt=""></div>' +
                                '<p>' + item.emp_name + '</p>' +
                                '<span><i class="far fa-check"></i></span>' +
                                '</div>' +
                                '</label> </div>';
                        });

                        $('.expert_owl').html(html);
                        $('#expert-owl').trigger('destroy.owl.carousel');
                        $('#expert-owl').owlCarousel({
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

                        $('.expert_owl').html(html);
                        $('.expert_owl').owlCarousel('refresh');
                    }

                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        });

        $(document).on('change', '.datepicker', function () {
            var service = $('.choose-service:checked').val();
            var date = $(this).val();
            var emp = $('.choose-expert:checked').val();

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
                                        '" class="custome_time" value="' + item.time + '" disabled>\n' +
                                        ' <p>' + item.time + '</p>\n' +
                                        ' </label>';
                                }
                            } else {
                                html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                            }


                        });
                        $('.time_slot').html(html);
                    } else {

                        html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                        $('.time_slot').html(html);
                    }
                    $('#loading').css('display', 'none');
                },
                error: function (e) {

                }
            });
        });


        $(document).on('click', '.cancel_appointment', function () {
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/get-appointment-data')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    appointment_data: id
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    var html = '';
                    if (status == 'true') {
                        $('.service_image_c').attr('src', data.service_image);
                        $('.service_name_c').text(data.service_name);
                        $('.order_number_c').text('#' + data.order_id);
                        $('.service_export_c').text(data.emp_name);
                        $('.user_id_c').val(data.user_id);
                        $('.appointment_id_c').val(data.id);
                        $('.service_id_c').val(data.service_id);
                        $('.emp_id_c').val(data.store_emp_id);
                    }
                    $('#cancel-appointment-modal').modal('toggle');
                },
                error: function (e) {

                }
            });

        });

        $(document).on('click', '.postpond_appointment', function () {
            var id = $(this).data('id');
            html = '<label class="custom-time-label">No Time Slot Avialble</label>';
            $('.postpond_time').html(html);

            $('.datepicker_postpond').datepicker('setDate', null);
            getServiceData(id);
            $('#reschedule-appointment-modal').modal('toggle');
        });

        function getServiceData(id) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/get-appointment-data')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    appointment_data: id
                },
                beforesend: $('#loading').css('display', 'block'),
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    var html = '';
                    if (status == 'true') {
                        $('.service_image').attr('src', data.service_image);
                        $('.service_name').text(data.service_name);
                        $('.order_number').text('#' + data.order_id);
                        $('.service_export').text(data.emp_name);
                        $('.user_name').val(data.user_name);
                        $('.user_id').val(data.user_id);
                        $('.appointment_id').val(data.id);
                        $('.service_id').val(data.service_id);
                        $('.emp_id').val(data.store_emp_id);
                    }
                    $('#loading').css('display', 'none');

                },
                error: function (e) {

                }
            });
        }

        $(document).on('change', '.datepicker_postpond', function () {
            var service = $('.service_id').val();
            var date = $('.datepicker_postpond').val();
            var emp = $('.emp_id').val();
            if (service != '' && date != '' && emp != '') {
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
                            $(response.data).each(function (i, item) {
                                if (item.time !== '00:00') {
                                    if (item.flag == 'Available') {
                                        html += ' <label class="custom-time-label" for="time-' + i + '">\n' +
                                            ' <input type="radio" name="service_time" id="time-' + i +
                                            '" class="custome_timea" value="' + item.time + '">\n' +
                                            ' <p>' + item.time + '</p>\n' +
                                            ' </label>';
                                    } else {
                                        html += ' <label class="custom-time-label disable" for="time-' + i +
                                            '">\n' +
                                            ' <input type="radio" name="service_time" id="time-' + i +
                                            '" class="custome_timea" value="' + item.time + '" disabled>\n' +
                                            ' <p>' + item.time + '</p>\n' +
                                            ' </label>';
                                    }
                                } else {
                                    html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                                }


                            });
                            $('.postpond_time').html(html);
                        } else {

                            html = '<label class="custom-time-label">No Time Slot Avialble</label>';
                            $('.postpond_time').html(html);
                        }
                        $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }

        });

        $(document).on('click', '.pst_appo', function () {
            var date = $('.datepicker_postpond').val();
            var time = $('.custome_timea:checked').val();
            var emp_id = $('.emp_id').val();
            var service_id = $('.service_id').val();
            var appointment_id = $('.appointment_id').val();

            if (date != '' && time != '' && emp_id != '' && service_id != '' && appointment_id != '') {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('service-provider/postpond-appointment')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        service_id: service_id,
                        date: date,
                        emp_id: emp_id,
                        time: time,
                        appointment_id: appointment_id,
                    },
                    beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var html = '';
                        if (status == 'true') {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                        $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }
        });

        $(document).on('click', '.cancel_appt', function () {
            var reason = $('.reason').val();

            if (reason != null) {
                $('.cancel_appointment').submit();
            }
        });
    </script>
    <script>
        $(document).on('click', '.search', function () {
            var search = $('.search_word').val();

            if (search != null) {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('service-provider/search-appointment')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        search: search,
                    },
                    // beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var data = response.data;
                        var html = '';
                        if (status == 'true') {
                            if (data.length > 0) {
                                $(data).each(function (i, item) {
                                    html += ' <div class="appointments-item-wrap">\n' +
                                        '    <div class="appointments-profile">\n' +
                                        '         <img src="' + item.service_image + '" alt="">\n' +
                                        '    </div>\n' +
                                        '    <div class="appointments-main-info">\n' +
                                        '        <div class="appointments-sub-wrap">\n' +
                                        '            <div class="appointments-info">\n' +
                                        '                <h5>' + item.service_name + '</h5>\n' +
                                        '                <h6>' + item.categoryName + '</h6>\n' +
                                        '                <ul>\n' +
                                        '                    <li>\n' +
                                        '                        <p>Appointment Date : <span>' + item.appo_date + ', ' + item.appo_time + '</span></p>\n' +
                                        '                    </li>\n' +
                                        '                    <li>\n' +
                                        '                        <p>Expert : <span>' + item.emp_name + '</span></p>\n' +
                                        '                    </li>\n' +
                                        '                </ul>\n' +
                                        '                <h6 class="mt-1">Store Name :  <span>' + item.store_name + '</span></h6>\n' +
                                        '            </div>\n' +
                                        '            <p class="appointments-status">Status : <span>' + item.status + '</span></p>';
                                    if (item.status == 'booked' || item.status == 'reschedule') {
                                        html += '<div class="appointments-action">\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-black cancel_appointment"\n' +
                                            '                        data-id="' + item.id + '">Cancel Appointment</a>\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-gray postpond_appointment"\n' +
                                            '                        data-id="' + item.id + '">Postpone Appointment</a>\n' +
                                            '                </div>';
                                    } else if (item.status == 'running') {
                                        html += '<div class="appointments-action">\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-black complete_appointment"\n' +
                                            '                        data-id="' + item.id + '">Complete Appointment</a>\n' +
                                            '                </div>';
                                    }
                                    html += ' </div>\n' +
                                        '        <div class="appointments-sub-wrap2">\n' +
                                        '            <p>Order ID : <span>#' + item.order_id + '</span></p>\n' +
                                        '            <p>Total Paid : <span>' + item.price + '€</span></p>\n' +
                                        '        </div>';
                                    if (item.user_id != '' && item.user_id != null) {
                                        html += '<div class="appointments-sub-wrap2">\n' +
                                            '            <p>User Name: <span>' + item.ufname + ' ' + item.ulname + '</span></p>\n' +
                                            '            <p>Payment Method : <span>' + item.payment_method + '</span></p>\n' +
                                            '        </div>';
                                    } else {
                                        html += '<div class="appointments-sub-wrap2">\n' +
                                            '            <p>User Name: <span>' + item.first_name + ' ' + item.last_name + '</span></p>\n' +
                                            '            <p>Payment Method : <span>' + item.payment_method + '</span></p>\n' +
                                            '        </div>';
                                    }
                                    html += '    </div>\n' +
                                        '</div>';
                                });
                            } else {
                                html += ' <div style="text-align: center;margin-top: 50px;">No Appointment Found.</div>';
                            }


                            $('.app_datas').html(html);
                        }
                        // $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }
        });
        $(document).on('click', '.search_recent', function () {
            var search = $('.search_word_recent').val();

            if (search != null) {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('service-provider/search-appointment-recent')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        search: search,
                    },
                    // beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var data = response.data;
                        var html = '';
                        if (status == 'true') {
                            if (data.length > 0) {
                                $(data).each(function (i, item) {
                                    html += ' <div class="appointments-item-wrap">\n' +
                                        '    <div class="appointments-profile">\n' +
                                        '         <img src="' + item.service_image + '" alt="">\n' +
                                        '    </div>\n' +
                                        '    <div class="appointments-main-info">\n' +
                                        '        <div class="appointments-sub-wrap">\n' +
                                        '            <div class="appointments-info">\n' +
                                        '                <h5>' + item.service_name + '</h5>\n' +
                                        '                <h6>' + item.categoryName + '</h6>\n' +
                                        '                <ul>\n' +
                                        '                    <li>\n' +
                                        '                        <p>Appointment Date : <span>' + item.appo_date + ', ' + item.appo_time + '</span></p>\n' +
                                        '                    </li>\n' +
                                        '                    <li>\n' +
                                        '                        <p>Expert : <span>' + item.emp_name + '</span></p>\n' +
                                        '                    </li>\n' +
                                        '                </ul>\n' +
                                        '                <h6 class="mt-1">Store Name :  <span>' + item.store_name + '</span></h6>\n' +
                                        '            </div>\n' +
                                        '            <p class="appointments-status">Status : <span>' + item.status + '</span></p>';
                                    if (item.status == 'booked' || item.status == 'reschedule') {
                                        html += '<div class="appointments-action">\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-black cancel_appointment"\n' +
                                            '                        data-id="' + item.id + '">Cancel Appointment</a>\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-gray postpond_appointment"\n' +
                                            '                        data-id="' + item.id + '">Postpone Appointment</a>\n' +
                                            '                </div>';
                                    } else if (item.status == 'running') {
                                        html += '<div class="appointments-action">\n' +
                                            '                    <a href="javascript:void(0)" class="btn btn-black complete_appointment"\n' +
                                            '                        data-id="' + item.id + '">Complete Appointment</a>\n' +
                                            '                </div>';
                                    }
                                    html += ' </div>\n' +
                                        '        <div class="appointments-sub-wrap2">\n' +
                                        '            <p>Order ID : <span>#' + item.order_id + '</span></p>\n' +
                                        '            <p>Total Paid : <span>' + item.price + '€</span></p>\n' +
                                        '        </div>';
                                    if (item.user_id != '' && item.user_id != null) {
                                        html += '<div class="appointments-sub-wrap2">\n' +
                                            '            <p>User Name: <span>' + item.ufname + ' ' + item.ulname + '</span></p>\n' +
                                            '            <p>Payment Method : <span>' + item.payment_method + '</span></p>\n' +
                                            '        </div>';
                                    } else {
                                        html += '<div class="appointments-sub-wrap2">\n' +
                                            '            <p>User Name: <span>' + item.first_name + ' ' + item.last_name + '</span></p>\n' +
                                            '            <p>Payment Method : <span>' + item.payment_method + '</span></p>\n' +
                                            '        </div>';
                                    }
                                    html += '    </div>\n' +
                                        '</div>';
                                });
                            } else {
                                html += ' <div style="text-align: center;margin-top: 50px;">No Appointment Found.</div>';
                            }


                            $('.search_rece').html(html);
                        }
                        // $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }
        });

    </script>
@endsection
