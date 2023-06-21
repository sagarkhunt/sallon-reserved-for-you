@extends('layouts.front')
@section('front_title')
    Cosmetic
@endsection
@section('front_css')
@endsection
@section('front_content')


<!-- Home banner -->
<section class="index-banner d-margin">
    <div class="index-banner-img">
        <img src="{{URL::to('storage/app/public/Frontassets/images/index-banner.jpg')}}" alt="">
    </div>
    <span class="about-banner-after"></span>
    <div class="cosmetics-box-main">
        <div class="cosmetics-box-header">
            <h4> Cosmetics</h4>
        </div>
        {{Form::open(array('url'=>URL::to('cosmetic-area')))}}
        <div class="cosmetics-box-body">
            <div class="owl-carousel owl-theme" id="service-item-owl2">
                @forelse($data as $raw)
                <div class="item">
                    <label href="javascript:void(0)"
                        class="service-item-icon service-item-icon-index service-item-label" data-id="{{$raw->id}}">
                        <input type="radio" name="categories" class="main_cate" value="{{$raw->id}}">
                        <span><?php echo file_get_contents(URL::to('storage/app/public/category/'.$raw->image)); ?></span>
                        <h6>{{$raw->name}}</h6>

                    </label>
                </div>
                @empty
                @endforelse
            </div>
            <ul>
                <li class="index-filter-li">
                    <select name="sab_cat" class="sub_cat">
                        <option>Sub-categories</option>
                    </select>
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/categories.svg')}}" alt=""></span>
                </li>
                <li class="index-filter-li">
                    <input type="text" name="stores" class="" id="search_data">
                    <div class="search_data">
                    <ul class="search_value"></ul>
                </div>
                        <!-- <option>Find salon or parlor</option>
                    </select> -->
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/store.svg')}}" alt=""></span>
                </li>
                <li class="index-filter-li">
                    <input type="text" id="autocomplete" autocomplete="off" placeholder="Post code or area">
                    <input type="hidden" id="postal_code" name="postal_code" autocomplete="off" placeholder="Post code or area">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/pin-2.svg')}}" alt=""></span>
                </li>
                <li class="index-filter-li">
                    <input class="datepicker" data-date-format="mm/dd/yyyy" placeholder="Date">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/calendar.svg')}}" alt=""></span>
                </li>
                <button type="submit" class="btn btn-black btn-search-now btn-block">Search now</button>
            </ul>
        </div>
        {{Form::close()}}
        <div class="cosmetics-box-footer"></div>
    </div>
</section>
 
<section class="index-service-section">
    <div class="container">
        <div class="who-r4u-section">
            <span><img src="{{URL::to('storage/app/public/Frontassets/images/service-heading-icon.')}}svg" alt=""></span>
            <h6>Our Services</h6>
            <h5>What kind of services do
            you want to take</h5>
        </div>
        <ul class="catagory-item-ull">
            <li class="catagory-item-1">
                <a href="javascript:void(0)" class="before-catagory">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/category-before-1.')}}svg" alt="">
                </a>
            </li>
            <li class="catagory-item-2">
                <a href="javascript:void(0)" class="after-catagory">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/category-2.svg')}}" alt="">
                </a>
                <h6>Cosmetics</h6>
            </li>
            <li class="catagory-item-3">
                <a href="javascript:void(0)" class="before-catagory">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/category-before-3.svg')}}" alt="">
                </a>
            </li>
            <li class="catagory-item-4">
                <a href="javascript:void(0)" class="before-catagory">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/category-before-4.svg')}}" alt="">
                </a>
            </li>
        </ul>
    </div>
</section>

