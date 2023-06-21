@extends('layouts.admin')
@section('title')
    Create Store Profile
@endsection
@section('css')
    <link href="{{URL::to('storage/app/public/Adminassets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('storage/app/public/Adminassets/libs/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{URL::to('storage/app/public/Adminassets/libs/multiselect/multi-select.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet" type="text/css"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
    <style>
        .chosen-container-multi .chosen-choices:focus{
            box-shadow:none
        }
        .chosen-container-multi .chosen-choices{
            display: block;
            width: 100%;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            box-shadow:none !important;
    </style>

@endsection
@section('content')
    <div class="row page-title">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="float-right mt-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin/store-profile')}}">Store Profile</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create Store Profile</li>
                </ol>
            </nav>
            <h4 class="mb-1 mt-0">Create Store Profile</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Create Store Profile</h4>

                    <hr/>

                    {{Form::open(array('url'=>'master-admin/store-profile','method'=>'post','name'=>'create-store-profile','files'=>'true','class'=>'needs-validation','novalidate'))}}
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3 old_user">
                                <label for="validationCustom01">User</label>
                                {{Form::select('user_id',$user,'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="new_user" class="custom-control-input checkmark new_user"
                                       data-id="new_user" id="invalidCheck">
                                <label class="custom-control-label" for="invalidCheck">Create New User</label>

                            </div>
                        </div>
                        <div class="col-lg-4 new_user_field">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">First Name <span class="name_value" style="display: none;">(optional)</span></label>
                                {{Form::text('first_name','',array('class'=>'form-control name','id'=>'validationCustom01','placeholder'=>'First Name'))}}

                            </div>
                        </div>
                        <div class="col-lg-4 new_user_field">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Last Name <span class="name_value" style="display: none;">(optional)</span></label>
                                {{Form::text('last_name','',array('class'=>'form-control name','id'=>'validationCustom01','placeholder'=>'Last Name'))}}

                            </div>
                        </div>

                        <div class="col-lg-4 new_user_field">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Email</label>
                                {{Form::text('email','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Email'))}}

                            </div>
                        </div>
                        <div class="col-lg-4 new_user_field new_phone">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Phone Number (optional)</label>
                                {{Form::text('phone_number','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Phone Number'))}}

                            </div>
                        </div>
                        <div class="col-lg-4 new_user_field">

                            <div class="form-group mb-3">
                                <label for="validationCustom01">Password</label>
                                {{Form::password('password',array('class'=>'form-control','id'=>'validationCustom04','placeholder'=>'password'))}}

                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Name</label>
                                {{Form::text('store_name','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Store Name','required'))}}
                                @error('store_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Contact Number (optional)</label>
                                {{Form::text('store_contact_number','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Store Contact Number'))}}
                                @error('store_contact_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Category</label>
                                {{Form::select('category_id',array(''=>'Select Category','Gastronomy'=>'Gastronomy','Cosmetics'=>"Cosmetics"),'',array('class'=>'form-control category','id'=>'validationCustom01','required','data-plugin'=>"customselect"))}}
                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Sub Category (optional)</label>
                                <div id="output"></div>
                                {{Form::select('category[]',$category,'',array('class'=>'form-control subcategory chosen-select','id'=>'validationCustom01','required','multiple','data-plugin'=>"customselect"))}}
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Subscription Plan</label>
                                {{Form::select('store_active_actual_plan',$plan,'',array('class'=>'form-control plan','id'=>'validationCustom01','required'))}}
                                @error('store_active_actual_plan')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dbusiness">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Description</label>
                                {{Form::textarea('store_description','',array('class'=>'form-control','id'=>'summernote','placeholder'=>'Store Description','rows'=>2))}}
                                @error('store_description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dbusiness">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Link Id (optional)</label>
                                {{Form::text('store_link_id','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Store Link Id'))}}
                                @error('store_link_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-lg-4 dbusiness">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Value (optional)</label>
                                {{Form::select('is_value',array(''=>'Select Store Value','€'=>"€",'€€'=>"€€",'€€€'=>"€€€",'€€€€'=>'€€€€','€€€€€'=>'€€€€€'),'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('store_link_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dbusiness">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Is Recommanded</label>
                                {{Form::select('is_recommended',array('no'=>"No",'yes'=>"Yes"),'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('is_recommended')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <h6>Other Details</h6>
                    <hr/>

                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Address</label>
                                {{Form::textarea('store_address','',array('class'=>'form-control ','id'=>'autocomplete','placeholder'=>'Store Address','required','rows'=>2))}}
                                @error('store_address')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                {{Form::hidden('latitude','',array('id'=>'latitude'))}}
                                {{Form::hidden('longitude','',array('id'=>'longitude'))}}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Zipcode</label>
                                {{Form::text('zipcode','',array('class'=>'form-control zipcodes','id'=>'validationCustom03','placeholder'=>'Zipcode','required'))}}
                                @error('zipcode')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Distinct</label>
                                {{Form::text('store_district','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Store Distinct','required'))}}
                                @error('store_district')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4 dspecial">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Cancellation Deadline (optional)</label>
                                {{Form::number('cancellation_deadline','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Cancellation Deadline','min'=>0))}}
                                @error('cancellation_deadline')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dspecial">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Cancellation Deadline Day/Hours/Minute (optional)</label>
                                {{Form::select('cancellation_day',array(''=>'Select Cancellation Time','day'=>'Day','hours'=>'Hours','minutes'=>'Minutes'),'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('cancellation_deadline')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dspecial">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Payment Method (optional)</label>
                                {{Form::select('payment_method',array(''=>"Select Payment Method",'cash'=>"Cash",'card'=>"Card",'both'=>"Cash & Card Both"),'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('payment_method')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Store Time Start --}}
                    <h6>Store Time</h6>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <p class="text-center">Day</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <p class="text-center">Start Time</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <p class="text-center">End Time</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Monday</h6>
                                {{Form::hidden('day[]','Monday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                {{--  {{Form::time('start_time[]','09:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker1" data-toggle="datetimepicker" value="09:00" data-id="Monday"
                                       data-target="#datetimepicker1"/>
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">

                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker2" data-toggle="datetimepicker" value="20:00" data-id="Monday"
                                       data-target="#datetimepicker2"/>
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Monday" id="invalidCheckMonday">
                                <label class="custom-control-label" for="invalidCheckMonday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Tuesday</h6>
                                {{Form::hidden('day[]','Tuesday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                {{--                                {{Form::time('start_time[]','09:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}

                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker15" data-toggle="datetimepicker" value="10:00"
                                       data-id="Tuesday"
                                       data-target="#datetimepicker15"/>
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                {{--                                {{Form::time('end_time[]','20:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker4" data-toggle="datetimepicker" value="20:00" data-id="Tuesday"
                                       data-target="#datetimepicker4"/>
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Tuesday" id="invalidCheckTuesday">
                                <label class="custom-control-label" for="invalidCheckTuesday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Wednesday</h6>
                                {{Form::hidden('day[]','Wednesday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker5" data-toggle="datetimepicker" value="10:00"
                                       data-id="Wednesday"
                                       data-target="#datetimepicker5"/>
                                {{--                                {{Form::time('start_time[]','09:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker6" data-toggle="datetimepicker" value="20:00"
                                       data-id="Wednesday"
                                       data-target="#datetimepicker6"/>
                                {{--                                {{Form::time('end_time[]','20:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Wednesday" id="invalidCheckWednesday">
                                <label class="custom-control-label" for="invalidCheckWednesday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Thursday</h6>
                                {{Form::hidden('day[]','Thursday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker7" data-toggle="datetimepicker" value="10:00"
                                       data-id="Thursday"
                                       data-target="#datetimepicker7"/>
                                {{--                                {{Form::time('start_time[]','09:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker8" data-toggle="datetimepicker" value="20:00"
                                       data-id="Thursday"
                                       data-target="#datetimepicker8"/>
                                {{--                                {{Form::time('end_time[]','20:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Thursday" id="invalidCheckThursday">
                                <label class="custom-control-label" for="invalidCheckThursday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Friday</h6>
                                {{Form::hidden('day[]','Friday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker9" data-toggle="datetimepicker" data-id="Friday" readonly
                                       data-target="#datetimepicker9"/>
                                {{--                                {{Form::time('start_time[]','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker10" data-toggle="datetimepicker" data-id="Friday" readonly
                                       data-target="#datetimepicker10"/>
                                {{--                                {{Form::time('end_time[]','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Friday" id="invalidCheckFriday" checked>
                                <label class="custom-control-label" for="invalidCheckFriday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Saturday</h6>
                                {{Form::hidden('day[]','Saturday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker11" data-toggle="datetimepicker" value="10:00"
                                       data-id="Saturday"
                                       data-target="#datetimepicker11"/>
                                {{--                                {{Form::time('start_time[]','09:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker12" data-toggle="datetimepicker" value="20:00"
                                       data-id="Saturday"
                                       data-target="#datetimepicker12"/>
                                {{--                                {{Form::time('end_time[]','20:00',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Saturday" id="invalidCheckSaturday">
                                <label class="custom-control-label" for="invalidCheckSaturday">Is Holiday</label>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <h6 class="text-center">Sunday</h6>
                                {{Form::hidden('day[]','Sunday')}}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input start_time"
                                       name="start_time[]"
                                       placeholder="Start Time"
                                       id="datetimepicker13" data-toggle="datetimepicker" data-id="Sunday" readonly
                                       data-target="#datetimepicker13"/>
                                {{--                                {{Form::time('start_time[]','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'Start Time'))}}--}}
                                @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control datetimepicker-input end_time" name="end_time[]"
                                       placeholder="End Time"
                                       id="datetimepicker14" data-toggle="datetimepicker" data-id="Sunday" readonly
                                       data-target="#datetimepicker14"/>
                                {{--                                {{Form::time('end_time[]','',array('class'=>'form-control','id'=>'validationCustom01','placeholder'=>'End Time'))}}--}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="custom-control custom-checkbox form-check">
                                <input type="checkbox" name="is_holiday[]" class="custom-control-input checkmark"
                                       data-id="Sunday" id="invalidCheckSunday" checked>
                                <label class="custom-control-label" for="invalidCheckSunday">Is Holiday</label>

                            </div>
                        </div>
                    </div>

                    {{-- Store Time End --}}

                    <h6 class="dbusiness">Store Images </h6>
                    <hr class="dbusiness"/>
                    <div class="row mb-3">
                        <div class="col-sm-4 dbusiness">
                            <label>Store Profile (optional)</label>
                            <div class="custom-file">
                                {{Form::file('store_profile',array('class'=>$errors->has('store_profile') ?'custom-file-input is-invalid' : 'custom-file-input','id'=>'customFile','accept'=>"image/*"))}}
                                <label class="custom-file-label store_profile_label" for="customFile">Choose
                                    file</label>

                            </div>
                            @error('store_profile')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-sm-4 dbusiness">
                            <label>Store banner (optional)</label>
                            <div class="custom-file">
                                {{Form::file('store_banner',array('class'=>$errors->has('store_banner') ?'custom-file-input is-invalid' : 'custom-file-input','id'=>'customFile','accept'=>"image/*"))}}
                                <label class="custom-file-label store_banner_label" for="customFile">Choose file</label>

                            </div>
                            @error('store_banner')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-sm-4 dbusiness">
                            <label>Store Gallery (Select Multiple images at a time) (optional)</label>
                            <div class="custom-file">
                                {{Form::file('store_gallery[]',array('class'=>$errors->has('store_gallery') ?'custom-file-input is-invalid' : 'custom-file-input','id'=>'customFile','accept'=>"image/*",'multiple'))}}
                                <label class="custom-file-label store_gallery_label" for="customFile">Choose
                                    file</label>

                            </div>
                            @error('store_banner')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                    </div>


                    {{Form::submit('Submit',array('class'=>'btn btn-primary'))}}
                    <a href="{{URL::to('master-admin/store-profile')}}" class="btn btn-danger">Cancel</a>
                    {{Form::close()}}

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
@endsection

@section('plugin')
    <!-- Plugin js-->
    <script src="{{URL::to('storage/app/public/Adminassets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script
        src="{{URL::to('storage/app/public/Adminassets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/multiselect/jquery.multi-select.js')}}"></script>
    {{--    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection
@section('js')
    <!-- Validation init js-->
    <script src="{{URL::to('storage/app/public/Adminassets/js/pages/form-validation.init.js')}}"></script>
    <script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
    <script
        src="https://maps.google.com/maps/api/js?key=AIzaSyBSItHxCbk9qBcXp1XTysVLYcJick5K8mU&libraries=places&callback=initialize"
        type="text/javascript"></script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var options = {
                componentRestrictions: {country: 'de'}
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
                getZipcode(place.geometry['location'].lat(), place.geometry['location'].lng())
            });

        }

        function getZipcode(latitude, logitude) {
            var latlng = new google.maps.LatLng(latitude, logitude);
            geocoder = new google.maps.Geocoder();

            geocoder.geocode({'latLng': latlng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        for (j = 0; j < results[0].address_components.length; j++) {
                            if (results[0].address_components[j].types[0] == 'postal_code')
                                $('.zipcodes').val(results[0].address_components[j].short_name);
                        }
                    }
                } else {
                    alert("Geocoder failed due to: " + status);
                }
            });
        }

        document.getElementById('output').innerHTML = location.search;
        $(".chosen-select").chosen();


    </script>
    <script>
        $(document).ready(function () {
            $('.new_user_field').css('display', 'none');
            $('#datetimepicker3').datetimepicker({
                format: 'HH:mm'
            });

            $('#summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

        })

        $(function () {
            $('#datetimepicker1').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker1').datetimepicker({
                format: 'HH:mm'
            });

            $('#datetimepicker2').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker2').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker3').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker3').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker4').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker4').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker5').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker5').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker6').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker6').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker7').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker7').datetimepicker({
                format: 'HH:mm'
            });

            $('#datetimepicker8').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker8').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker9').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker9').datetimepicker({
                format: 'HH:mm'
            });

            $('#datetimepicker10').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker10').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker10').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker10').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker11').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker11').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker12').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker12').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker13').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker13').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker14').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker14').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker15').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker15').datetimepicker({
                format: 'HH:mm'
            });

            $("#datetimepicker1").val("09:00");
            $("#datetimepicker2").val("20:00");
            $("#datetimepicker15").val("10:00");
            $("#datetimepicker4").val("20:00");
            $("#datetimepicker5").val("10:00");
            $("#datetimepicker6").val("20:00");
            $("#datetimepicker7").val("10:00");
            $("#datetimepicker8").val("20:00");
            $("#datetimepicker11").val("10:00");
            $("#datetimepicker12").val("20:00");

        });


        $('input[name="store_profile"]').change(function (e) {

            var fileName = e.target.files[0].name;
            $('.store_profile_label').text(fileName);

        });
        $('input[name="store_banner"]').change(function (e) {

            var fileName = e.target.files[0].name;
            $('.store_banner_label').text(fileName);

        });
        $('input[name="store_gallery[]"]').change(function (e) {

            var fileName = e.target.files.length + ' Files';
            $('.store_gallery_label').text(fileName);

        });


        $(document).on('click', '.new_user', function () {
            if ($(this).prop('checked') == true) {
                $('.new_user_field').css('display', 'block');
                // $('.old_user').css('display','none');
                $('.new_user_field input').prop('required', true);
                $('.new_phone input').prop('required', false);
            } else {
                $('.new_user_field').css('display', 'none');
                // $('.old_user').css('display','block');
                $('.new_user_field input').prop('required', false);
            }

        });

        $(document).on('change', '.category', function () {
            var value = $(this).val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('master-admin/store-profile/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    var html = '';

                    $(response.data).each(function (index, value) {

                        html += '<option value="' + value.id + '">' + value.name + '</option>';
                    });

                    if (response.data.length > 0) {
                        $('.subcategory').prop('required', true);
                    }

                    $('.subcategory').html(html);
                    $("select.subcategory").trigger("chosen:updated");

                },
                error: function (e) {

                }
            });
        });

        $(document).on('click', '.checkmark', function () {
            var id = $(this).data('id');

            if ($(this).prop('checked') == true) {
                $('.start_time[data-id=' + id).val('');
                $('.end_time[data-id=' + id).val('');
                $('.start_time[data-id=' + id).attr('readonly', true);
                $('.end_time[data-id=' + id).attr('readonly', true);
            } else {
                $('.start_time[data-id=' + id).val('10:00');
                $('.end_time[data-id=' + id).val('20:00');
                $('.start_time[data-id=' + id).attr('readonly', false);
                $('.end_time[data-id=' + id).attr('readonly', false);
            }

        });

        $(document).on('change', '.plan', function () {
            var value = $(this).val();
            if (value == 1) {
                $('.dbusiness').css('display', 'none');
                $('.dspecial').css('display', 'none');
                $('.name').prop('required', false);
                $('.name_value').css('display','contents');
                
            } else if (value == 2 || value == 3) {
                $('.dbusiness').css('display', 'none');
                $('.dbusiness').css('display', 'block');
                $('.dspecial').css('display', 'none');
                $('.name_value').css('display','none');
                $('.name').prop('required', true);
            } else if (value == 4 || value == 5) {
                $('.dbusiness').css('display', 'block');
                $('.dspecial').css('display', 'block');
                $('.name_value').css('display','none');
                $('.name').prop('required', true);
            }
        });

    </script>

@endsection

