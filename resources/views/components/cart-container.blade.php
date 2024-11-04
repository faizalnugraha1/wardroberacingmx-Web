<div class="row px-xl-5">
    @if (count($data) == 0)
    <div class="col-lg-8">
        <div class="align-center text-center">     
            <h2 class="text-center mb-1">Oooops..</h2>
            <h5 class="text-center mb-3">Sepertinya tidak ada produk dalam keranjang</h5>
            <img src="{{ asset('empty.png') }}" class="img-fluid" alt="">
        </div>
    </div>
    @else
    <div class="col-lg-8 table-responsive mb-5">
        <table class="table table-light table-borderless table-hover text-center mb-0">
            <thead class="thead-dark">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody class="">
                @php
                    $total = 0;
                @endphp
                @foreach ($data as $d)                        
                <tr data-val="{{$d->enc_id()}}">
                    <td class="align-middle text-left">{{$d->barang->nama}}</td>
                    <td class="align-middle">Rp. {{number_format($d->barang->harga_jual)}}</td>
                    <td class="align-middle">
                        <div class="input-group quantity2 mx-auto" data-url="{{ route('cart.update') }}" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-minus" @if($d->qty == 1) disabled @endif>
                                <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center" value="{{$d->qty}}" min="1" max="{{$d->barang->stok}}" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-plus" @if($d->qty == $d->barang->stok) disabled @endif>
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                    @php
                        $subtotal = $d->barang->harga_jual * $d->qty;     
                        $total += $subtotal;
                    @endphp
                    <td class="align-middle">Rp. {{number_format($subtotal)}}</td>
                    <td class="align-middle"><button class="btn btn-sm btn-danger del_cart" data-url="{{ route('cart.delete') }}"><i class="fa fa-times"></i></button></td>
                </tr>
                @endforeach

                @if ($kosong->count() > 0)                            
                <tr class="kosong_devider">
                    <td colspan="5">Stok Habis</td>
                </tr>
                @foreach ($kosong as $d)                        
                <tr data-val="{{$d->enc_id()}}">
                    <td class="align-middle text-left">{{$d->barang->nama}}</td>
                    <td class="align-middle">Rp. {{number_format($d->barang->harga_jual)}}</td>
                    <td class="align-middle">
                        <div class="input-group quantity2 mx-auto" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-minus" disabled>
                                <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center" value="0" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-primary btn-plus" disabled>
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle"><button class="btn btn-sm btn-danger del_cart" data-url="{{ route('cart.delete') }}"><i class="fa fa-times"></i></button></td>
                </tr>
                @endforeach
                @endif

                
            </tbody>
        </table>
    </div>
    @endif
    <div class="col-lg-4">
        <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
        <div class="bg-light p-30 mb-5">
            <div class="pt-2">
                <div class="d-flex justify-content-between mt-2">
                    <h5>Total</h5>
                    <h5>Rp. <span id="cart_total">{{number_format($subtotalbe)}}</span></h5>
                </div>
                <a class="btn btn-block btn-primary font-weight-bold my-3 py-3 @if (count($data) == 0) disabled @endif" href="{{ route('checkout') }}" >Checkout</a>
            </div>
        </div>
    </div>
</div>