<section class="pocket-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="pocket-info">
                    <h3>Manage Your Business From Your Pocket.</h3>
                    <p>We are available in both platform Android & iOS. it will help you to easy booking, save time & money.</p>
                    <ul>
                        <li>
                            <a href="#"><img src="{{URL::to('storage/app/public/Frontassets/images/play-store.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{URL::to('storage/app/public/Frontassets/images/app-store.svg')}}" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 pocket-order-1">
                <div class="pocket-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/pocket-phone.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="advantages-home-section padding-100">
    <div class="container">
        <div class="advantages-home-title">
            <h6>Our Advantages</h6>
            <h5>Do Benefit with Us</h5>
        </div>
        <div class="row advantages-home-row">
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/advantages-home-1.png')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-info">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/advantages-home-icon-1.svg')}}" alt=""></span>
                    <h5>Register now and use our function to your own Advantages</h5>
                    <h6>Free Registration</h6>
                </div>
            </div>
        </div>
        <div class="row advantages-home-row">
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-info">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/advantages-home-icon-2.svg')}}" alt=""></span>
                    <h5>Use our services around the clock, when and where you want</h5>
                    <h6>Use Anywhere, Anytime</h6>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 advantages-ordermd">
                <div class="advantages-home-item-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/advantages-home-2.png')}}" alt="">
                </div>
            </div>
        </div>
        <div class="row advantages-home-row">
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/advantages-home-3.png')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-info">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/advantages-home-icon-3.svg')}}" alt=""></span>
                    <h5>Our 4 Service Area on one platform Combined together.</h5>
                    <h6>Four in One</h6>
                </div>
            </div>
        </div>
        <div class="row advantages-home-row">
            <div class="col-lg-6 col-md-6">
                <div class="advantages-home-item-info">
                    <span><img src="{{URL::to('storage/app/public/Frontassets/images/icon/advantages-home-icon-4.svg')}}" alt=""></span>
                    <h5>Booking, Payment & rating give from any device Smartphone, Tablet & Laptop.</h5>
                    <h6>Book, Pay & Rate</h6>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 advantages-ordermd">
                <div class="advantages-home-item-img  ">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/advantages-home-4.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="digital-main-section">
    <div class="container">
        <div class="digital-main-title">
            <h6>We will help you to grow your business</h6>
            <h4>Bring your business to our
                Digital Platform</h4>
        </div>
        <ul class="digital-main-ul">
            <li>
                <h5>50,000+</h5>
                <p>Our Business Partner</p>
            </li>
            <li>
                <h5>2,50,350+</h5>
                <p>Total Booked Appointments</p>
            </li>
            <li>
                <h5>6 Million+</h5>
                <p>Already Joined Users</p>
            </li>
        </ul>
        <div class="owl-carousel owl-theme" id="digital-main-owl">
            <div class="item">
                <div class="digital-main-partner">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/client-1.png')}}" alt="">
                </div>
            </div>
            <div class="item">
                <div class="digital-main-partner">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/client-2.png')}}" alt="">
                </div>
            </div>
            <div class="item">
                <div class="digital-main-partner">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/client-3.png')}}" alt="">
                </div>
            </div>
            <div class="item">
                <div class="digital-main-partner">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/client-4.png')}}" alt="">
                </div>
            </div>
            <div class="item">
                <div class="digital-main-partner">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/client-5.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="index-map-section padding-100">
    <div class="container">
        <div class="index-map-title">
            <h6>Destinations</h6>
            <h5>Where will we Available for you</h5>
        </div>
    </div>
    <div class="index-map-image">
        <img src="{{URL::to('storage/app/public/Frontassets/images/map.png')}}" alt="">
    </div>
</section>


