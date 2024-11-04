<div class="col-lg-3 col-md-4 col-sm-6 pb-1">
    <a class="text-decoration-none" href="{{ route('shop_query', ['kategori[]'=>$kategori->slug]) }}">
        <div class="cat-item d-flex align-items-center mb-4">
            <div class="overflow-hidden" style="width: 100px; height: 100px;">
                <img class="img-fluid" src="{{ asset('images/'.$kategori->thumbnail) }}" alt="{{$kategori->nama}}">
            </div>
            <div class="flex-fill pl-3">
                <h6>{{$kategori->nama}}</h6>
                <small class="text-body">{{count($kategori->barang)}} Produk</small>
            </div>
        </div>
    </a>
</div>