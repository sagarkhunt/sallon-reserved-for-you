@extends('layouts.admin')
@section('title')
    Create Service
@endsection
@section('css')
@endsection
@section('content')
    <div class="row page-title">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="float-right mt-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin/store-profile/'.$store['id'])}}">Service</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create Service</li>
                </ol>
            </nav>
            <h4 class="mb-1 mt-0">Create Service</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Create Service</h4>

                    <hr/>

                    {{Form::open(array('url'=>'master-admin/store-profile/'.$store['id'].'/service','method'=>'post','name'=>'create-service','files'=>'true','class'=>'needs-validation','novalidate'))}}
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Store Name</label>
                                {{Form::text('store_id',$store['store_name'],array('class'=>'form-control','id'=>'validationCustom01','required','readonly'))}}
                                @error('store_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Category</label>
                                {{Form::select('category_id',$category,'',array('class'=>'form-control category','id'=>'validationCustom01','required'))}}
                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Sub Category</label>
                                {{Form::select('subcategory_id',$category,'',array('class'=>'form-control subcategory','id'=>'validationCustom01'))}}
                                @error('subcategory_id')
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
                                <label for="validationCustom01">Service Name</label>
                                {{Form::text('service_name','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Service Name','required'))}}
                                @error('service_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Description</label>
                                {{Form::textarea('description','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Description','required','rows'=>2))}}
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Price (€)</label>
                                {{Form::number('price','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Price','required','min'=>0))}}
                                @error('price')
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
                                <label for="validationCustom01">Discount (€ | %)</label>
                                {{Form::number('discount','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Discount','min'=>0))}}
                                @error('discount')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Discount Type</label>
                                {{Form::select('discount_type',array(''=>'Select Discount Type','percentage'=>"Percentage",'amount'=>'Amount'),'',array('class'=>'form-control','id'=>'validationCustom01'))}}
                                @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Duration Of Service (min)</label>
                                {{Form::text('duration_of_service','',array('class'=>'form-control','id'=>'validationCustom03','placeholder'=>'Duration Of Service','required'))}}
                                @error('duration_of_service')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label>Image</label>
                            <div class="custom-file">
                                {{Form::file('image',array('class'=>$errors->has('image') ?'custom-file-input is-invalid' : 'custom-file-input','id'=>'customFile','accept'=>"image/*",'required'))}}
                                <label class="custom-file-label" for="customFile">Choose file</label>

                            </div>
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Is Popular</label>
                                {{Form::select('is_popular',array(''=>'Select Is Popular','yes'=>'Yes','no'=>'No'),'',array('class'=>'form-control','id'=>'validationCustom03','required'))}}
                                @error('is_popular')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                    </div>

                    {{Form::submit('Submit',array('class'=>'btn btn-primary'))}}
                    <a href="{{URL::to('master-admin/store-profile/'.$store['id'])}}" class="btn btn-danger">Cancel</a>
                    {{Form::close()}}

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
@endsection

@section('plugin')
    <!-- Plugin js-->
    <script src="{{URL::to('storage/app/public/Adminassets/libs/parsleyjs/parsley.min.js')}}"></script>
@endsection
@section('js')
    <!-- Validation init js-->
    <script src="{{URL::to('storage/app/public/Adminassets/js/pages/form-validation.init.js')}}"></script>

    <script>
        $('input[name="image"]').change(function (e) {

            var fileName = e.target.files[0].name;
            $('.custom-file-label').text(fileName);

        });

        $(document).on('change','.category',function (){
            var value = $(this).val();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: "json",
                url: "{{URL::to('master-admin/service/category')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: value,
                },
                success: function(response) {
                    var status = response.status;

                   var html = '<option value="">Select Subcategory</option>';

                    $(response.data).each(function(index, value) {

                        html += '<option value="'+value.id+'">'+value.name+'</option>';
                    });

                    if(response.data.length > 0){
                        $('.subcategory').prop('required',true);
                    }

                    $('.subcategory').html(html);

                },
                error: function(e) {

                }
            });

        });


    </script>

@endsection