<section class="padding-100 social-media-section">
    <div class="container">
        <div class="row align-items-center ">
            <div class="col-lg-6 col-md-6">
                <div class="social-media-img">
                    <img src="{{URL::to('storage/app/public/Frontassets/images/facebook.png')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="soical-media-info">
                    <h5>Join us on our <br /> <span> Social Media </span> Platform</h5>
                    <ul class="soical-media-ul">
                        <li class="instagram">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/instagram.svg')); ?></span>
                            <h6>Instagram</h6>
                        </li>
                        <li class="active facebook">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/facebook.svg')); ?></span>
                            <h6>Facebook</h6>
                        </li>
                        <li class="tiktok">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/tiktok.svg')); ?></span>
                            <h6>TikTok</h6>
                        </li>
                        <li class="snapchat">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/snapchat.svg')); ?></span>
                            <h6>Snapchat</h6>
                        </li>
                        <li class="linkedin">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/linkedin.svg')); ?></span>
                            <h6>Linkedin</h6>
                        </li>
                        <li class="whatsapp">
                            <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/whatsapp.svg')); ?></span>
                            <h6>Whatsapp</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="register-cosmetic-section">
    <div class="container">
        <div class="register-cosmetic-areas">
            <span class="register-cosmetic-lines"><img src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
            <span class="register-cosmetic-lines2"><img src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
            <div class="register-cosmetic-areas-info">
                <h6>Register here to get all the Advantages</h6>
                <a href="javascript:void(0)" class="btn btn-white">Register Now</a>
            </div>
        </div>
    </div>
</section>

@endsection
@section('front_js')
<script
        src="https://maps.google.com/maps/api/js?key=AIzaSyBSItHxCbk9qBcXp1XTysVLYcJick5K8mU&libraries=places"
        type="text/javascript"></script>
<script type="text/javascript">

    $('.datepicker').datepicker();

    google.maps.event.addDomListener(window, 'load', initialize);
     function initialize() {
        var input = document.getElementById('autocomplete');
        var options = {
            types: ['(regions)'],
            componentRestrictions: {
                country: 'de'
            }
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            for (var j = 0; j < place.address_components.length; j++) {
                for (var k = 0; k < place.address_components[j].types.length; k++) {
                    if (place.address_components[j].types[k] == "postal_code") {
                        $('#postal_code').val(place.address_components[j].short_name);
                    }
                }
            }

        });
    }

    $('.main_cate').on('click',function(){
        var cat = $(this).val();
        $.ajax({
          type: 'GET',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{URL::to('get_sub_cat')}}",
          data: {cat:cat},
          success: function(response) { 
            // console.log(response.length);
            // if(response.length > 0){
                var html = "<option>Sub-categories</option>";
                $.each(response, function(i,data){
                    html += "<option value="+i+">"+data+"</option>";
                });

                $('.sub_cat').html(html);
            // }
          },
          error: function(error) {
            
            
          }
        });
    });

    $('.sub_cat').on('change',function(){
        var sub_cat = $(this).val();
        var cat = $('input[name=categories]:checked').val();
        $.ajax({
          type: 'GET',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{URL::to('get_stores')}}",
          data: {cat:cat,sub_cat:sub_cat},
          success: function(response) { 
            // console.log(response.length);
            // if(response.length > 0){
                var html = "<option>Find salon or parlor</option>";
                $.each(response, function(i,data){
                    html += "<option value="+i+">"+data+"</option>";
                });

                $('.stores').html(html);
            // }
          },
          error: function(error) {
            
            
          }
        });
    });

</script>
<script type="text/javascript">
    $(document).on('keyup', '#search_data', function () {
            var search = $(this).val();

            var baseURL = '{{URL::to('/')}}';
            if (search.length >= 4) {
                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('get-search-data')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        search: search,
                    },
                    // beforesend: $('#loading').css('display', 'block'),
                    success: function (response) {
                        var status = response.status;
                        var data = response.data;
                        var html = '';
                        if (status == 'true') {

                            $(data).each(function (i, item) {
                                if(item.url == 'store'){

                                    html += '<li><a href="javascript:void(0)" class="select_search" data-value="'+item.search_name+'">'+item.search_name+'</a></li>';
                                }
                            });

                            $('.search_value').html(html);
                        }
                        // $('#loading').css('display', 'none');
                    },
                    error: function (e) {

                    }
                });
            }else if(search.length == 0){
                $('.search_value').html('');
            }
        });

        $(document).on('click','.select_search',function (){
            var value = $(this).data('value');

            $('#search_data').val(value);
            $('.search_value').html('');

        })
</script>
@endsection
