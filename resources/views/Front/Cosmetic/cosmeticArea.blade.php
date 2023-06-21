@extends('layouts.front')
@section('front_title')
    Cosmetic Area
@endsection
@section('front_css')
@endsection
@section('front_content')
    <!-- Home banner -->
<section class="d-margin area-banner">
    <div class="container">
        <ul class="nav nav-pills area-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-new-tab" data-toggle="pill" href="#pills-new" role="tab"
                    aria-controls="pills-new" aria-selected="true">New</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-recommended-tab" data-toggle="pill" href="#pills-recommended" role="tab"
                    aria-controls="pills-recommended" aria-selected="false">Recommended</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-rated-tab" data-toggle="pill" href="#pills-rated" role="tab"
                    aria-controls="pills-rated" aria-selected="false">Best Rated</a>
            </li>
        </ul>
        <div class="tab-content owl-buttons" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-new" role="tabpanel" aria-labelledby="pills-new-tab">
                <div class="owl-carousel owl-theme" id="new-area-owl">
                    @forelse($new_store as $key=>$row)
                    <div class="item">
                        <div class="area-banner-item">
                            <div class="area-banner-item-img">
                                <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}" alt="">
                            </div>
                            <div class="area-banner-item-info">
                                <p class="review-box"><span><i class="fas fa-star"></i></span>{{$row->rating}}</p>
                                <h6>{{$row->store_name}}</h6>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="tab-pane fade" id="pills-recommended" role="tabpanel" aria-labelledby="pills-recommended-tab">
                <div class="owl-carousel owl-theme" id="recommended-area-owl">
                    @forelse($recommandedforyou as $key=>$row)
                    <div class="item">
                        <div class="area-banner-item">
                            <div class="area-banner-item-img">
                                <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}" alt="">
                            </div>
                            <div class="area-banner-item-info">
                                <p class="review-box"><span><i class="fas fa-star"></i></span>{{$row->rating}}</p>
                                <h6>{{$row->store_name}}</h6>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="tab-pane fade" id="pills-rated" role="tabpanel" aria-labelledby="pills-rated-tab">
                <div class="owl-carousel owl-theme" id="rated-area-owl">
                    @forelse($high_rate as $key=>$row)
                    <div class="item">
                        <div class="area-banner-item">
                            <div class="area-banner-item-img">
                                <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}" alt="">
                            </div>
                            <div class="area-banner-item-info">
                                <p class="review-box"><span><i class="fas fa-star"></i></span>{{$row->rating}}</p>
                                <h6>{{$row->store_name}}</h6>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<section class="area-filter">
    <div class="container">
        <ul class="area-filter-wrap">
            <li class="w-17">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/categories.svg')) ?></span>
                <!-- <input type="text" placeholder="Categories"> -->
                {{Form::select('categories',$category,[''=>'Categories'],array('class'=>'main_cate'))}}
            </li>
            <li class="w-20">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/categories.svg')) ?></span>
                <select name="sab_cat" class="sub_cat">
                    <option>Sub-categories</option>
                </select>
            </li>
            <li class="w-18">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/store.svg')) ?></span>
                <input type="text" name="stores" class="stores" id="search_data">
                <div class="search_data">
                <ul class="search_value"></ul>
            </li>
            <li class="w-17">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/location.svg')) ?></span>
                <input type="text" id="autocomplete" autocomplete="off" placeholder="Place">
                <input type="hidden" id="postal_code" name="postal_code" autocomplete="off" placeholder="Post code or area">
            </li>
            <li class="w-17">
                <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/calendar.svg')) ?></span>
                <input class="datepicker" name="date" data-date-format="mm/dd/yyyy" placeholder="Date">
            </li>
            <li class="w-10 ml-auto">
                <button href="javascript:void(0)" class="btn btn-blue btn-filter-search serach_btn" type="button">Search Now</button>
            </li>
        </ul>
    </div>
</section>

<section>
    <div class="container">
        <div class="filter-wrap">
            <p><span>{{count($getStore)}}</span> Result of the service you selected</p>
            <div class="filter-right-info">
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                    <p>Map</p>
                </label>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                    <p>Booking System</p>
                </label>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                    <p>Discounts</p>
                </label>
                <div class="filter-box">
                    <a class="filter-box-icon"
                        href="javascript:void(0)"><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/filtter.svg')) ?></a>
                </div>
                <select class="select">
                    <option>Sort by</option>
                    <option>A to Z</option>
                    <option>Z to A</option>
                </select>
            </div>
        </div>
    </div>
</section>

