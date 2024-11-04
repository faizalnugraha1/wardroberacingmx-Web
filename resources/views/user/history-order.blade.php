@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="row px-xl-5">
        @include('components.user-sidebar')      


        <div class="col-lg-9 col-md-8">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order History</span></h5>
            <div class="col-12 pb-1">
                <table class="table table-light table-borderless table-hover text-center mb-0">
                    <tbody class="align-middle">

                        @foreach ($data as $d)                            
                        <tr>
                            <td class="align-middle"> 
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <h5>{{$d->kode_invoice}} </h5>
                                    {{-- <h4>{{$d->model_motor}}</h4> --}}
                                </div>
                            </td>
                            <td class="align-middle"><span class="badge {{$d->flag->badge()}} badge-pill">{{$d->flag->status}}</span></td>
                            <td class="align-middle">
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <p class="mb-1">Total:</p>
                                    <h6 class="mb-0">Rp. {{number_format($d->jumlah)}} </h6>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <p class="mb-1">Dibuat:</p>
                                    <h6 class="mb-0">{{$d->created_at}}</h6>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="btn-toolbar justify-content-center" role="group">
                                @if($d->flag->id == 14) 
                                <form action="{{ route('user.order.finish', ['id'=>$d->kode_invoice]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="finish-order btn btn-icon btn-success mr-1"><i class="far fa-check-circle"></i></button> 
                                </form>
                                @endif
                                <button class="detail-show btn btn-icon btn-warning" data-url="{{ route('user.order.show', ['id'=>$d->kode_invoice]) }}"><i class="fa-solid fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
  

            <div class="col-12 justify-content-center mt-3">
                {{ $data->links() }}
            </div>

            @if (count($data) == 0)
                <div class="col-12">
                    <div class="align-center text-center">     
                        <h2 class="text-center mb-1">Oooops..</h2>
                        <h5 class="text-center mb-3">Sepertinya Anda tidak memiliki data Order</h5>
                        <img src="{{ asset('no_data.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection

@section('modal')
    <div id="temp_modal">

    </div>
@endsection

@section('script_line')
<script>
    $('.detail-show').click(function (e) { 
        e.preventDefault();
        var url = $(this).data('url');
        // alert(url);
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                $('#temp_modal').html(response.modal);
                $('#detail').modal('show');
            }
        });
    });

    $(".finish-order").click(function (event) {
    var form = $(this).closest("form");    
    event.preventDefault();
    Swal.fire({
        title: `Anda yakin?`,
        text: "Pesanan telah diterima dan selesaikan pesanan?",
        icon: 'question',
        buttons: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
  });
</script>
@endsection