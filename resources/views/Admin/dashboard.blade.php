@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('css')
@endsection
@section('content')
    <div class="row page-title align-items-center">
        <div class="col-sm-4 col-xl-6">
            <h4 class="mb-1 mt-0">Dashboard</h4>
        </div>

    </div>

    <!-- content -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total
                                                    Revenue</span>
                            <h2 class="mb-0">{{number_format($totalRevenue,1)}} €</h2>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total Store</span>
                            <h2 class="mb-0">{{$totalStore}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">New
                                                    Customers</span>
                            <h2 class="mb-0">{{$totalUser}}</h2>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total Appointment</span>
                            <h2 class="mb-0">{{$totalAppointment}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('plugin')
    <script src="{{URL::to('storage/app/public/Adminassets/libs/moment/moment.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{URL::to('storage/app/public/Adminassets/js/pages/dashboard.init.js')}}"></script>
@endsection
@section('js')

@endsection

