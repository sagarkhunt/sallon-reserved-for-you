@extends('layouts.admin')
@section('title')
    Store Profile
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{URL::to('storage/app/public/Adminassets/libs/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('storage/app/public/Adminassets/libs/datatables/responsive.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="row page-title">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="float-right">
                <a href="{{URL::to('master-admin/store-profile/create')}}" class="btn btn-primary text-white">+ Create Store Profile</a>
            </nav>
            <h4 class="mb-1 mt-1">Store Profile</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="basic-datatable" class="table dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Store Owner Name</th>
                            <th>Store Name</th>
                            <th>Store Contact Number</th>
                            <th>Store Distinct</th>
                            <th>Store Category</th>
                            <th>Store Profile</th>
                            <th>Store Banner</th>
                            <th>Store Subsciption</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{@$row->userDara->first_name}} {{@$row->userDara->last_name}}</td>
                                <td>{{$row->store_name}}</td>
                                <td>{{$row->store_contact_number == '' ? '-' : $row->store_contact_number}}</td>
                                <td>{{$row->store_district == '' ? '-' : $row->store_district}}</td>
                                <td>{{$row->category_id == '' ? '-' : $row->category_id}}</td>
                                <td>
                                    @if(file_exists(storage_path('app/public/store/'.$row->store_profile)) && $row->store_profile != '')
                                        <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @else
                                        
                                        <img src="{{URL::to('storage/app/public/default/default_store.jpeg')}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @endif
                                </td>
                                <td>
                                    @if(file_exists(storage_path('app/public/store/banner/'.$row->store_banner)) && $row->store_banner != '')
                                        <img src="{{URL::to('storage/app/public/store/banner/'.$row->store_banner)}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @else
                                        <img src="{{URL::to('storage/app/public/default/default_store.jpeg')}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @endif
                                </td>
                                <td>{{$row->store_active_plan == '' ? '-' : ucfirst($row->store_active_plan)}}</td>
                                <td>
                                   <label
                                            class="badge badge-soft-{{$row->store_status == 'active' ? 'success' : 'danger'}}">{{$row->store_status}}</label>

                                </td>
                                <td>
                                    <a class="" href="{{URL::to('master-admin/store-profile/'.$row->id)}}"> <i
                                            class="uil-eye"></i></a>
                                    <a class="" href="{{URL::to('master-admin/store-profile/'.$row->id.'/edit')}}"> <i
                                            class="uil-pen"></i></a>
                                    <a class="" href="{{URL::to('master-admin/store-profile/'.$row->id.'/destroy')}}"><i
                                            class="uil-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
@section('plugin')
    <script src="{{URL::to('storage/app/public/Adminassets/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>
@endsection
@section('js')
    <!-- Datatables init -->
    <script src="{{URL::to('storage/app/public/Adminassets/js/pages/datatables.init.js')}}"></script>
@endsection

