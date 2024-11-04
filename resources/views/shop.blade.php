@extends('layouts.app')

@section('content')

    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">          

            <form id="filter_form" action="{{ route('shop_query') }}">
                @if (Request::has('q'))<input type="hidden" name="q" value="{{request()->get('q')}}">@endif
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter Kategori</span></h5>
                <div class="bg-light p-4 mb-30">
                    @foreach ($kategori as $k)                            
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" name="kategori[]" class="custom-control-input" @if (Request::has('kategori') && in_array($k->slug, request()->get('kategori'))) checked @endif value="{{$k->slug}}" id="{{$k->slug}}">
                        <label class="custom-control-label" for="{{$k->slug}}">{{$k->nama}}</label>
                    </div>
                    @endforeach
                </div>

                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter Brand</span></h5>
                <div class="bg-light p-4 mb-30">
                    @foreach ($brand as $b)                            
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" name="brand[]" class="custom-control-input" @if (Request::has('brand') && in_array($b->slug, request()->get('brand'))) checked @endif value="{{$b->slug}}" id="{{$b->slug}}">
                        <label class="custom-control-label" for="{{$b->slug}}">{{$b->nama}}</label>
                    </div>
                    @endforeach
                </div>
                @if (Request::has('sort'))<input type="hidden" name="sort" value="{{request()->get('sort')}}">@endif
                @if (Request::has('show') && request()->routeIs('shop_query'))<input type="hidden" name="show" value="{{request()->get('show')}}">@endif
                    
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary px-3"><i class="fas fa-filter mr-1"></i></i> Terapkan Filter</button>
                </div>
            </form>
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="ml-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item sort-btn" data-value="terbaru" href="#" a>Terbaru</a>
                                        {{-- <a class="dropdown-item sort-btn" data-value="terlaris" href="#">Terlaris</a> --}}
                                        <a class="dropdown-item sort-btn" data-value="terbaik" href="#">Rating terbaik</a>
                                        <a class="dropdown-item sort-btn" data-value="termurah" href="#" a>Murah ke mahal</a>
                                        <a class="dropdown-item sort-btn" data-value="termahal" href="#" a>Mahal ke murah</a>
                                    </div>
                                </div>
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Showing</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item @if (request()->routeIs('shop_query')) show-btn @endif" data-value="24" href="@if (request()->routeIs('shop')) ?show=24 @endif">24</a>
                                        <a class="dropdown-item @if (request()->routeIs('shop_query')) show-btn @endif" data-value="48" href="@if (request()->routeIs('shop')) ?show=48 @endif">48</a>
                                        <a class="dropdown-item @if (request()->routeIs('shop_query')) show-btn @endif" data-value="96" href="@if (request()->routeIs('shop')) ?show=96 @endif">96</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (count($barang) == 0)
                        <div class="col-12">
                            <div class="align-center text-center">     
                                <h2 class="text-center mb-1">Oooops..</h2>
                                <h5 class="text-center mb-3">Sepertinya tidak ada Produk yang ditemukan</h5>
                                <img src="{{ asset('empty.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                    
                    @else

                    @if ($single_brand != null)
                    <div class="col-lg-4 col-md-4 col-sm-6 pb-1 mb-3 justify-content-center d-flex align-items-center">
                        <img src="{{ asset('images/'.$single_brand->logo) }}" alt="{{$single_brand->nama}}" style="width: 80%">
                    </div>
                    @endif
                    
                    @foreach ($barang as $b)
                        <x-product-card2 :barang="$b"></x-product-card2>
                    @endforeach

                    <div class="col-12 justify-content-center">
                        {{ $barang->links() }}
                    </div>

                    @endif
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->

@endsection

@section('script_line')
<script>
    $('.sort-btn').click(function (e) { 
        e.preventDefault();
        var val = $(this).data('value');
        var form = $('#filter_form');
        form.append(`<input type="hidden" name="sort" value="${val}" /> `);
        form.submit();
    });

    $('.show-btn').click(function (e) { 
        e.preventDefault();
        var val = $(this).data('value');
        var form = $('#filter_form');
        form.append(`<input type="hidden" name="show" value="${val}" /> `);
        form.submit();
    });
</script>
@endsection