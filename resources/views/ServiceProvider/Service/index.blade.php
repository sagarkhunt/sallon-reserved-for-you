@extends('layouts.serviceProvider')
@section('service_title')
    Service List
@endsection
@section('service_css')
    <style>
        #imageUpload {
            /*position: absolute;*/
            /*opacity: 0;*/
            top: 0;
            left: 0;
            z-index: -9999;
        }

        .service-upload-info span {
            padding: 8px;
        }

    </style>
@endsection
@section('service_content')
    <div class="body-content">
        <h5 class="page-title">Services List ({{count($category)}})</h5>
        <div class="service-list-wrap">
            <div style="display: none">{{$i = 1}}</div>
            @foreach($category as $row)
                <div class="service-uploded-item @if($i == 1) active @endif" data-id="{{$row->category_id}}"
                     data-value="{{@$row->CategoryData->name}}">
                    <div class="service-upload-info">
                        <span>
                    <img src="{{URL::to('storage/app/public/category/'.@$row->CategoryData->image)}}" alt="">
                        </span>
                        <h6>{{@$row->CategoryData->name}}</h6>
                    </div>

                </div>
                <div style="display: none">{{$i++}}</div>
            @endforeach

        </div>
        <div class="service-list">
            <div class="service-list-head-wrap">
                <h5 class="page-title"><span class="s_category">{{@$fistCategory['CategoryData']['name']}}</span>@if(count($service)>0) (<span
                        class="s_count">{{count($service)}}</span>)@endif</h5>
                <a href="javascript:void(0)" class="btn btn-skin" data-toggle="modal"
                   data-target="#add-extention">Add</a>
            </div>
            <div class="service-list-body-items">
                @forelse($service as $row)
                    <div class="service-list-item-wrap" data-id="{{$row->id}}">
                        <div class="service-list-img">
                            @if(file_exists(storage_path('app/public/service/'.$row['image'])) && $row['image'] != '')
                                <img src="{{URL::to('storage/app/public/service/'.$row['image'])}}"
                                     alt=""
                                >
                                @if($row->discount_type != null && $row->discount != '0')
                                <span>{{$row->discount}}{{$row->discount_type == 'percentage' ? '%' : '€'}} Disscount</span>
                                @endif
                            @else
                                <img src="{{URL::to('storage/app/public/default/store_default.png')}}"
                                     alt=""
                                >
                                @if($row->discount_type != null && $row->discount != '0')
                                    <span>{{$row->discount}}{{$row->discount_type == 'percentage' ? '%' : '€'}} Disscount</span>
                                @endif
                            @endif
                        </div>
                        <div class="service-list-info">
                            <h6>{{$row['service_name']}}</h6>
                            <p>{{$row['description']}}</p>
                            <p>{{$row->duration_of_service}} Min</p>
                            <h5>
                                @if($row->discount_type != null && $row->discount != '0')
                                    <span
                                        class="orignal-price">{{$row->price}}€</span> {{\BaseFunction::finalPrice($row->id)}}
                                    €
                                @else
                                    {{\BaseFunction::finalPrice($row->id)}}
                                    €
                                @endif
                            </h5>
                        </div>
                        <div class="service-list-action">
                            <a
                                href="javascript:void(0)" class="edit_service"
                                data-id="{{$row->id}}"><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/edit.svg')) ?></a>
                            <a
                                href="javascript:void(0)" class="remove_service"
                                data-id="{{$row->id}}"><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/delete.svg')) ?></a>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <!-- add extention -->
    <div class="modal fade" id="add-extention" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body">
                    {{Form::open(array('method'=>'post','url'=>'service-provider/add-service','files'=>'true','name'=>"add_service"))}}
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload" class="service-imageUpload" accept=".png, .jpg, .jpeg"
                                   name="image"/>
                            <label for="imageUpload">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" class="service-imagePreview"
                                 style="background-image: url(https://decodes-studio.com/reserved4you/storage/app/public/default/default-user.png);">
                            </div>
                        </div>
                    </div>
                <!-- {{Form::file('image',array('id'=>'imageUpload','accept'=>"image/*",'required'))}} -->
                    <div class="row">

                        <div class="col-12">
                            <div class="input-form">
                                <input type="text" placeholder="Enter Service Name" name="service_name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-form">
                                {{Form::textarea('description','',array('placeholder'=>'Enter Service Description','rows'=>'3','required'))}}
                            </div>
                        </div>
                        <div class="col-11">
                            <div class="input-form">
                                <input type="number" placeholder="Price" min="1" name="price" required>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="input-form">€</div>
                        </div>

                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('category_id',$categoryData,'',array('class'=>'form-control select category','id'=>'validationCustom01','required'))}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('subcategory_id',$categoryData,'',array('class'=>'form-control select subcategory','id'=>'validationCustom01'))}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                <input type="text" placeholder="Discount (€ | %)" name="discount">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('discount_type',array(''=>'Select Discount Type','percentage'=>"Percentage",'amount'=>'Amount'),'',array('class'=>'form-control select','id'=>'validationCustom01'))}}
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="input-form">
                                <input type="text" placeholder="Duration of time" name="duration_of_service" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-form"> Min.</div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-black btn-modal" type="submit">Add</button>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <!-- edit extention -->
    <div class="modal fade" id="edit-extention" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-radius">
                <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body">
                    {{Form::open(array('method'=>'post','url'=>'service-provider/update-service','files'=>'true','name'=>"edit_service",'class'=>'employee-form'))}}

                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload2" class="service-imageUpload" accept=".png, .jpg, .jpeg"
                                   name="image"/>
                            <label for="imageUpload2">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview2" class="service-imagePreview editser"
                                 style="background-image: url(https://decodes-studio.com/reserved4you/storage/app/public/default/default-user.png);">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12">
                            <div class="input-form">
                                <input type="text" placeholder="Enter Service Name" name="service_name" required
                                       class="service_name">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-form">
                                {{Form::textarea('description','',array('placeholder'=>'Enter Service Description','rows'=>'3','required','class'=>'description'))}}
                            </div>
                        </div>
                        <div class="col-11">
                            <div class="input-form">
                                <input type="number" placeholder="Price" min="1" name="price" required
                                       class="price">
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="input-form"> €</div>
                        </div>

                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('category_id',$categoryData,'',array('class'=>'form-control category_edit select','id'=>'validationCustom01','required'))}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('subcategory_id',$categoryData,'',array('class'=>'form-control subcategory_edit select','id'=>'validationCustom01'))}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                <input type="text" placeholder="Discount (€ | %)" name="discount" class="discount">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-form">
                                {{Form::select('discount_type',array(''=>'Select Discount Type','percentage'=>"Percentage",'amount'=>'Amount'),'',array('class'=>'form-control select discount_type','id'=>'validationCustom01'))}}
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="input-form">
                                <input type="text" placeholder="Duration of time" name="duration_of_service"
                                       required class="duration_of_service">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-form"> Min.</div>
                        </div>
                    </div>
                    <input type="hidden" name="service_id" class="service_id">
                    <div class="text-center">
                        <button class="btn btn-black btn-modal mt-1" type="submit">Update</button>
                    </div>
                    {{Form::close()}}

                </div>
            </div>
        </div>
    </div>
