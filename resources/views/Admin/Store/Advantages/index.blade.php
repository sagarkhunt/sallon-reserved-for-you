@extends('layouts.admin')
@section('title')
    Store Advantages
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
                <a href="{{URL::to('master-admin/store-profile/'.$id.'/advantages/create')}}" class="btn btn-primary text-white">+ Create Store Advantages</a>
            </nav>
            <h4 class="mb-1 mt-1">Store Advantages</h4>
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
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{$row->description}}</td>
                                <td>
                                    @if(file_exists(storage_path('app/public/store/advantage/'.$row->image)) && $row->image != '')
                                        <img src="{{URL::to('storage/app/public/store/advantage/'.$row->image)}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @else
                                        <img src="{{URL::to('storage/app/public/default/default-user.png')}}"
                                             class="rounded avatar-sm"
                                             alt="user">
                                    @endif
                                </td>
                                <td>
                                    <label
                                        class="badge badge-soft-{{$row->status == 'active' ? 'success' : 'danger'}}">{{$row->status}}</label>

                                </td>
                                <td>
                                    <a class="" href="{{URL::to('master-admin/store-profile/'.$id.'/advantages/'.$row->id.'/edit')}}"> <i
                                            class="uil-pen"></i></a>
                                    <a class="" href="{{URL::to('master-admin/store-profile/'.$id.'/advantages/'.$row->id.'/destroy')}}"><i
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

