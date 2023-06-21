@extends('layouts.front')
@section('front_title')
    Cosmetic Advantages
@endsection
@section('front_css')
@endsection
@section('front_content')
    <!-- Home-banner -->
    <section class="home-banner-section">
        <div class="home-banner bpartners-banner cosmetics-banner">
            <div class="home-banner-img cosmetics-banner-imgs">
                <img src="{{URL::to('storage/app/public/Frontassets/images/cosmetics-advantages-banner.jpg')}}" alt="">
            </div>
            <div class="banner-text-info">
                <h3>Recommended for you</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum soluta quos ipsum dolore suscipit
                    reiciendis fugit quasi error tenetur pariatur!</p>
            </div>
        </div>
    </section>
    <!-- Filter Section -->
    <section class="pt-0">
        <div class="container">
            <div class="mb-5">
                <h3 class="areas-title">Berlin Saloon </h3>
                <p class="areas-title-text"><span class="store_count">{{count($getStore)}}</span> Result found</p>
            </div>
            <div class="row filters-rows">
                <div class="col-lg-12">
                    <div class="filter_data">
                        @foreach($getStore as $row)
                            <div class="cosmetics-area-item-wrap">
                                <div class="cosmetics-area-item-img">
                                    <a href="{{URL::to('cosmetic/'.$row->slug)}}">
                                        @if(file_exists(storage_path('app/public/store/'.$row->store_profile)) && $row->store_profile != '')
                                            <img src="{{URL::to('storage/app/public/store/'.$row->store_profile)}}"
                                                 alt=""
                                                 style="object-fit:fill !important;">
                                        @else
                                            <img src="{{URL::to('storage/app/public/default/default_store.jpeg')}}"
                                                 alt=""
                                                 style="object-fit:fill !important;">
                                        @endif
                                    </a>
                                </div>
                                <div class="cosmetics-area-item-info">
                                    <h5>
                                        <a href="{{URL::to('cosmetic/'.$row->slug)}}"> {{$row->store_name}} </a><span><i
                                                class="fas fa-star"></i> {{$row->rating}}</span>
                                        <span><i class="fas fa-user"></i> 128</span>
                                    </h5>
                                    <p>
                                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/location.svg')) ?></span>
                                        {{$row->store_address}}
                                    </p>
                                    <p>
                                        <span><?php echo file_get_contents(URL::to('storage/app/public/Frontassets/images/eyelash.svg')); ?></span>
                                        Cosmetic
                                    </p>
                                    <ul>
                                        @foreach($row->categories as $item)
                                            <li>{{@$item->CategoryData->name}}</li>
                                        @endforeach
                                        <li class="more">More</li>
                                    </ul>
                                </div>
                                <div class="cosmetics-area-item-wishlist">
                                    <a href="javascript:void(0)"
                                       class="wishlist-icon {{$row->isFavorite == 'true' ? 'active' : ''}} favorite"
                                       data-id="{{$row->id}}"><i class="far fa-heart"></i></a>
                                    <p>{{$row->is_value}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="register-cosmetic-section ">
        <div class="container">
            <div class="register-cosmetic-areas">
            <span class="register-cosmetic-lines"><img
                    src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
                <span class="register-cosmetic-lines2"><img
                        src="{{URL::to('storage/app/public/Frontassets/images/area-line.svg')}}" alt=""></span>
                <div class="register-cosmetic-areas-info">
                    @if(!Auth::check())
                        <h6>Register here to be able to use all the advantages</h6>

                        <a href="javascript:void(0)" class="btn btn-white" data-toggle="modal"
                           data-target="#register-modal">Register Now</a>

                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
@section('front_js')
    <script>
        $("body").addClass("cosmetics-body");
        $("body").addClass("cosmetics-area-body");

        $(document).on('click', '.favorite', function () {
            var auth = '{{Auth::check()}}';

            if (auth == '1') {
                var id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: "json",
                    url: "{{URL::to('favorite-store')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success: function (response) {
                        var status = response.status;
                        var type = response.data;
                        if (status == 'true') {
                            if (type == 'remove') {
                                $('.wishlist-icon[data-id=' + id + ']').removeClass('active');
                            } else if (type == 'add') {
                                $('.wishlist-icon[data-id=' + id + ']').addClass('active');
                            }
                        } else {

                        }

                    },
                    error: function (e) {

                    }
                });
            } else {
                $('#login-modal').modal('toggle');
            }
        });

    </script>
@endsection