@endsection
@section('service_js')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).on('click', '.service-uploded-item', function () {
            var id = $(this).data('id');
            var value = $(this).data('value');
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/get-service')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function (response) {
                    var status = response.status;
                    var html = '';
                    if (status == 'true') {

                        var discount = '';
                        $(response.data).each(function (i, item) {


                            html += '<div class="service-list-item-wrap" data-id="'+item.id+'">\n' +
                                '  <div class="service-list-img">\n' +
                                '    <img src="' + item.image + '" alt="">\n' ;

                            if(item.discount_type != null && item.discount != 0){
                                html +='<span>'+item.discount+'% Disscount</span>' + item.price + '€</span> ' + item.discountedPrice + '€';
                            } else {
                                html += ' <h5>' + item.price + '€</h5>';
                            }

                            html += '  </div>\n' +
                                '  <div class="service-list-info">\n' +
                                '    <h6>' + item.service_name + '</h6>\n' +
                                '    <p>' + item.description + '</p>\n' +
                                '    <p>' + item.duration_of_service + ' Min</p> <h5>\n';
                            if(item.discount_type != null && item.discount != 0){
                                html +='<span class="orignal-price">' + item.price + '€</span> ' + item.discountedPrice + '€';
                            } else {
                                html +=  item.price + '€'
                            }

                            html += '  </h5> </div><div class="service-list-action"> <a ' +
                                '            href="javascript:void(0)" class="edit_service" data-id="' + item.id + '"><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/service-provider/edit.svg")) ?></a>\n' +
                                '    <a href="javascript:void(0)" class="remove_service" data-id="' + item.id + '"><?php echo file_get_contents(URL::to("storage/app/public/Frontassets/images/service-provider/delete.svg")) ?></a>\n' +
                                '  </div>\n' +
                                '</div>';
                        });

                        $('.service-list-body-items').html(html);
                        $('.service-uploded-item').removeClass('active');
                        $('.service-uploded-item[data-id=' + id + ']').addClass('active');


                    } else {
                        var html = '<div class="text-center">No Service Found</div>';
                        $('.service-list-body-items').html(html);
                        $('.service-uploded-item').removeClass('active');
                        $('.service-uploded-item[data-id=' + id + ']').addClass('active');
                    }

                    $('.s_category').text(value);
                    $('.s_count').text(response.data.length);


                },
                error: function (e) {

                }
            });
        });

        $(document).on('change', '.category', function () {
            var value = $(this).val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/service/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    var html = '<option value="">Select Subcategory</option>';

                    $(response.data).each(function (index, value) {

                        html += '<option value="' + value.id + '">' + value.name + '</option>';
                    });

                    if (response.data.length > 0) {
                        $('.subcategory').prop('required', true);
                    }

                    $('.subcategory').html(html);

                },
                error: function (e) {

                }
            });
        });

        $(document).on('change', '.category_edit', function () {
            var value = $(this).val();
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/service/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function (response) {
                    var status = response.status;

                    var html = '<option value="">Select Subcategory</option>';

                    $(response.data).each(function (index, value) {

                        html += '<option value="' + value.id + '">' + value.name + '</option>';
                    });

                    if (response.data.length > 0) {
                        $('.subcategory_edit').prop('required', true);
                    }

                    $('.subcategory_edit').html(html);

                },
                error: function (e) {

                }
            });
        });

        $("#imageUpload").change(function () {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.service-imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('.service-imagePreview').hide();
                    $('.service-imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imagePreview").change(function () {
            readURL2(this);
        });

        $(document).on('click', '.edit_service', function () {
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/edit-service')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    service_id: id,
                },
                success: function (response) {
                    var status = response.status;
                    var data = response.data;
                    $('.store_id').val(data.store_id);
                    $('.service_name').val(data.service_name);
                    $('.description').val(data.description);
                    $('.price').val(data.price);
                    $('.category_edit').val(data.category_id);
                    $('.discount').val(data.discount);
                    $('.discount_type').val(data.discount_type);
                    $('.duration_of_service').val(data.duration_of_service);
                    subcategory(data.category_id, data.subcategory_id);
                    $('.editser').css('background-image', 'url(' + data.image + ')');
                    $('.service_id').val(data.id);
                    $('#edit-extention').modal('toggle');
                    $('select.category_edit').niceSelect('update');

                    $('select.discount_type').niceSelect('update');
                },
                error: function (e) {

                }
            });
        });

        function subcategory(category_id, subcategory_id) {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('service-provider/service/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: category_id,
                },
                success: function (response) {
                    var status = response.status;

                    var html = '<option value="">Select Subcategory</option>';

                    $(response.data).each(function (index, value) {
                        if (value.id == subcategory_id) {
                            html += '<option value="' + value.id + '" selected>' + value.name + '</option>';
                        } else {
                            html += '<option value="' + value.id + '">' + value.name + '</option>';
                        }

                    });

                    if (response.data.length > 0) {
                        $('.subcategory_edit').prop('required', true);
                    }

                    $('.subcategory_edit').html(html);

                },
                error: function (e) {

                }
            });
        }

        $(document).on('click', '.remove_service', function () {
            var id = $(this).data('id');


            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this service!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Poof! Your service has been deleted!", {
                            icon: "success",
                        }).then((sucess) => {
                            if (sucess) {
                                $.ajax({
                                    type: 'POST',
                                    async: true,
                                    dataType: "json",
                                    url: "{{URL::to('service-provider/remove-service')}}",
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        service_id: id,
                                    },
                                    success: function (response) {
                                        var status = response.status;
                                        if (status == 'true') {
                                            $('.service-list-item-wrap[data-id=' + id + ']').remove();
                                        }

                                    },
                                    error: function (e) {

                                    }
                                });
                            }

                        });
                    } else {
                        swal("Your service is safe!");
                    }
                });
        })


        $("#imageUpload2").change(function () {
            readURL2(this);
        });

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.service-imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('.service-imagePreview').hide();
                    $('.service-imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imagePreview2").change(function () {
            readURL2(this);
        });
    </script>
@endsection
