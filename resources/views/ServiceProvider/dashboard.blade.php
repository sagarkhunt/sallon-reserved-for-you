@extends('layouts.serviceProvider')
@section('service_title')
    Dashboard
@endsection
@section('service_css')
@endsection
@section('service_content')
    <div class="index-main-flex">
        <div class="body-content index-body-content">
            <h5 class="page-title">Dashboard</h5>
            <div class="row dashboard-box-row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box green-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/calendar.svg')) ?></span>
                            <h4>{{number_format($activeAppointment)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Active Appointments <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box orange-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/pending.svg')) ?></span>
                            <h4>{{number_format($pendingAppointment)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Pending Appointments <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box blue-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/complate.svg')) ?></span>
                            <h4>{{number_format($completedAppointment)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Completed Appointments <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box red-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/cancel.svg')) ?></span>
                            <h4>{{number_format($canceledAppointment)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Cancel Appointments <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box pink-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/woman_combing.svg')) ?></span>
                            <h4>{{number_format($totalService)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Total Services <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box purple-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/employee-list.svg')) ?></span>
                            <h4>{{number_format(count($totalEmp))}}</h4>
                        </div>
                        <a href="javascript:void(0)">Total Employee <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box yellow-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/review.svg')) ?></span>
                            <h4>{{number_format($totalReview)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Customer Reviews <span><i class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="dashboard-box black-box">
                        <div class="dashboard-wrap">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/business_group.svg')) ?></span>
                            <h4>{{number_format($totalCustomer)}}</h4>
                        </div>
                        <a href="javascript:void(0)">Total Appointments <span><i
                                    class="fas fa-chevron-right"></i></span></a>
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-xl-6">
                    <div class="timeline-title-wrap">
                        <h5 class="page-title">Today Appointment</h5>
                    </div>
                    <ul class="time-item">
                        @foreach($todayAppointment as $row => $key)

                            <li>
                                <p class="timline-time">{{\Carbon\Carbon::parse($row)->format('H:i')}}</p>
                                @foreach($key as $value)
                                    <div class=" timeline-item-head">
                                        <div class="timeline-itme-profile">
                                            <img
                                                src="{{URL::to('storage/app/public/service/'.@$value->serviceDetails->image)}}"
                                                alt="">
                                        </div>
                                        <div>
                                            <h6>{{$value->service_name}}</h6>
                                            <p>{{@$value->employeeDetails->emp_name}}</p>
                                        </div>
                                        <div class="p-left">
                                            <h5>{{$value->price}}€</h5>
                                            <p><span>Status : </span>: <b>{{$value->status}}</b></p>
                                        </div>


                                    </div>

                                    <div class="timeline-info-wrap" style="margin-bottom: 10px">
                                        <p>
                                            <span>Appt Date : </span> {{\Carbon\Carbon::parse($value->appo_date)->format('d M,Y')}}
                                            -
                                            {{\Carbon\Carbon::parse($value->appo_time)->format('H:i')}}</p>
                                        <p><span>Order ID : </span> #{{@$value->order_id}}</p>
                                    </div>
                                    <div class="timeline-info-wrap" style="margin-bottom: 20px">
                                        <p>
                                            <span>Customer: </span> {{$value->customer}}
                                           </p>
                                        <p><span>Payment Method : </span>{{ $value->payment_method}}</p>

                                    </div>

                                @endforeach

                            </li>
                        @endforeach

                    </ul>
                </div>
                <div class="col-xl-6">
                    <div class="timeline-title-wrap">
                        <h5 class="page-title">Statistics</h5>
                        <ul class="nav nav-pills statistics-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-day-tab" data-toggle="pill" href="#pills-day"
                                   role="tab" aria-controls="pills-day" aria-selected="true">Daily</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-monthly-tab" data-toggle="pill" href="#pills-monthly"
                                   role="tab" aria-controls="pills-monthly" aria-selected="false">Monthly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-yearly-tab" data-toggle="pill" href="#pills-yearly"
                                   role="tab"
                                   aria-controls="pills-yearly" aria-selected="false">Yearly</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-day" role="tabpanel"
                             aria-labelledby="pills-day-tab">
                            <div class="chart-box">
                                <canvas id="myChart" height="380" width="500"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-monthly" role="tabpanel"
                             aria-labelledby="pills-monthly-tab">
                            <div class="chart-box">
                                <canvas id="myChartMonth" height="380" width="500"></canvas>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
                            <div class="chart-box">
                                <canvas id="myChartYear" height="380" width="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="index-stylish">
            <div class="search-notification-wrap">
                <div class="search-div">
                </div>
                <div class="dropdown">
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
            <div class="stylish-box">
            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-provider/stylish.svg')}}"
                       alt=""></span>
                <h6><span>Todays Earning : </span> €{{$todayEarning}}</h6>
            </div>
            <div class="stylish-items-list">
                <h5>Stylish ({{count($totalEmp)}})</h5>
                @foreach($totalEmp as $row)
                    <div class="stylish-item-wrap" onclick="window.location='{{URL::to('service-provider/employee-list')}}'">
                        <span>
                               @if(file_exists(storage_path('app/public/store/employee/'.$row->image)) && $row->image != '')
                                <img src="{{URL::to('storage/app/public/store/employee/'.$row->image)}}"
                                     alt=""
                                >
                            @else
                                <img src="{{URL::to('storage/app/public/default/default-user.png')}}"
                                     alt=""
                                >
                            @endif

                        </span>
                        <div>
                            <h6>{{$row->emp_name}}</h6>
{{--                            <p>{{$row->country}}</p>--}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('service_js')
    <script>

        $("body").addClass("dashboard-menu-icon");

        /*Day Data*/
        var dayData = '<?php echo json_encode($dayData); ?>'
        var dayLable = new Array();
        var dayValue = new Array();

        $.each(JSON.parse(dayData), function (index, value) {
            dayLable.push(value.month);
            dayValue.push(value.count);
        });

        var ctx = document.getElementById("myChart");
        var dlable = dayLable;
        var ddata = dayValue;
        chart(ctx, dlable, ddata);

        /*Month data */
        var monthData = '<?php echo json_encode($monthData); ?>'
        var monthLable = new Array();
        var monthValue = new Array();

        $.each(JSON.parse(monthData), function (index, value) {
            monthLable.push(value.month);
            monthValue.push(value.count);
        });

        var ctx1 = document.getElementById("myChartMonth");
        var month = monthLable;
        var mdata = monthValue;

        chart(ctx1, month, mdata);

        /*Year data */
        var yearData = '<?php echo json_encode($yearData); ?>'
        var yearLable = new Array();
        var yearValue = new Array();

        $.each(JSON.parse(yearData), function (index, value) {
            yearLable.push(value.month);
            yearValue.push(value.count);
        });

        var ctx2 = document.getElementById("myChartYear");
        var year = yearLable;
        var Ydata = yearValue;
        chart(ctx2, year, Ydata);


        function chart(ctx, type, d) {
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: type,
                    datasets: [{
                        label: 'No of Appointments',
                        data: d,
                        backgroundColor: [
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)',
                            'rgb(0, 0, 0)'
                        ],
                    }]
                },
                options: {
                    responsive: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                maxRotation: 90,
                                minRotation: 80
                            },
                            gridLines: {
                                offsetGridLines: true // à rajouter
                            }
                        },

                        ],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    </script>

@endsection
