@extends('layouts.front')
@section('front_title')
User Profile
@endsection
@section('front_css')
<style>
.user-profile-info {
    text-align: center;
    margin: 20px auto;
}

.user-profile-info h6 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #0c0c0c;
}

.user-profile-info p {
    font-size: 13px;
    color: #343434;
    font-weight: 600;
    opacity: 0.70;
}

.cancle-text-box {
    margin-bottom: 20px;
}

.cancle-text-box textarea {
    box-shadow: -1.045px 9.945px 32px 0px rgba(212, 212, 212, 0.16);
    border-radius: 15px;
    background: #ffff;
    border: none;
    width: 100%;
    padding: 15px;
    font-size: 16px;
    resize: none;
}
</style>
@endsection
@section('front_content')
<span class="d-margin"></span>
<div class="container">
    {{Form::open(array('url'=>'change-profile','method'=>'post','name'=>'change_profile','id'=>"change_profile",'files'=>'true'))}}
    <div class="avatar-upload-wrap">
        <div class="avatar-upload">
            <div class="avatar-edit">
                <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="profile_image"
                    class="profile_image" />
                <label for="imageUpload">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <div class="avatar-preview">
                @if(!empty(Auth::user()->profile_pic))
                <div id="imagePreview"
                    style="background-image: url({{URL::to('storage/app/public/user/'.Auth::user()->profile_pic)}});">
                </div>
                @else
                <div id="imagePreview"
                    style="background-image: url({{URL::to('storage/app/public/default/default-user.png')}});"></div>
                @endif
            </div>
        </div>
        <div class="avatar-upload-info">
            <h6>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h6>
            <a href="#">{{Auth::user()->email}}</a>
        </div>
    </div>
    {{Form::close()}}
    <div class="booked-box">
        <div class="row">
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills booked-pills" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-order-tab" data-toggle="pill" href="#v-pills-order"
                        role="tab" aria-controls="v-pills-order" aria-selected="true">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/handcart.svg')) ?></span>
                        <i>My Order </i>
                    </a>
                    <a class="nav-link" id="v-pills-favourites-tab" data-toggle="pill" href="#v-pills-favourites"
                        role="tab" aria-controls="v-pills-favourites" aria-selected="false">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/heart.svg')) ?></span>
                        <i>My Favourites</i>
                    </a>
                    <a class="nav-link" id="v-pills-addresses-tab" data-toggle="pill" href="#v-pills-addresses"
                        role="tab" aria-controls="v-pills-addresses" aria-selected="false">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/region.svg')) ?></span>
                        <i>Addresses</i>
                    </a>
                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab"
                        aria-controls="v-pills-settings" aria-selected="false">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/user.svg')) ?></span>
                        <i>Edit Profile</i>
                    </a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-order" role="tabpanel"
                        aria-labelledby="v-pills-order-tab">
                        <div class="order-heading-wrap">
                            <h5>Upcoming Order</h5>
                            <div class="d-flex flex-wrap">
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
                        </div>
                        <div class="appointments-details-view">
                            @forelse($upcommingAppointment as $row)
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
                                                    <p>Order ID : <span>#{{$row->order_id}}</span></p>
                                                </li>
                                                <li>
                                                    <p>Store Name : <span>{{@$row->storeDetails->store_name}}</span></p>
                                                </li>
                                                <li>
                                                    <p>Employee Name : <span>{{@$row->empName}}</span></p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="appointments-action ml-auto">
                                            <p><i class="far fa-calendar-alt"></i> <span>
                                                    {{\Carbon\Carbon::parse($row->appo_date)->format('d M, Y')}},
                                                    {{\Carbon\Carbon::parse($row->appo_time)->format('H:i')}}</span>
                                            </p>
                                            @if($row->status != 'cancel')
                                            @if($row->isAPP =='yes')
                                            <a href="javascript:void(0)" class="btn btn-gray cancel_appointment"
                                                data-id="{{$row->id}}">Cancel
                                                Appointment?</a>
                                            @else
                                            <p><span> Cancellation time is over</span></p>
                                            @endif
                                            @else
                                            <p><span>Appointment Cancelled</span></p>
                                            @endif
                                            <p><span>Time : {{$row->serviceTime}} Min</span></p>
                                            <p><span>Status : {{$row->status}}</span></p>
                                        </div>
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>Payment Method : <span>{{$row->payment_method != '' ?  $row->payment_method :'-'}}</span></p>
                                        @if($row->status != 'cancel')
                                        <p>Total Paid : <span>{{$row->price}}€</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center">No Upcoming Order Found.</div>
                            @endforelse
                        </div>
                        <div class="appointments-details-view mt-5">
                            <h5 class="page-title">Recent Order</h5>
                            @forelse($pastAppointment as $row)
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
                                                    <p>Order ID : <span>#{{$row->order_id}}</span></p>
                                                </li>
                                                <li>
                                                    <p>Store Name : <span>{{@$row->storeDetails->store_name}}</span></p>
                                                </li>
                                                <li>
                                                    <p>Employee Name : <span>{{@$row->empName}}</span></p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="appointments-action ml-auto">
                                            <p><i class="far fa-calendar-alt"></i> <span>
                                                    {{\Carbon\Carbon::parse($row->appo_date)->format('d M, Y')}},
                                                    {{\Carbon\Carbon::parse($row->appo_time)->format('H:i')}}</span>
                                            </p>
                                            <p><span>Time : {{$row->serviceTime}} Min</span></p>
                                            <p><span>Status : {{$row->status}}</span></p>
                                        </div>
                                    </div>
                                    <div class="appointments-sub-wrap2">
                                        <p>Payment Method : <span>{{$row->payment_method != '' ?  $row->payment_method :'-'}}</span></p>
                                        <p>Total Paid : <span>{{$row->price}}€</span></p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center">No Recent Order Found.</div>
                            @endforelse
                            @if(count($pastAppointment)> 5)
                            <a href="javascript:void(0)" class="show-more-order">Show More Order</a>
                            @endif
                        </div>
                        <div class="calander-view" style="display: none">
                            <div id='calendar'></div>
{{--                            <div id='datepicker'></div>--}}
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
                                                <p class="appointment-modal-status">Status : <span>Running</span>
                                                </p>
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
                                                <div class="booked-modal-info-wrap">
                                                    <h5>TOTAL BILL PAID <span>(Via Credit card)</span></h5>
                                                    <h4>$85</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-favourites" role="tabpanel"
                        aria-labelledby="v-pills-favourites-tab">
                        <div class="order-heading-wrap">
                            <h5>Favorites</h5>
{{--                            <div class="short-by-select">--}}
{{--                                <select name="" id="" class="select">--}}
{{--                                    <option value="">Short By</option>--}}
{{--                                    <option value="">A to Z</option>--}}
{{--                                    <option value="">Z to A</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
                        </div>
                        @foreach($favorites as $row)
                        <div class="cosmetics-area-item-wrap">
                            <div class="cosmetics-area-item-img">
                                <a href="{{URL::to('cosmetic/'.$row->slug)}}">
                                @if(file_exists(storage_path('app/public/store/'.$row->store_profile)) && $row->store_profile != '')
                                            <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}"
                                                 alt=""
                                                 style="object-fit:fill !important;">
                                        @else
                                            <img src="{{URL::to('storage/app/public/default/default_store.jpeg')}}"
                                                 alt=""
                                                 style="object-fit:fill !important;">
                                        @endif
                                   
                                </a>
                            </div>
                            <div class="cosmetics-area-item-info">
                                <h5>
                                    <a href="{{URL::to('cosmetic/'.$row->slug)}}"> {{$row->store_name}} </a><span><i
                                            class="fas fa-star"></i> {{$row->rating}}</span>
                                    <span><i class="fas fa-user"></i> {{$row->ratingCount}}</span>
                                </h5>
                                <p>
                                    <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?></span>
                                    {{$row->store_address}}
                                </p>
                                <p>
                                    <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/eyelash.svg')); ?></span>
                                    Cosmetic
                                </p>
                                <ul>
                                    @foreach($row->categories as $item)
                                    <li>{{@$item->CategoryData->name}}</li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="cosmetics-area-item-wishlist">
                                <a href="javascript:void(0)"
                                    class="wishlist-icon {{$row->isFavorite == 'true' ? 'active' : ''}} favorite"
                                    data-id="{{$row->id}}"><i class="far fa-heart"></i></a>
                                <p>{{$row->is_value}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade" id="v-pills-addresses" role="tabpanel"
                        aria-labelledby="v-pills-addresses-tab">
                        <h6 class="edit-profile-title">Address List</h6>
                        <div class="address-box">
                            <div class="row">
                                <div style="text-align: center">
                                    No Address Found.
                                </div>
                                {{--                                    <div class="col-lg-4">--}}
                                {{--                                        <div class="address-wrap">--}}
                                {{--                                            <div class="address-wrap-icon">--}}
                                {{--                                                <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="address-wrap-info">--}}
                                {{--                                                <h6>Work</h6>--}}
                                {{--                                                <p>Poststraße 28, 10178 Berlin, Germany</p>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-black">Edit</a>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-gray">Delete</a>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="col-lg-4">--}}
                                {{--                                        <div class="address-wrap">--}}
                                {{--                                            <div class="address-wrap-icon">--}}
                                {{--                                                <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="address-wrap-info">--}}
                                {{--                                                <h6>Work</h6>--}}
                                {{--                                                <p>Poststraße 28, 10178 Berlin, Germany</p>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-black">Edit</a>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-gray">Delete</a>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="col-lg-4">--}}
                                {{--                                        <div class="address-wrap">--}}
                                {{--                                            <div class="address-wrap-icon">--}}
                                {{--                                                <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="address-wrap-info">--}}
                                {{--                                                <h6>Work</h6>--}}
                                {{--                                                <p>Poststraße 28, 10178 Berlin, Germany</p>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-black">Edit</a>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-gray">Delete</a>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="col-lg-4">--}}
                                {{--                                        <div class="address-wrap">--}}
                                {{--                                            <div class="address-wrap-icon">--}}
                                {{--                                                <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="address-wrap-info">--}}
                                {{--                                                <h6>Work</h6>--}}
                                {{--                                                <p>Poststraße 28, 10178 Berlin, Germany</p>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-black">Edit</a>--}}
                                {{--                                                <a href="javascript:void(0)" class="btn btn-gray">Delete</a>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                            </div>
                            <a href="javascript:void(0)" class="add-address-btn"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                        aria-labelledby="v-pills-settings-tab">
                        <h6 class="edit-profile-title">Edit Profile</h6>
                        <div class="edit-profile-box">
                            {{Form::open(array('url'=>'update-profile','method'=>'post','name'=>"update_profile",'class'=>'update_profile'))}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="login-modal-input border-modal-input">
                                        <label>First Name</label>
                                        {{Form::text('first_name',Auth::user()->first_name,array('id'=>'first_name','required'))}}
                                        <span class="invalid-feedback first_name_err" role="alert"
                                            style="display: none !important;">
                                            <strong>
                                                <p class="first_name-ee">Last Name is required</p>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="login-modal-input border-modal-input">
                                        <label>Last Name</label>
                                        {{Form::text('last_name',Auth::user()->last_name,array('id'=>'last_name','required'))}}
                                        <span class="invalid-feedback last_name_err" role="alert"
                                            style="display: none !important;">
                                            <strong>
                                                <p class="last_name-ee">Last Name is required</p>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="login-modal-input border-modal-input">
                                        <label>Email Address</label>
                                        {{Form::email('email',Auth::user()->email,array('id'=>'email','required'))}}
                                        <span class="invalid-feedback mail_err" role="alert"
                                            style="display: none !important;">
                                            <strong>
                                                <p class="mail-ee">Last Name is required</p>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="login-modal-input border-modal-input">
                                        <label>Mobile Number</label>
                                        {{Form::text('phone_number',Auth::user()->phone_number,array('id'=>"phone_number",'min'=>'0'))}}
                                        <span class="invalid-feedback phone_number_err" role="alert"
                                            style="display: none !important;">
                                            <strong>
                                                <p class="phone_number-ee">Last Name is required</p>
                                            </strong>
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="text-center mt-5">
                                <button type="button" class="btn btn-black update_profile_btn">Update</button>
                            </div>
                            {{Form::close()}}
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
            <div class="modal-body modal-bg">
                {{Form::open(array('url'=>'cancel-appointment','method'=>'post','name'=>'cancel-apponintment'))}}
                <div class="user-profile-info">
                    <span class="user-profile-imgs"><img src="../assets/images/saloon-img-1.jpg" alt=""
                            class="service_image"></span>
                    <h6 class="service_name">Deniana Omul</h6>
                    <p>Order ID :<span class="service_order">#26672107231</span></p>
                    <input type="hidden" name="appointment_id" class="appointment_id">
                </div>
                <div class="cancle-text-box">
                    <textarea rows="10" placeholder="Write Reason..." name="cancel_reason"></textarea>
                </div>
                <div class="btn-book-modal text-center">
                    <button class="btn btn-gray" type="submit">Cancel Appointment</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@endsection
@section('front_js')
<script>
$("body").addClass("bg-light-white");
$("body").addClass("footer-margin");
$("body").addClass("black-body");
$("body").addClass("cosmetics-body");

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imageUpload").change(function() {
    readURL(this);
});


$(document).on('click','.viewL',function (){
    $('.appointments-details-view').css('display','block');
    $('.calander-view').css('display','none');
    $('.viewC').removeClass('active');
    $('.viewL').addClass('active');

});
$(document).on('click','.viewC',function (){
    $('.appointments-details-view').css('display','none');
    $('.calander-view').css('display','block');
    $('.viewC').addClass('active');
    $('.viewL').removeClass('active');

    var calData = <?php echo json_encode($calander); ?>;
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
            $("#save-event").hide();
            $("input").prop("readonly", true);
        }
    });


});
</script>
<script>
$('.update_profile').validate({ // initialize the plugin
    rules: {
        first_name: {
            required: true
        },
        last_name: {
            required: true
        },
        email: {
            required: true,
            email: true
        },
        phone_number: {
            minlength: 11,
            maxlength: 11,
            number:true
        }
    },
    // Specify validation error messages
    messages: {
        phone_number: {
            required: "Please provide a phone number",
        },
        email: "Please enter a valid email address",
        first_name: "Please enter a valid First Name",
        last_name: "Please enter a valid Last Name",
    },
});

$(document).on('click', '.update_profile_btn', function() {
    if ($('.update_profile').valid()) {
        var form = $('.update_profile').serialize();
        $.ajax({
            type: 'POST',
            async: true,
            dataType: "json",
            url: "{{URL::to('update-profile')}}",
            data: form,
            success: function(data) {
                var status = data.ResponseCode;
                var text = data.ResponseText;
                console.log(status);
                if (status == 'false') {
                    $('.email-ee').text(text);
                    $('.invalid-feedback.email_err').removeAttr("style");
                } else {
                    alert('profile update successfully');
                }
            },
            error: function(data) {
                var data = data.responseJSON.errors;
                console.log(data);
                $(data).each(function(index, element) {
                    if (element['first_name'] != undefined) {
                        $('.first_name_err').css('display', 'block');
                        $('.first_name-ee').text(element['first_name'][0]);
                    }
                    if (element['last_name'] != undefined) {
                        $('.last_name_err').css('display', 'block');
                        $('.last_name-ee').text(element['last_name'][0]);
                    }
                    if (element['email'] != undefined) {
                        $('.mail_err').css('display', 'block');
                        $('.mail-ee').text(element['email'][0]);
                    }
                    if (element['phone_number'] != undefined) {
                        $('.phone_number_err').css('display', 'block');
                        $('.phone_number_err-ee').text(element['phone_number'][0]);
                    }


                });
            }
        });
    }
});

$(document).on('change', '.profile_image', function() {
    $('#change_profile').submit();
})

$(document).on('click', '.favorite', function() {
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
            success: function(response) {
                var status = response.status;
                var type = response.data;
                if (status == 'true') {
                    if (type == 'remove') {
                        $('.wishlist-icon[data-id=' + id + ']').removeClass('active');

                        $('.favorite[data-id=' + id + ']').closest('.cosmetics-area-item-wrap')
                            .remove();

                    } else if (type == 'add') {
                        $('.wishlist-icon[data-id=' + id + ']').addClass('active');
                    }
                } else {

                }

            },
            error: function(e) {

            }
        });
    } else {
        $('#login-modal').modal('toggle');
    }
});
</script>
<script>
$(document).on('click', '.cancel_appointment', function() {
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        async: true,
        dataType: "json",
        url: "{{URL::to('get-appointment-data')}}",
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
        },
        success: function(response) {
            var status = response.status;
            var data = response.data;
            if (status == 'true') {
                $('.service_image').attr('src', data.service_image);
                $('.service_name').text(data.service_name);
                $('.service_order').text('#' + data.order_id);
                $('.appointment_id').val(data.id);
                $('#cancel-appointment-modal').modal('toggle');
            }
        }
    });
})
</script>
@endsection
