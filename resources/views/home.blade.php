@extends('layouts.app')

@section('content')

    <!-- Carousel Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#header-carousel" data-slide-to="1"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item position-relative active" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset('assets/img/IMG_20191111_080914_099.jpg') }}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Motocross Apparel</h1>
                                    {{-- <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item position-relative" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset('assets/img/IMG_20191111_081823_841.jpg') }}" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Motocross Spare Parts</h1>
                                    {{-- <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ asset('assets/img/152733051_266509604918467_280705824963691822_n.jpg') }}" alt="">
                    <div class="offer-text">
                        {{-- <h6 class="text-white text-uppercase">Terlengkap</h6> --}}
                        <h3 class="text-white mb-3">Terlengkap</h3>
                        {{-- <a href="" class="btn btn-primary">Shop Now</a> --}}
                    </div>
                </div>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{{ asset('assets/img/140605224_1052164315294551_656674699500505436_n.jpg') }}" alt="">
                    <div class="offer-text">
                        {{-- <h6 class="text-white text-uppercase">100% Original</h6> --}}
                        <h3 class="text-white mb-3">100% Original</h3>
                        {{-- <a href="" class="btn btn-primary">Shop Now</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Kategori</span></h2>
        <div class="row px-xl-5 pb-3">
            @foreach ($kategori as $k)
            <x-kategori-card :kategori="$k"></x-kategori-card>
            @endforeach
        </div>
    </div>

    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Barang tersedia</span></h2>
        <div class="row px-xl-5">
            @foreach ($barang as $b)
                <x-product-card :barang="$b"></x-product-card>
            @endforeach
        </div>
    </div>
    <!-- Products End -->


    <!-- Offer Start -->
    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('assets/img/16599663290282.jpg') }}" alt="">
                    <div class="offer-text">
                        {{-- <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('assets/img/1659966329049.jpg') }}" alt="">
                    <div class="offer-text">
                        {{-- <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-primary">Shop Now</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer End -->


    <!-- Products Start -->
    @if (count($terlaris) > 0)        
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Paling Banyak terjual</span></h2>
        <div class="row px-xl-5">
            @foreach ($terlaris as $t)
            <x-product-card :barang="$t->barang"></x-product-card>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Products End -->


    <!-- Vendor Start -->
    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Brand</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    @foreach ($brand as $br)
                    <div class="bg-light p-4">
                        <img src="{{ asset('images/'.$br->logo) }}" alt="{{$br->nama}}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->

@endsection