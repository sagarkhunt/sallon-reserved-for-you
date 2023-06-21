@extends('layouts.serviceProvider')
@section('service_title')
    Wallet
@endsection
@section('service_css')
@endsection
@section('service_content')
    <div class="body-content">
        <div class="wallet-head-wrap">
            <h5 class="page-title">My Wallet</h5>

        </div>
        <div class="wallet-wrap wallet-white-box white-box">
            <div class="wallet-item">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/wallet-money.svg')) ?></span>
                <div>
                    <h6>{{number_format($netEarning)}} €</h6>
                    <p>Net Earning</p>
                </div>
            </div>
            <div class="wallet-item">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/wallet-coins.svg')) ?></span>
                <div>
                    <h6>{{number_format($availableBalance)}} €</h6>
                    <p>Available Balance</p>
                </div>
            </div>
            <div class="wallet-item">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/wallet-history.svg')) ?></span>
                <div>
                    <h6>{{number_format($withdrwalBalance)}} €</h6>
                    <p>withdrawn Balance</p>
                </div>
            </div>
            <div class="wallet-item">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/wallet-refund_1.svg')) ?></span>
                <div>
                    <h6>{{number_format($refundableBalance)}} €</h6>
                    <p>Refundable Balance</p>
                </div>
            </div>
        </div>
        <div class="row mt-40">
            <div class="col-xl-9">
                <div class="timeline-title-wrap">
                    <h5 class="page-title">Statistics</h5>
                    <ul class="nav nav-pills statistics-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-day-tab" data-toggle="pill" href="#pills-day" role="tab"
                               aria-controls="pills-day" aria-selected="true">Daily</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-monthly-tab" data-toggle="pill" href="#pills-monthly" role="tab"
                               aria-controls="pills-monthly" aria-selected="false">Monthly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-yearly-tab" data-toggle="pill" href="#pills-yearly" role="tab"
                               aria-controls="pills-yearly" aria-selected="false">Yearly</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-day" role="tabpanel" aria-labelledby="pills-day-tab">
                        <div class="chart-box white-box chart-box-wallet">
                            <canvas id="myChart" height="400" width="900"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-monthly" role="tabpanel" aria-labelledby="pills-monthly-tab">
                        <div class="chart-box white-box chart-box-wallet">
                            <canvas id="myChart1" height="400" width="900"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
                        <div class="chart-box white-box chart-box-wallet">
                            <canvas id="myChart2" height="400" width="900"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="white-box earning-white-box">
                    <h6><span>Todays Earning </span>{{\Carbon\Carbon::now()->format('d M,Y')}}</h6>
                    <div class="stylish-box">
                        <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-provider/stylish.svg')}}" alt=""></span>
                        <h6>{{number_format($todayEarning)}} €</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-40">
            <div class="tabel-title-wrap">
                <h5 class="page-title">All Transaction</h5>
{{--                <div>--}}
{{--                    <select class="select">--}}
{{--                        <option>Show All</option>--}}
{{--                        <option>Show All</option>--}}
{{--                        <option>Show All</option>--}}
{{--                        <option>Show All</option>--}}
{{--                    </select>--}}
{{--                    <select class="select">--}}
{{--                        <option>All Month </option>--}}
{{--                        <option>All Month </option>--}}
{{--                        <option>All Month </option>--}}
{{--                        <option>All Month </option>--}}
{{--                    </select>--}}
{{--                    <select class="select">--}}
{{--                        <option>2020 </option>--}}
{{--                        <option>2020 </option>--}}
{{--                        <option>2020 </option>--}}
{{--                        <option>2020 </option>--}}
{{--                    </select>--}}
{{--                </div>--}}
            </div>
            <table id="example" class="table table-striped table-bordered my-table" style="width:100%">
                <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>User Name</th>
                    <th>Service Details</th>
                    <th>Service Expert</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                @forelse($allTransaction as $row)
                <tr>
                    <td>#{{$row->order_id}}</td>
                    <td>{{\Carbon\Carbon::parse($row->appo_date)->format('d M, Y')}}, {{\Carbon\Carbon::parse($row->appo_time)->format('H:i')}}</td>
                    <td>{{@$row->userDetails->first_name}} {{@$row->userDetails->last_name}}</td>
                    <td>{{$row->service_name}}</td>
                    <td>{{@$row->employeeDetails->emp_name}}</td>
                    <td class="{{$row->status}}">{{$row->status}}</td>
                    <td>{{$row->price}}€</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7">No Transaction Found.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
{{--            <a href="javascript:void(0)" class="view-more-table">View More Order</a>--}}
        </div>
    </div>

@endsection
@section('service_js')
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
        /*Day Data*/
        var dayData = '<?php echo json_encode($dayData); ?>'
        var dayLable =  new Array();
        var dayValue =  new Array();

        $.each(JSON.parse(dayData), function( index, value ) {
            dayLable.push(value.month);
            dayValue.push(value.count);
        });

        var ctx = document.getElementById("myChart");
        var dlable = dayLable;
        var ddata = dayValue;
        chart(ctx,dlable,ddata);

        /*Month data */
        var monthData = '<?php echo json_encode($monthData); ?>'
        var monthLable =  new Array();
        var monthValue =  new Array();
        $.each(JSON.parse(monthData), function( index, value ) {
            monthLable.push(value.month);
            monthValue.push(value.count);
        });

        var ctx1 = document.getElementById("myChart1");
        var month = monthLable;
        var mdata = monthValue;

        chart(ctx1,month,mdata);

        /*Year data */
        var yearData = '<?php echo json_encode($yearData); ?>'
        var yearLable =  new Array();
        var yearValue =  new Array();

        $.each(JSON.parse(yearData), function( index, value ) {
            yearLable.push(value.month);
            yearValue.push(value.count);
        });

        var ctx2 = document.getElementById("myChart2");
        var year = yearLable;
        var Ydata = yearValue;

        chart(ctx2,year,Ydata);
        function chart(ctx,type,d){
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: type,
                    datasets: [{
                        label: 'Earning in £',
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
