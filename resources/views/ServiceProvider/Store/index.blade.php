@extends('layouts.serviceProvider')
@section('service_title')
Store Profile
@endsection
@section('service_css')
{{--    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"--}}
{{--          type="text/css"/>--}}
{{--    <link--}}
{{--        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css"--}}
{{--        rel="stylesheet" type="text/css"/>--}}
{{--    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"--}}
{{--          type="text/css"/>--}}
<style>
.c-gallery-item .fas.fa-times {
    position: absolute;
    top: 0;
    right: 15px;
    background: #000;
    color: #fff;
    padding: 12px;
    border-radius: 10px 0;
    box-shadow: 0px 0px 1px 0px #757575;
}

.c-gallery-item img {
    height: 250px;
    width: 100%;
}

#gallery {
    padding: 10px 0;
}
</style>
@endsection
@section('service_content')
<div class="body-content">
    <h5 class="page-title">Store Profile</h5>
    <div class="profile-uploading">
        <div class="background-img">
            {{Form::open(array('url'=>'service-provider/update-store-banner','method'=>'post','name'=>'update-banner','id'=>'update_banner','files'=>'true'))}}
            @if(!empty($data['store_banner']))

                <span class="background-img-preview" id="imagePreviewBanner"
                      style="background-image: url({{URL::to('storage/app/public/store/banner/'.$data['store_banner'])}});"></span>
            @else
                <span class="background-img-preview" id="imagePreviewBanner"
                      style="background-image: url(http://i.pravatar.cc/500?img=7);"></span>
            @endif

            <label for="background-img" class="background-upload-btn">
                <input type="file" id="background-img" name="store_banner" accept=".png, .jpg, .jpeg">
                <span class="btn btn-white">+ Upload Banner Image</span>
            </label>
            {{Form::close()}}
        </div>
        <div class="avatar-upload avatar-upload22">
            {{Form::open(array('url'=>'service-provider/update-store-profile','method'=>'post','name'=>'update-profile','id'=>'update_profile','files'=>'true'))}}
            <div class="avatar-edit">
                <input type='file' id="imageUpload" name="store_profile" accept=".png, .jpg, .jpeg" />
                <label for="imageUpload">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <div class="avatar-preview">
                @if(!empty($data['store_profile']))
                <div id="imagePreview"
                    style="background-image: url({{URL::to('storage/app/public/store/'.$data['store_profile'])}});">
                </div>
                @else
                <div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);"></div>
                @endif
            </div>
            {{Form::close()}}
        </div>
    </div>
    <ul class="nav nav-pills online-store-nav" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-info-tab" data-toggle="pill" href="#pills-info" role="tab"
                aria-controls="pills-info" aria-selected="true">Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-gallery-tab" data-toggle="pill" href="#pills-gallery" role="tab"
                aria-controls="pills-gallery" aria-selected="false">Gallery</a>
        </li>

    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">

            {{Form::open(array('url'=>'service-provider/update-store','method'=>'post','name'=>'update-profile','class'=>"online-store-form"))}}
            <div class="row">
                <div class="col-12">
                    <div class="input-form">
                        <label>Store Name</label>
                        {{Form::text('store_name',$data['store_name'],array('placeholder'=>'Saloon Name','required','readonly'))}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-form">
                        <label>Store Description</label>
                        {{Form::textarea('store_description',$data['store_description'],array('required','rows'=>'2'))}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-form">
                        <label>Store Contact Number</label>
                        {{Form::text('store_contact_number',$data['store_contact_number'],array('placeholder'=>'Store Contact Number','required'))}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-form">
                        <label>Website URL</label>
                        {{Form::text('store_link_id',$data['store_link_id'],array('placeholder'=>'Store Link'))}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-form">
                        <label>Store Expensive</label>
                        {{Form::select('is_value',array(''=>'Select Store Value','€'=>"€",'€€'=>"€€",'€€€'=>"€€€",'€€€€'=>'€€€€','€€€€€'=>'€€€€€'),$data['is_value'],array('class'=>'select'))}}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-form">
                        <label>Store Address</label>
                        {{Form::text('store_address',$data['store_address'],array('placeholder'=>'Store Address','id'=>'autocomplete'))}}
                        {{Form::hidden('latitude',$data['latitude'],array('id'=>'latitude'))}}
                        {{Form::hidden('longitude',$data['longitude'],array('id'=>'longitude'))}}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-form">
                        <label>Zipcode</label>
                        {{Form::text('zipcode',$data['zipcode'],array('placeholder'=>'Store Zipcode'))}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-form">
                        <label>District</label>
                        {{Form::text('store_district',$data['store_district'],array('placeholder'=>'Store District'))}}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-form">
                        <h5>Select Working Hour</h5>
                       <div class="working-hour-width">
                       <div class="working-hour-bg">
                            <div class="working-wrap mb-3">
                                <p class="w-30">Day</p>
                                <p class="w-23">Stating Hour</p>
                                <p class="w-23">Ending Hour</p>
                                <p class="w-20">Holiday</p>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Monday</h6>
                                {{Form::hidden('day[]','Monday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" @if(@$storeTiming[0]['is_off']=='on' ) readonly @endif data-id="Monday"
                                        id="datetimepicker1">
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Monday" id="datetimepicker2" @if(@$storeTiming[0]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[0]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Monday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Monday">
                                    @endif
                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Tuesday</h6>
                                {{Form::hidden('day[]','Tuesday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" @if(@$storeTiming[1]['is_off']=='on' ) readonly @endif
                                        data-id="Tuesday" id="datetimepicker15">
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Tuesday" @if(@$storeTiming[1]['is_off']=='on' ) readonly @endif
                                        id="datetimepicker4">
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[1]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Tuesday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Tuesday">
                                    @endif

                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Wednesday</h6>
                                {{Form::hidden('day[]','Wednesday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" data-id="Wednesday" @if(@$storeTiming[2]['is_off']=='on' ) readonly
                                        @endif id="datetimepicker5">
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Wednesday" id="datetimepicker6" @if(@$storeTiming[2]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[2]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Wednesday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Wednesday">
                                    @endif
                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Thursday</h6>
                                {{Form::hidden('day[]','Thursday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" data-id="Thursday" id="datetimepicker7"
                                        @if(@$storeTiming[3]['is_off']=='on' ) readonly @endif>
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Thursday" id="datetimepicker8" @if(@$storeTiming[3]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[3]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Thursday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Thursday">
                                    @endif
                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Friday</h6>
                                {{Form::hidden('day[]','Friday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" data-id="Friday" id="datetimepicker9"
                                        @if(@$storeTiming[4]['is_off']=='on' ) readonly @endif>
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Friday" id="datetimepicker10" @if(@$storeTiming[4]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[4]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Friday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Friday">
                                    @endif
                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Satureday</h6>
                                {{Form::hidden('day[]','Satureday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" data-id="Satureday" @if(@$storeTiming[5]['is_off']=='on' ) readonly
                                        @endif id="datetimepicker11">
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Satureday" id="datetimepicker12" @if(@$storeTiming[5]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[5]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Satureday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Satureday">
                                    @endif
                                </span>
                            </div>
                            <div class="working-wrap">
                                <h6 class="w-30">Sunday</h6>
                                {{Form::hidden('day[]','Sunday')}}
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input start_time" name="start_time[]"
                                        value="" data-id="Sunday" id="datetimepicker13"
                                        @if(@$storeTiming[6]['is_off']=='on' ) readonly @endif>
                                </span>
                                <span class="w-23">
                                    <input type="time" class="datetimepicker-input end_time" name="end_time[]" value=""
                                        data-id="Sunday" id="datetimepicker14" @if(@$storeTiming[6]['is_off']=='on' )
                                        readonly @endif>
                                </span>
                                <span class="w-20 checkbox">
                                    @if(@$storeTiming[6]['is_off'] == 'on')
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Sunday"
                                        checked>
                                    @else
                                    <input type="checkbox" name="is_holiday[]" class="checkmark" data-id="Sunday">
                                    @endif
                                </span>
                            </div>
                        </div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-black btn-modal" type="submit">SAVE</button>
            </div>
            {{Form::close()}}
        </div>
        <div class="tab-pane fade" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab">

            {{Form::open(array('url'=>'service-provider/update-store-gallery','method'=>'post','name'=>'update_gallery','id'=>'update_profile','files'=>'true','class'=>"online-store-form"))}}
            <div class="row">
                <div class="col-12">
                    <div class="input-form">
                        <input type='file' id="gallery" class="form-control" name="store_gallery[]" multiple
                            accept=".png, .jpg, .jpeg" />
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-black btn-modal submit">Add Photos</button>
            </div>
            {{Form::close()}}
            <div class="row mt-5">
                @forelse($storeGallery as $row)
                <div class="col-lg-3 mb-3">
                    <div class="c-gallery-item">
                        <img src="{{URL::to('storage/app/public/store/gallery/'.$row->file)}}" alt="">
                        <label for="imageUpload">
                            <a href="{{URL::to('service-provider/remove-image/'.$row->id.'/gallery')}}">
                                <i class="fas fa-times"></i>
                            </a>
                        </label>
                    </div>
                </div>
                @empty
                <div class="col-lg-12" style="text-align: center">No Images Found.</div>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endsection
@section('service_js')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js">
</script>
<script
    src="https://maps.google.com/maps/api/js?key=AIzaSyBSItHxCbk9qBcXp1XTysVLYcJick5K8mU&libraries=places&callback=initialize"
    type="text/javascript"></script>
<script>
google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
    var input = document.getElementById('autocomplete');
    var options = {
        componentRestrictions: {
            country: 'de'
        }
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        $('#latitude').val(place.geometry['location'].lat());
        $('#longitude').val(place.geometry['location'].lng());
    });
}
</script>
<script>
$(function() {
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


    var timing = <?php echo json_encode($storeTiming); ?>;

    if (timing[0]['is_off'] != 'on') {
        $("#datetimepicker1").val(timing[0]['start_time']);
    }
    if (timing[0]['is_off'] != 'on') {
        $("#datetimepicker2").val(timing[0]['end_time']);
    }
    if (timing[1]['is_off'] != 'on') {
        $("#datetimepicker15").val(timing[1]['start_time']);
    }
    if (timing[1]['is_off'] != 'on') {
        $("#datetimepicker4").val(timing[1]['end_time']);
    }
    if (timing[2]['is_off'] != 'on') {
        $("#datetimepicker5").val(timing[2]['start_time']);
    }
    if (timing[2]['is_off'] != 'on') {
        $("#datetimepicker6").val(timing[2]['end_time']);
    }
    if (timing[3]['is_off'] != 'on') {
        $("#datetimepicker7").val(timing[3]['start_time']);
    }
    if (timing[3]['is_off'] != 'on') {
        $("#datetimepicker8").val(timing[3]['end_time']);
    }
    if (timing[4]['is_off'] != 'on') {
        $("#datetimepicker9").val(timing[4]['start_time']);
    }
    if (timing[4]['is_off'] != 'on') {
        $("#datetimepicker10").val(timing[4]['end_time']);
    }

    if (timing[5]['is_off'] != 'on') {
        $("#datetimepicker11").val(timing[5]['start_time']);
    }
    if (timing[5]['is_off'] != 'on') {
        $("#datetimepicker12").val(timing[5]['end_time']);
    }

    if (timing[6]['is_off'] != 'on') {
        $("#datetimepicker13").val(timing[6]['start_time']);
    }
    if (timing[6]['is_off'] != 'on') {
        $("#datetimepicker14").val(timing[6]['end_time']);
    }

});

$(document).on('click', '.checkmark', function() {
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
$("#background-img").change(function() {
    readURLs(this);
});

function readURLs(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreviewBanner').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreviewBanner').hide();
            $('#imagePreviewBanner').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change', '#background-img', function() {
    $('#update_banner').submit();
});

$(document).on('change', '#imageUpload', function() {
    $('#update_profile').submit();
});
</script>
@endsection
