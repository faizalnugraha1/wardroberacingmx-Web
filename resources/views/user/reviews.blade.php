@extends('layouts.app')

@section('csscustom')
    <style>
        
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row px-xl-5">
        @include('components.user-sidebar')      


        <div class="col-lg-9 col-md-8">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Ulasan Saya</span></h5>

            <div class="row pb-3">
                @foreach ($data as $inv)
                    {{-- <x-product-card2 :barang="$b->barang"></x-product-card2> --}}
                    <div class="col-12 bg-light mb-3">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-3 border-right">
                                <h6>{{$inv[0]->invoice->kode_invoice}} </h6>
                            </div>
                            <div class="col-9">
                                <div class="row gy-5">
                                    @foreach ($inv as $b)
                                    <div class="col-12 @if($loop->last) mb-0 @else mb-4 @endif">
                                        <div class="row">
                                            {{-- <div class="col-2">
                                                <div class="square-img" style="background-image: url({{ asset('images/'. $b->barang->thumbnail) }})"></div>
                                            </div>
                                            <div class="col-7">
                                                <a class="h6 text-decoration-none d-block" href="{{ route('product_detail', [$b->barang->slug]) }}">{{$b->barang->nama}}</a>
                                                <small>Jumlah: x{{$b->qty}}</small>
                                            </div> --}}
                                            <div class="col-9">
                                                <div class="media">
                                                    <img src="{{ asset('images/'. $b->barang->thumbnail) }}" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                                    <div class="media-body">
                                                        <a class="h6 text-decoration-none d-block" href="{{ route('product_detail', [$b->barang->slug]) }}">{{$b->barang->nama}}</a>
                                                        <small>Jumlah: x{{$b->qty}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">       
                                                @if ($b->rating == null)
                                                    <button class="btn-review ml-auto btn btn-primary" data-url="{{ route('user.review.create', ['id'=>$b->enc_id()]) }}">Beri Ulasan</button>     
                                                @else                                               
                                                    <button class="btn-review ml-auto btn btn-primary" data-url="{{ route('user.review.view', ['id'=>$b->enc_id()]) }}">Lihat Ulasan</button>     
                                                @endif                                         
                                            </div>
                                        </div>
                                    </div>   
                                    @endforeach
                                </div>
                            </div>
                            </div>                    
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <div class="col-12 justify-content-center">
                {{ $data->links() }}
            </div> --}}

            @if (count($data) == 0)
                <div class="col-12">
                    <div class="align-center text-center">     
                        <h2 class="text-center mb-1">Oooops..</h2>
                        <h5 class="text-center mb-3">Sepertinya Anda tidak memiliki produk untuk di Review.</h5>
                        <img src="{{ asset('empty.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection

@section('modal')
<div id="modal_container">
</div> 
@endsection

@section('script_line')
<script>
    function init_review() 
    {  
        $('textarea.auto-height').each(function () {
            }).on('input', function () {
            this.style.overflow = 'hidden';
            this.style.height = 0;
            this.style.height = this.scrollHeight + 'px';
        });

        $('.owl-carousel').owlCarousel({
            autoplay: true,
            margin:10,
            loop:true,
            items:1
        })

        $(".needs-validation").submit(function () {
            var form = $(this);
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.addClass("was-validated");
        });

        if($("#update_review").length){
        //     $("#update_review").submit(function () {
        //     var form = $(this);
        //     if (form[0].checkValidity() === false) {
        //         event.preventDefault();
        //         event.stopPropagation();
        //     } else {
        //         event.preventDefault();
        //         Swal.fire({
        //             title: `Update Ulasan?`,
        //             text: "Anda hanya bisa melakukan update ulasan 1 kali.",
        //             icon: 'question',
        //             buttons: true,
        //             showCancelButton: true,
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 form.submit();
        //             }
        //         });
        //     }
        //     form.addClass("was-validated");
        // });
        $('#submit_update').click(function (e) { 
            e.preventDefault();
            var form = $(this).parents('form');
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.preventDefault();
                Swal.fire({
                    title: `Update Ulasan?`,
                    text: "Anda hanya bisa melakukan update ulasan 1 kali.",
                    icon: 'question',
                    buttons: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
            form.addClass("was-validated");
        });
        }
    }
    init_review();

    $('.btn-review').click(function (e) { 
        e.preventDefault();
        // alert($(this).data('url'));
        $.ajax({
            type: "GET",
            url: $(this).data('url'),
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $('#modal_container').html(response.modal);
                $('#review_modal').modal('show');
                init_review();
            }
        });
    });
</script>
@endsection