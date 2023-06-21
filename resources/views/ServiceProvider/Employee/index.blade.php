@extends('layouts.serviceProvider')
@section('service_title')
    Employee List
@endsection
@section('service_css')

@endsection
@section('service_content')
    <div class="body-content">
        <div class="employee-head-wrap">
            <h5 class="page-title">Employee List ({{count($data)}})</h5>
            <div class="search-notification-wrap">
                <a href="javascript:void(0)" class="btn btn-black" data-toggle="modal" data-target="#add-employee">Add
                    Employee</a>
                <div class="search-div">
{{--                    <input type="search">--}}
{{--                    <i class="fas fa-search"></i>--}}
                </div>
                <div class="dropdown employee-none">
                    <a class="notification-icon dropdown-toggle" type="button" id="dropdownMenuButton"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/notification.svg')) ?>
                        <span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu2" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit.</a>
                        <a class="dropdown-item" href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit.</a>
                        <a class="dropdown-item" href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit.</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data as $row)
                <div class="col-xl-3 col-lg-4 col-md-6 emp_tabs" data-id="{{$row->id}}">
                    <div class="employee-item">
                        <div class="employee-profile-img">

                            @if(file_exists(storage_path('app/public/store/employee/'.$row->image)) && $row->image != '')
                                <img src="{{URL::to('storage/app/public/store/employee/'.$row->image)}}"
                                     alt=""
                                     >
                            @else
                                <img src="{{URL::to('storage/app/public/default/default-user.png')}}"
                                     alt=""
                                    >
                            @endif
                        </div>
                        <h6>{{$row->emp_name}}</h6>
                        <p>{{$row->day}}</p>
                        <p>@if(@$row->time->is_off == 'off'){{@$row->time->start_time}}
                            To {{@$row->time->end_time}} @else - @endif</p>

                        <ul>
                            <li>{{$row->category}}</li>
                        </ul>
                        <div class="dropdown employee-dropdown">
                            <a class="service-menu-icon dropdown-toggle" type="button" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu2 service-dropdown"
                                 aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item edit_emp" data-id="{{$row->id}}" data-toggle="modal"
                                   data-target="#edit-employee">Edit</a>
                                <a class="dropdown-item remove_emp" data-id="{{$row->id}}" href="#">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- add-employee -->
    <div class="modal fade" id="add-employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body">
                    {{Form::open(array('method'=>'post','url'=>'service-provider/add-employee','files'=>'true','name'=>"add_service",'class'=>"employee-form"))}}
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="image"/>
                            <label for="imageUpload">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url(https://decodes-studio.com/reserved4you/storage/app/public/default/default-user.png);">
                            </div>
                        </div>
                    </div>

                    <div class="input-form">
                        {{Form::text('emp_name','',array('placeholder'=>'Employee Name','required'))}}
                    </div>

                    <div class="input-form">
                        {{Form::select('category_name',$category,'',array('class'=>"select category",'required'))}}
                    </div>
                    <div class="input-form">
                        <!-- {{Form::select('service_name[]',array(''=>'Select Service'),'',array('class'=>"services",'multiple'=>'true','required'))}} -->
                        <div id="output"></div>
                        {{Form::select('service_name[]',array(''=>'Select Service'),'',array('class'=>"services chosen-select",'multiple'=>'true','required'))}}
                    </div>
                    <h5>Select Working Hour</h5>
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
                                <input type="text" class="timepicker2 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Monday"
                                                      >
                            </span>
                            <span class="w-23">
                                <input type="text" class="timepicker3 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Monday" id="datetimepicker2">
                            </span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Monday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Tuesday</h6>
                            {{Form::hidden('day[]','Tuesday')}}
                            <span class="w-23"><input type="text" class="timepicker4 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Tuesday"
                                                      id="datetimepicker15"></span>
                            <span class="w-23"><input type="text" class="timepicker5 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Tuesday" id="datetimepicker4"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Tuesday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Wednesday</h6>
                            {{Form::hidden('day[]','Wednesday')}}
                            <span class="w-23"><input type="text" class="timepicker6 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Wednesday"
                                                      id="datetimepicker5"></span>
                            <span class="w-23"><input type="text" class="timepicker7 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Wednesday"
                                                      id="datetimepicker6"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Wednesday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Thursday</h6>
                            {{Form::hidden('day[]','Thursday')}}
                            <span class="w-23"><input type="text" class="timepicker8 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Thursday"
                                                      id="datetimepicker7"></span>
                            <span class="w-23"><input type="text" class="timepicker9 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Thursday"
                                                      id="datetimepicker8"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Thursday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Friday</h6>
                            {{Form::hidden('day[]','Friday')}}
                            <span class="w-23"><input type="text" class="timepicker10 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Friday"
                                                      id="datetimepicker9"></span>
                            <span class="w-23"><input type="text" class="timepicker11 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Friday" id="datetimepicker10"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Friday" checked></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Satureday</h6>
                            {{Form::hidden('day[]','Satureday')}}
                            <span class="w-23"><input type="text" class="timepicker12 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Satureday"
                                                      id="datetimepicker11"></span>
                            <span class="w-23"><input type="text" class="timepicker13 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Satureday"
                                                      id="datetimepicker12"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Satureday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Sunday</h6>
                            {{Form::hidden('day[]','Sunday')}}
                            <span class="w-23"><input type="text" class="timepicker14 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Sunday"
                                                      id="datetimepicker13"></span>
                            <span class="w-23"><input type="text" class="timepicker15 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Sunday" id="datetimepicker14"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]" class="checkmark"
                                                               data-id="Sunday" checked></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-black btn-modal" type="submit">Save</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <!-- edit-employee -->
    <div class="modal fade" id="edit-employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body">
                    {{Form::open(array('method'=>'post','url'=>'service-provider/update-employee','files'=>'true','name'=>"add_service",'class'=>"employee-form"))}}
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUploads" name="image" accept=".png, .jpg, .jpeg"/>
                            <label for="imageUploads">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreviews">
                                {{--                                <img src="http://i.pravatar.cc/500?img=7" class="profile_pic">--}}
                            </div>
                        </div>
                    </div>
                    <div class="input-form">
                        {{Form::text('emp_name','',array('placeholder'=>'Employee Name','required','class'=>'edit_emp'))}}
                    </div>
                    <div class="input-form">
                        <!-- {{Form::select('category_name',$category,'',array('class'=>"select edit_category",'required'))}} -->
                        {{Form::select('category_name',$category,'',array('class'=>"select edit_category",'required'))}}
                    </div>
                    <div class="input-form">
                        <!-- {{Form::select('service_name[]',array(''=>'Select Service'),'',array('class'=>"edit_services",'multiple'=>'true','required'))}} -->
                        <div id="output"></div>
                        {{Form::select('service_name[]',array(''=>'Select Service'),'',array('class'=>"edit_services chosen-select",'multiple'=>'true','required'))}}
                        <!-- <select data-placeholder="Choose tags ..." name="tags[]" multiple class="chosen-select">
                            <option value="Engineering">Engineering</option>
                            <option value="Carpentry">Carpentry</option>
                            <option value="Plumbing">Plumbing</option>
                            <option value="Electical">Electrical</option>
                            <option value="Mechanical">Mechanical</option>
                            <option value="HVAC">HVAC</option>
                        </select> -->
                    </div>
                    {{Form::hidden('store_emp_id','',array('class'=>'store_emp_id'))}}
                    <h5>Select Working Hour</h5>
                    <div class="working-hour-bg">
                        <div class="working-wrap mb-3">
                            <p class="w-30">Day</p>
                            <p class="w-23">Stating Hour</p>
                            <p class="w-23">Stating Hour</p>
                            <p class="w-20">Holiday</p>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Monday</h6>
                            {{Form::hidden('day[]','Monday')}}
                            <span class="w-23"><input type="text" class="etimepicker2 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Monday"
                                                      id="datetimepicker1edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker3 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Monday"
                                                      id="datetimepicker2edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark mondaychecked" data-id="Monday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Tuesday</h6>
                            {{Form::hidden('day[]','Tuesday')}}
                            <span class="w-23"><input type="text" class="etimepicker4 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Tuesday"
                                                      id="datetimepicker15edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker5 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Tuesday"
                                                      id="datetimepicker4edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark tuesdaychecked"
                                                               data-id="Tuesday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Wednesday</h6>
                            {{Form::hidden('day[]','Wednesday')}}
                            <span class="w-23"><input type="text" class="etimepicker6 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Wednesday"
                                                      id="datetimepicker5edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker7 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Wednesday"
                                                      id="datetimepicker6edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark wednesdaychecked"
                                                               data-id="Wednesday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Thursday</h6>
                            {{Form::hidden('day[]','Thursday')}}
                            <span class="w-23"><input type="text" class="etimepicker8 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Thursday"
                                                      id="datetimepicker7edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker9 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Thursday"
                                                      id="datetimepicker8edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark thursdaychecked"
                                                               data-id="Thursday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Friday</h6>
                            {{Form::hidden('day[]','Friday')}}
                            <span class="w-23"><input type="text" class="etimepicker10 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Friday"
                                                      id="datetimepicker9edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker11 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Friday"
                                                      id="datetimepicker10edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark fridaychecked" data-id="Friday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Satureday</h6>
                            {{Form::hidden('day[]','Satureday')}}
                            <span class="w-23"><input type="text" class="etimepicker12 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Satureday"
                                                      id="datetimepicker11edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker13 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Satureday"
                                                      id="datetimepicker12edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark saturedaychecked"
                                                               data-id="Satureday"></span>
                        </div>
                        <div class="working-wrap">
                            <h6 class="w-30">Sunday</h6>
                            {{Form::hidden('day[]','Sunday')}}
                            <span class="w-23"><input type="text" class="etimepicker14 clocktime datetimepicker-input start_time"
                                                      name="start_time[]" value="" data-id="Sunday"
                                                      id="datetimepicker13edit"></span>
                            <span class="w-23"><input type="text" class="etimepicker15 clocktime datetimepicker-input end_time"
                                                      name="end_time[]" value="" data-id="Sunday"
                                                      id="datetimepicker14edit"></span>
                            <span class="w-20 checkbox"><input type="checkbox" name="is_holiday[]"
                                                               class="checkmark sundaychecked" data-id="Sunday"></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-black btn-modal submit">Update</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('service_js')
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $("body").addClass("employee-menu-icon");
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');

                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function () {
            readURL(this);
        });

        $("#imageUploads").change(function () {
            readURLs(this);
        });

        function readURLs(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreviews').css('background-image', 'url(' + e.target.result + ')');
                    // $('.profile_pic').attr('src', e.target.result);
                    $('#imagePreviews').hide();
                    $('#imagePreviews').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change', '.category', function () {
            var value = $(this).val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/employee/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    // var html = '<option value="">Select Services</option>';
                    var html = '';

                    $(response.data).each(function (index, value) {

                        html += '<option value="' + value.id + '">' + value.service_name + '</option>';
                    });

                    if (response.data.length > 0) {
                        $('.services').prop('required', true);
                    }

                    $('.services').html(html);
                    // $('.services').multiselect('refresh');
                    // $('select.services').niceSelect('update');
                    $("select.services").trigger("chosen:updated");

                },
                error: function (e) {

                }
            });
        });

        $(function () {
            // $('#datetimepicker1').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker1').datetimepicker({
            //     format: 'HH:mm'
            // });
            //
            // $('#datetimepicker2').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker2').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker3').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker3').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker4').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker4').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker5').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker5').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker6').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker6').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker7').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker7').datetimepicker({
            //     format: 'HH:mm'
            // });
            //
            // $('#datetimepicker8').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker8').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker9').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker9').datetimepicker({
            //     format: 'HH:mm'
            // });
            //
            // $('#datetimepicker10').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker10').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker10').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker10').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker11').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker11').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker12').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker12').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker13').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker13').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker14').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker14').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker15').datetimepicker({
            //     format: 'HH:mm'
            // });
            // $('#datetimepicker15').datetimepicker({
            //     format: 'HH:mm'
            // });

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

        $(document).on('click', '.edit_emp', function () {
            var id = $(this).data('id');

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/edit-employee')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    employee: id,
                },
                success: function (response) {
                    var status = response.status;
                    var html = '';
                    var data = response.data;

                    $('.edit_emp').val(data.emp_name);
                    $('.edit_country').val(data.country);
                    $('.edit_category').val(data.category);
                    $('.store_emp_id').val(data.id);
                    // $('.profile_pic').attr('src', data.image);
                    $('#imagePreviews').css('background-image', 'url(' + data.image + ')');
                    $('#edit-employee').modal('toggle');
                    changeCategory(data.category, data.service);
                    $('select.edit_country').niceSelect('update');
                    $('select.edit_category').niceSelect('update');


                    var timing = data.timeslot;

                    if (timing[0]['is_off'] != 'on') {
                        $("#datetimepicker1edit").val(timing[0]['start_time']);
                    }
                    if (timing[0]['is_off'] != 'on') {
                        $("#datetimepicker2edit").val(timing[0]['end_time']);
                    }

                    if (timing[0]['is_off'] == 'on') {
                        $(".mondaychecked").prop("checked", true);
                        $('#datetimepicker1edit').attr('readonly', true);
                        $("#datetimepicker2edit").attr('readonly', true);
                    }
                    if (timing[1]['is_off'] != 'on') {
                        $("#datetimepicker15edit").val(timing[1]['start_time']);
                    }
                    if (timing[1]['is_off'] != 'on') {
                        $("#datetimepicker4edit").val(timing[1]['end_time']);
                    }
                    if (timing[1]['is_off'] == 'on') {
                        $(".tuesdaychecked").prop("checked", true);
                        $('#datetimepicker15edit').attr('readonly', true);
                        $("#datetimepicker4edit").attr('readonly', true);
                    }

                    if (timing[2]['is_off'] != 'on') {
                        $("#datetimepicker5edit").val(timing[2]['start_time']);
                    }
                    if (timing[2]['is_off'] != 'on') {
                        $("#datetimepicker6edit").val(timing[2]['end_time']);
                    }

                    if (timing[2]['is_off'] == 'on') {
                        $(".wednesdaychecked").prop("checked", true);
                        $('#datetimepicker5edit').attr('readonly', true);
                        $("#datetimepicker6edit").attr('readonly', true);
                    }


                    if (timing[3]['is_off'] != 'on') {
                        $("#datetimepicker7edit").val(timing[3]['start_time']);
                    }
                    if (timing[3]['is_off'] != 'on') {
                        $("#datetimepicker8edit").val(timing[3]['end_time']);
                    }

                    if (timing[3]['is_off'] == 'on') {
                        $(".thursdaychecked").prop("checked", true);
                        $('#datetimepicker7edit').attr('readonly', true);
                        $("#datetimepicker8edit").attr('readonly', true);
                    }

                    if (timing[4]['is_off'] != 'on') {
                        $("#datetimepicker9edit").val(timing[4]['start_time']);
                    }
                    if (timing[4]['is_off'] != 'on') {
                        $("#datetimepicker10edit").val(timing[4]['end_time']);
                    }

                    if (timing[4]['is_off'] == 'on') {
                        $(".fridaychecked").prop("checked", true);
                        $('#datetimepicker9edit').attr('readonly', true);
                        $("#datetimepicker10edit").attr('readonly', true);
                    }


                    if (timing[5]['is_off'] != 'on') {
                        $("#datetimepicker11edit").val(timing[5]['start_time']);
                    }
                    if (timing[5]['is_off'] != 'on') {
                        $("#datetimepicker12edit").val(timing[5]['end_time']);
                    }

                    if (timing[5]['is_off'] == 'on') {
                        $(".saturedaychecked").prop("checked", true);
                        $('#datetimepicker11edit').attr('readonly', true);
                        $("#datetimepicker12edit").attr('readonly', true);
                    }

                    if (timing[6]['is_off'] != 'on') {
                        $("#datetimepicker13edit").val(timing[6]['start_time']);
                    }
                    if (timing[6]['is_off'] != 'on') {
                        $("#datetimepicker14edit").val(timing[6]['end_time']);
                    }

                    if (timing[6]['is_off'] == 'on') {
                        $(".sundaychecked").prop("checked", true);
                        $('#datetimepicker13edit').attr('readonly', true);
                        $("#datetimepicker14edit").attr('readonly', true);
                    }

                },
                error: function (e) {

                }
            });

        });

        function changeCategory(value, service) {


            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/employee/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    // var html = '<option value="">Select Services</option>';
                    var html = '';

                    $(response.data).each(function (index, value) {
                        if ($.inArray(value.id, service)) {
                            html += '<option value="' + value.id + '" selected>' + value.service_name + '</option>';
                        } else {

                            html += '<option value="' + value.id + '">' + value.service_name + '</option>';
                        }
                    });

                    if (response.data.length > 0) {
                        $('.edit_services').prop('required', true);
                    }

                    $('.edit_services').html(html);
                    // $('.edit_services').multiselect('refresh');
                    $("select.edit_services").trigger("chosen:updated");

                },
                error: function (e) {

                }
            });
        }

        $(document).on('change', '.edit_category', function () {
            var value = $(this).val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/employee/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    // var html = '<option value="">Select Services</option>';
                    var html = '';

                    $(response.data).each(function (index, value) {

                        html += '<option value="' + value.id + '">' + value.service_name + '</option>';
                    });

                    if (response.data.length > 0) {
                        $('.edit_services').prop('required', true);
                    }

                    $('.edit_services').html(html);
                    // $('.services').multiselect('refresh');
                    // $('select.edit_services').niceSelect('update');
                    $("select.edit_services").trigger("chosen:updated");

                },
                error: function (e) {

                }
            });
        });

        $(document).on('click','.remove_emp',function (){
            var id = $(this).data('id');


            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Employee!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Poof! Your Employee has been deleted!", {
                            icon: "success",
                        }).then((sucess) =>{
                            if(sucess){
                                $.ajax({
                                    type: 'POST',
                                    async: true,
                                    dataType: "json",
                                    url: "{{URL::to('service-provider/remove-employee')}}",
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        emp_id: id,
                                    },
                                    success: function (response) {
                                        var status = response.status;
                                        if(status == 'true'){
                                            $('.emp_tabs[data-id='+id+']').remove();
                                        }

                                    },
                                    error: function (e) {

                                    }
                                });
                            }

                        });
                    } else {
                        swal("Your Employee is safe!");
                    }
                });
        });
        $('.timepicker2, .timepicker3, .timepicker4, .timepicker5, .timepicker6, .timepicker7, .timepicker8, .timepicker9, .timepicker10, .timepicker11, .timepicker12, .timepicker13, .timepicker14, .timepicker15, .timepicker16').wickedpicker({
            twentyFour: true,
        });

        $('.etimepicker2, .etimepicker3, .etimepicker4, .etimepicker5, .etimepicker6, .etimepicker7, .etimepicker8, .etimepicker9, .etimepicker10, .etimepicker11, .etimepicker12, .etimepicker13, .etimepicker14, .etimepicker15, .etimepicker16').wickedpicker({
            twentyFour: true,
        });
        document.getElementById('output').innerHTML = location.search;
        $(".chosen-select").chosen();
    </script>
@endsection

