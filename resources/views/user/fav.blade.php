@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="row px-xl-5">
        @include('components.user-sidebar')      


        <div class="col-lg-9 col-md-8">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">My Favourite</span></h5>
            <div class="col-12 pb-1">
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div class="btn-group ml-auto">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Showing</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="?show=24">24</a>
                            <a class="dropdown-item" href="?show=36">36</a>
                            <a class="dropdown-item" href="?show=72">72</a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row pb-3">
                @foreach ($data as $b)
                    <x-product-card2 :barang="$b->barang"></x-product-card2>
                @endforeach
            </div>

            <div class="col-12 justify-content-center">
                {{ $data->links() }}
            </div>

            @if (count($data) == 0)
                <div class="col-12">
                    <div class="align-center text-center">     
                        <h2 class="text-center mb-1">Oooops..</h2>
                        <h5 class="text-center mb-3">Sepertinya Anda tidak memiliki produk Favorit.</h5>
                        <img src="{{ asset('empty.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection

@section('script_line')

@endsection