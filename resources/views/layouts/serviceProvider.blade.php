<!doctype html>
<html dir="ltr" lang="en-US">

<head>
    <title>@yield('service_title') reserved4you</title>
    <link type="image/x-icon" rel="shortcut icon" href="{{URL::to('storage/app/public/Frontassets/images/favicon.jpg')}}" />
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/font/stylesheet.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/all.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/owl.carousel.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/nice-select.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/dataTables.bootstrap4.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/fullcalendar.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/wickedpicker.min.css')}}" />
    <link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/service-provider.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{URL::to('storage/app/public/Frontassets/css/responsive.css')}}" />

    @yield('service_css')
    <?php
    use App\Models\StoreProfile;
    $getStore = ['' =>'All Store'] + StoreProfile::where('user_id', Auth::user()->id)->pluck('store_name','id')->all();
    ?>
</head>

<button class="service-navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <div class="burger">
        <span class="burger__line burger__line_first"></span>
        <span class="burger__line burger__line_second"></span>
        <span class="burger__line burger__line_third"></span>
        <span class="burger__line burger__line_fourth"></span>
    </div>
</button>

<aside class="sidebar">
    <a href="{{URL::to('service-provider')}}" class="side-logo">
        <img src="{{URL::to('storage/app/public/Frontassets/images/logo.png')}}" alt="">
    </a>
    <ul class="side-menu">

        <li>
            <i>{{Form::select('store_id',$getStore,session('store_id'),array('class'=>'select store_category'))}}</i>

        </li>
        <li>
            <a href="{{URL::to('service-provider')}}" class="{{Request::is('service-provider') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/dashboard.svg')) ?>
                </span>
                <i>Dashboard</i>
            </a>
        </li>
        <li>
            <a href="{{URL::to('service-provider/service-list')}}" class="{{Request::is('service-provider/service-list') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/service-list.svg')) ?>
                </span>
                <i>Services List</i>
            </a>
        </li>
        <li>
            <a href="{{URL::to('service-provider/employee-list')}}" class="{{Request::is('service-provider/employee-list') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/employee-list.svg')) ?>
                </span>
                <i>Employee List</i>
            </a>
        </li>
        <li>
            <a href="{{URL::to('service-provider/appointment')}}" class="{{Request::is('service-provider/appointment') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/calendar.svg')) ?>
                </span>
                <i>My Appointment</i>
            </a>
        </li>
        <li>
            <a href="{{URL::to('service-provider/wallet')}}" class="{{Request::is('service-provider/wallet') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/money.svg')) ?>
                </span>
                <i>My Wallet</i>
            </a>
        </li>
        <li>
            <a href="{{URL::to('service-provider/online-store')}}" class="{{Request::is('service-provider/online-store') ? 'active' : '' }}">
                <span>
                    <?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/service-provider/online_store.svg')) ?>
                </span>
                <i>Store Profile</i>
            </a>
        </li>
    </ul>
    <div class="sidebar-profile-box">
        {{--        <span>--}}
        {{--            <img src="{{URL::to('storage/app/public/Frontassets/images/c-gallery-img-5.jpg')}}" alt="">--}}
        {{--        </span>--}}
        <a href="javascript:void(0)">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</a>
        <p>The cosmetic studios Germany</p>
        <a href="{{URL::to('service-provider/logout')}}">Logout</a>
    </div>
</aside>


<body>
@yield('service_content')
<!-- Optional JavaScript -->
<script src="{{URL::to('storage/app/public/Frontassets/js/jquery.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/owl.carousel.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/wow.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/nice-select.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/chart.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/moment-with-locales.min.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/jquery.session.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/fullcalendar.js')}}"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/bootstrap-datepicker.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/wickedpicker.min.js')}}"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="{{URL::to('storage/app/public/Frontassets/js/custom.js')}}"></script>
<script>
    $(document).on('change','.store_category',function (){
        var value = $(this).val();

        $.ajax({
            type: 'POST',
            async: true,
            dataType: "json",
            url: "{{URL::to('service-provider/set-store')}}",
            data: {
                _token: '{{ csrf_token() }}',
                id: value,
            },
            success: function (response) {
                window.location.reload();
            }
        });
    })

</script>
@yield('service_js')
</body>

</html>
