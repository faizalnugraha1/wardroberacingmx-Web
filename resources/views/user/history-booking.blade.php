@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="row px-xl-5">
        @include('components.user-sidebar')      


        <div class="col-lg-9 col-md-8">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Booking History</span></h5>
            <div class="col-12 pb-1">
                <table class="table table-light table-borderless table-hover text-center mb-0">
                    <tbody class="align-middle">

                        @foreach ($data as $d)                            
                        <tr>
                            <td class="align-middle"> 
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <h6>{{$d->booking_id}} </h6>
                                    <h4>{{$d->model_motor}}</h4>
                                </div>
                            </td>
                            <td class="align-middle"><span class="badge {{$d->flag->badge()}} badge-pill">{{$d->flag->status}}</span></td>
                            <td class="align-middle">
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <p class="mb-1">Jadwal:</p>
                                    <h6 class="mb-0">{{$d->tanggal_booking}} </h6>
                                    <h6 class="mb-0">{{$d->jam_booking}}</h6>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column align-items-center justify-content-center my-auto">
                                    <p class="mb-1">Dibuat:</p>
                                    <h6 class="mb-0">{{$d->created_at}}</h6>
                                    {{-- <h6 class="mb-0">{{$d->jam_booking}}</h6> --}}
                                </div>
                            </td>
                            {{-- <td class="align-middle"><button class="btn btn-icon btn-warning" data-toggle="modal" data-target="#booking_detail"><i class="fa-solid fa-eye"></i></button></td> --}}
                            <td class="align-middle"><button class="detail-show btn btn-icon btn-warning" data-url="{{ route('user.booking.show', ['id'=>$d->booking_id]) }}"><i class="fa-solid fa-eye"></i></button></td>
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
                        <h5 class="text-center mb-3">Sepertinya Anda tidak memiliki data Booking Bengkel</h5>
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
</script>
@endsection