<div class="col-lg-3 col-md-4 col-sm-6 pb-1 mb-3">
    <div class="card h-100 product-item bg-light">
        <div class="product-img position-relative overflow-hidden">
            <img class="img-fluid w-100" src="{{ asset('images/'. $barang->thumbnail) }}" alt="">
            <div class="product-action">
                @unlessrole('admin')
                <a class="btn btn-outline-dark btn-square cart-button" href="{{ route('add_cart')}}" data-slug="{{$barang->slug}}"><i class="fa fa-shopping-cart"></i></a>
                <a class="btn btn-outline-dark btn-square fav-button" href="{{ route('add_favorite') }}" data-slug="{{$barang->slug}}"><i class="far fa-heart"></i></a>
                @endunlessrole
                <a class="btn btn-outline-dark btn-square" href="{{ route('product_detail', [$barang->slug]) }}"><i class="fa fa-search"></i></a>
            </div>
        </div>
        {{-- <img src="{{ asset('images/'. $barang->thumbnail) }}" class="card-img-top" alt="..."> --}}
        <div class="card-body text-center d-flex flex-column">
            <a class="h6 text-decoration-none" href="{{ route('product_detail', [$barang->slug]) }}">{{$barang->nama}}</a>
            <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                @if ($barang->stok >0)<p class="mb-1">Tersedia: {{$barang->stok}}</p>@endif
                <h5>Rp. {{number_format($barang->harga_jual)}}</h5>
                <h6 class="text-muted">@if($barang->harga_asal || $barang->harga_asal != 0) <del>{{number_format($barang->harga_asal)}}</del>@endif</h6>
            </div>
            <div class="d-flex align-items-center justify-content-center mb-1">
                <?php $sisa = round(($barang->rating - floor($barang->rating))*10); ?>
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($barang->rating))
                    <small class="fa fa-star text-primary mr-1"></small>      
                    @elseif ($i == (floor($barang->rating) + 1))   
                        @if ($sisa == 0)
                        <small class="far fa-star text-primary mr-1"></small>      
                        @elseif ($sisa > 0 && $sisa < 4)
                        <small class="far fa-star text-primary mr-1"></small>    
                        @elseif ($sisa >= 4 && $sisa <= 6)
                        <small class="fa fa-star-half-alt text-primary mr-1"></small>
                        @elseif ($sisa > 6)
                        <small class="fa fa-star text-primary mr-1"></small>
                        @endif               
                    @else                    
                    <small class="far fa-star text-primary mr-1"></small>      
                    @endif
                @endfor
            </div>
            <small>({{$barang->total_review}} Reviews)</small>
        </div>
    </div>
</div>