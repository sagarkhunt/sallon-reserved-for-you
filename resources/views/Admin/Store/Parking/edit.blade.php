@extends('layouts.admin')
@section('title')
    Edit Store Parking
@endsection
@section('css')
@endsection
@section('content')
    <div class="row page-title">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="float-right mt-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{URL::to('master-admin/store-profile/'.$id.'/parking')}}">Store Parking</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Store Parking</li>
                </ol>
            </nav>
            <h4 class="mb-1 mt-0">Edit Store Parking</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Edit Store Parking</h4>

                    <hr/>

                    {{Form::open(array('url'=>'master-admin/store-profile/'.$id.'/parking/'.$data['id'],'method'=>'put','name'=>'edit-parking','files'=>'true','class'=>'needs-validation','novalidate'))}}
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">Name</label>
                                {{Form::text('parking_name',$data['parking_name'],array('class'=>'form-control','id'=>'validationCustom01','required'))}}
                                @error('parking_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    {{Form::submit('Submit',array('class'=>'btn btn-primary'))}}
                    <a href="{{URL::to('master-admin/store-profile/'.$id.'/parking')}}" class="btn btn-danger">Cancel</a>
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
        $('input[type="file"]'). change(function(e){

            var fileName = e. target. files[0]. name;
            $('.custom-file-label').text(fileName);

        });

    </script>
@endsection

