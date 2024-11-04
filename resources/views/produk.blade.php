@extends('layouts.app')

@section('content')

    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="{{ asset('images/'.$barang->thumbnail) }}" alt="Image">
                        </div>
                        @foreach ($barang->images as $bi)
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="{{ asset('images/'.$bi->file) }}">
                        </div>                            
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{$barang->nama}}</h3>
                    <h6>Terjual: {{$barang->terjual}}</h6>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <?php $sisa = round(($barang->rating - floor($barang->rating))*10); ?>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($barang->rating))
                                <i class="fa fa-star text-primary"></i>      
                                @elseif ($i == (floor($barang->rating) + 1))   
                                    @if ($sisa == 0)
                                    <i class="far fa-star text-primary"></i>      
                                    @elseif ($sisa > 0 && $sisa < 4)
                                    <i class="far fa-star text-primary"></i>    
                                    @elseif ($sisa >= 4 && $sisa <= 6)
                                    <i class="fa fa-star-half-alt text-primary"></i>
                                    @elseif ($sisa > 6)
                                    <i class="fa fa-star text-primary"></i>
                                    @endif               
                                @else                    
                                <i class="far fa-star text-primary"></i>      
                                @endif
                            @endfor
                        </div>
                        <small class="pt-1">({{$barang->total_review}} Reviews)</small>
                    </div>
                    @if($barang->harga_asal || $barang->harga_asal != 0)<h6 class="text-muted">Rp. <del>{{number_format($barang->harga_asal)}}</del></h6>@endif
                    <h3 class="font-weight-semi-bold mb-4">Rp. {{number_format($barang->harga_jual)}}</h3>
                    <p class="mb-4">{!! $barang->deskripsi !!}</p>
                    <div class="d-flex mt-3 mb-2">
                        <h6 class="font-weight-semi-bold text-dark mr-3">Brand/Merek:</h6>
                        @if ($barang->brand) 
                        <a class="h6 text-decoration-none" href="">{{$barang->brand->nama}}</a>
                        @else
                        <a class="h6 text-decoration-none text-muted" href="" > - </a>
                        @endif
                    </div>
                    <div class="d-flex mt-3 mb-2">
                        <h6 class="font-weight-semi-bold text-dark mr-3">Kategori:</h6>
                        <a class="h6 text-decoration-none" href="">{{$barang->kategori->nama}}</a>
                    </div>
                    <div class="d-flex mt-3 mb-2">
                        <h6 class="font-weight-semi-bold text-dark mr-3">Stok:</h6>
                        <p class="h6 text-decoration-none">@if($barang->stok == 0 ) Habis @else {{$barang->stok}} @endif</a>
                    </div>

                    @unlessrole('admin')
                    <div class="d-flex align-items-center mb-4 pt-2">              
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control bg-secondary border-0 text-center qty" value="@if($barang->stok == 0 ) 0 @else 1 @endif" max="{{$barang->stok}}">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <a class="btn btn-primary px-3 cart-button2 @if($barang->stok == 0 ) disabled @endif" href="{{ route('add_cart2') }}" data-slug="{{$barang->slug}}"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</a>                        

                    </div>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <button class="btn btn-primary px-3 fav-button" href="{{ route('add_favorite') }}" data-slug="{{$barang->slug}}"><i class="fas fa-heart mr-1"></i>
                            </i> Add To Favorite</button>
                    </div>
                    @endunlessrole

                    <div class="d-flex pt-2 mt-auto">
                        <strong class="text-dark mr-2">Share:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="whatsapp://send?text={{ route('product_detail', [$barang->slug]) }}">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fas fa-copy"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-3">Reviews ({{$barang->total_review}})</a>
                    </div>
                    <div class="tab-content">                        
                        <div class="tab-pane fade show active" id="tab-pane-3">
                            <div class="row">
                                <div id="comment-container" class="col-md-6" data-url="{{ route('fetch_comment', ['slug'=>$barang->slug]) }}">                                    
                                    <div class="w-100 text-center">
                                        <i class="fas fa-circle-notch fa-2x fa-spin"></i>                                       
                                    </div>
                                </div>
                                @if (Auth::check())
                                <div class="col-md-6">
                                    @if ($myreview != null && count($myreview) > 0)
                                    <h4 class="mb-4">Ulasan Saya</h4>
                                    <div class="owl-carousel owl-theme">  
                                        @foreach ($myreview as $r)
                                        <div class="media mb-4">
                                            <div class="media-body">
                                                <h6><small><i>{{ \Carbon\Carbon::parse($r->created_at)->translatedFormat('d F Y') }}</i></small></h6>
                                                <div class="text-primary mb-2">
                                                    <?php $sisa = round(($r->rating - floor($r->rating))*10); ?>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($r->rating))
                                                        <i class="fa fa-star"></i>      
                                                        @elseif ($i == (floor($r->rating) + 1))   
                                                            @if ($sisa == 0)
                                                            <i class="far fa-star"></i>      
                                                            @elseif ($sisa > 0 && $sisa < 4)
                                                            <i class="far fa-star"></i>    
                                                            @elseif ($sisa >= 4 && $sisa <= 6)
                                                            <i class="fa fa-star-half-alt"></i>
                                                            @elseif ($sisa > 6)
                                                            <i class="fa fa-star"></i>
                                                            @endif               
                                                        @else                    
                                                        <i class="far fa-star"></i>      
                                                        @endif
                                                    @endfor
                                                </div>
                                                @if ($r->review)
                                                <p>{{$r->review}}</p>                                                
                                                @endif
                                            </div>
                                        </div> 
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_line')
    <script>
        var url = $('#comment-container').data('url');
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('#comment-container').html(response.html);
                ajax_page_btn();
            }
        });

        function ajax_page_btn(){
            $('.ajax-page-btn').click(function (e) { 
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    beforeSend: function() {
                        $('#comment-container').html('<div class="w-100 text-center"><i class="fas fa-circle-notch fa-2x fa-spin"></i></div>');
                    },
                    success: function (response) {
                        $('#comment-container').html(response.html);
                        ajax_page_btn();
                    }
                });
            });
        }
        $('.owl-carousel').owlCarousel({
            autoplay: true,
            loop:true,
            items:1
        })
    </script>
@endsection