<section class="area-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="area-section-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15243983.007440727!2d81.914063!3d21.125498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1617707645581!5m2!1sen!2sin"
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-lg-7 search_div">
                @forelse($getStore as $key=>$row)
                <div class="area-item-wrap">
                    <div class="area_img">
                        <div class="owl-carousel owl-theme" id="area-img-owl">
                            <div class="item">
                                <div class="area-img">
                                    <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="area-img">
                                    <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}" alt="">
                                </div>
                            </div>
                        </div>
                        <p class="disscount-box">%</p>
                    </div>
                    <div class="area_info">
                        <h6>{{$row->store_name}}</h6>
                        <p> <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/location.svg')) ?></span>
                            {{$row->store_address}}</p>
                        <p> <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/cream.svg')) ?></span> 
                            @forelse(@$row->storeCategory as $cat)
                                {{@$cat->CategoryData->name}}
                            @empty
                            @endforelse</p>
                        <div class="area_info_rating_wrap">
                            <ul>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li class="active"><i class="fas fa-star"></i></li>
                                <li><i class="fas fa-star"></i></li>
                            </ul>
                            <p>{{\BaseFunction::finalRating($row->id)}} <span> ({{@$row->storeRated->count()}} Reviews)</span></p>
                        </div>
                        <ul class="area_tag">
                            @forelse(@$row->storeCategory as $cat)
                            @if(@$cat->CategoryData->main_category == null)
                            <li>
                                {{@$cat->CategoryData->name}}
                            </li>
                            @endif
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    <div class="area_price">
                        <a class="wishlist_icon" href="javascript:void(0)"><i class="far fa-heart"></i></a>
                        <h5>{{$row->is_value}}</h5>
                    </div>
                </div>
                @empty
                @endforelse
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

    $('.main_cate').on('change',function(){
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
                $('.sub_cat').niceSelect('update');
            // }
          },
          error: function(error) {
            
            
          }
        });
    });

    $('.sub_cat').on('change',function(){
        var sub_cat = $(this).val();
        var cat = $('.main_cate').val();
        $.ajax({
          type: 'GET',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{URL::to('get_stores')}}",
          data: {cat:cat,sub_cat:sub_cat},
          success: function(response) { 
            // console.log(response.length);
            // if(response.length > 0){
                var html = "<option>Store</option>";
                $.each(response, function(i,data){
                    html += "<option value="+i+">"+data+"</option>";
                });

                $('.stores').html(html);
                // $('.stores').niceSelect('update');
            // }
          },
          error: function(error) {
            
            
          }
        });
    });

    $(".serach_btn").on('click',function(){
        var sub_cat = $('.sub_cat').val();
        var cat = $('.main_cate').val();
        var stores = $('.stores').val();
        var postal_code = $('input[name="postal_code"]').val();
        var date = $('input[name="date"]').val();
        $.ajax({
          type: 'POST',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{URL::to('cosmetic-area')}}",
          data: {"_token": "{{ csrf_token() }}",categories:cat,sub_cat:sub_cat,postal_code:postal_code,stores:stores,date:date,is_ajax:true},
          success: function(response) { 
            updateSearch(response);
          },
          error: function(error) {
            
            
          }
        });
    });

function updateSearch(data){
    var html;
    $.each(data,function(i,row){
        html += `<div class="area-item-wrap"><div class="area_img"><div class="owl-carousel owl-theme" id="area-img-owl"><div class="item"><div class="area-img">`;
        html += `<img src="{{URL::to('storage/app/public/store/')}}`+row.store_profile+`" alt="">`;
        html += `</div></div><div class="item"><div class="area-img">`;
        html += `<img src="{{URL::to('storage/app/public/store/')}}`+row.store_profile+`" alt="">`;
        html += `</div></div></div><p class="disscount-box">%</p></div><div class="area_info">`;
        html += `<h6>`+row.store_name+`</h6>`;
        html += `<p> <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/location.svg')) ?></span>`+row.store_address+`</p>`
        html += `<p> <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/icon/cream.svg')) ?></span>`; 
        $.each(row.store_category,function(i,cat){
            html += cat.category_data.name;
        });
        html += `</p><div class="area_info_rating_wrap">`;
        html += `<ul>`;
        html += `<li class="active"><i class="fas fa-star"></i></li>`;
         html += `<li class="active"><i class="fas fa-star"></i></li>`;
         html += `<li class="active"><i class="fas fa-star"></i></li>`;
         html += `<li class="active"><i class="fas fa-star"></i></li>`;
         html += `<li><i class="fas fa-star"></i></li>`;
         html += `</ul>`;
        html += `<p>`+row.rating+` <span> (`+row.rating_count+` Reviews)</span></p>`;
        html += `</div>`;
        html += `<ul class="area_tag">`;
        $.each(row.store_category,function(i,cat){
            if(cat.category_data.main_category == null){
                html += `<li>`;
                html += cat.category_data.name;
                html += `</li>`;
            }
        });
        html += `</ul>`;
        html += `</div>`;
        html += `<div class="area_price">`;
        html += `<a class="wishlist_icon" href="javascript:void(0)"><i class="far fa-heart"></i></a>`;
        html += `<h5>`+row.is_value+`</h5>`;
        html += `</div>`;
        html += `</div>`;

        $('.search_div').html(html);
    });

}
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
