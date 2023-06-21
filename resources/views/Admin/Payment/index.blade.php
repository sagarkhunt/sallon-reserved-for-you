@extends('layouts.admin')
@section('title')
    Payment Details
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
            <h4 class="mb-1 mt-1">Payment Details</h4>
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
                            <th>Order No</th>
                            <th>Service Name</th>
                            <th>Customer Name</th>
                            <th>Payment Method</th>
                            <th>Payment ID</th>
                            <th>Price</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>#{{$row->order_id}}</td>
                                <td>{{@$row->serviceData->service_name == '' ? '-' : @$row->serviceData->service_name}}</td>
                                <td>{{@$row->userData->first_name != '' ? @$row->userData->first_name.' '. @$row->userData->last_name : (@$row->appointmentData->first_name == '' ? '-' : @$row->appointmentData->first_name.' '.@$row->appointmentData->first_name ) }}</td>
                                <td>{{$row->payment_method}}</td>
                                <td>{{$row->payment_id == '' ? '-' : $row->payment_id}}</td>
                                <td>£{{$row->total_amount}}</td>
                                <td><label
                                        class="badge badge-soft-{{$row->status == 'succeeded' ? 'success' : ($row->status == 'pending' ? 'warning':'danger')}}">{{$row->status}}</label></td>
                                <td>{{\Carbon\Carbon::parse($row->updated_at)->format('M d, Y')}}</td>
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

