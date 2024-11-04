@extends('admin.appadmin')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/chocolat/dist/css/chocolat.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">

@endsection

@section('maincontent')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
        <h1>Online Order</h1>
        <a href="{{ route('admin.order.refresh') }}" class="ml-auto" class="text-decoration-none"><i class="fas fa-sync fa-2x"></i></a>
    </div>
    <div class="section-body">
      
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Selesai</h4>
                </div>
                <div class="card-body">
                    {{$selesai}}
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Transaksi</h4>
                </div>
                <div class="card-body">
                  {{$transaksi}}
                </div>
                </div>
            </div>
            </div>  
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-shopping-bag"></i>
                  </div>
                  <div class="card-wrap">
                  <div class="card-header">
                      <h4>Pesanan Masuk Hari Ini</h4>
                  </div>
                  <div class="card-body">                    
                    {{$order_today}}
                  </div>
                  </div>
              </div>
            </div>                
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-cash-register"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Transaksi Hari Ini</h4>
                </div>
                <div class="card-body">
                  {{$transaksi_today}}
                </div>
                </div>
            </div>
            </div>                  
        </div>

        <div class="row">
            <div class="col-12">

                @include('admin.component.alert-error')

                {{-- <div id="badge-filter" class="badges">
                  <a href="{{ route('admin.booking.index', ['show'=>'all']) }}" class="badge badge-primary">Semua</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'menunggu']) }}" class="badge badge-warning">Menunggu Approve</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'diterima']) }}" class="badge badge-secondary">Diterima</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'pengerjaan']) }}" class="badge badge-info">Pengerjaan</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'batal']) }}" class="badge badge-danger">Tolak/Batal</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'selesai']) }}" class="badge badge-success">Selesai</a>
                </div> --}}
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-order">
                              <thead  class="text-center">
                                <tr>
                                  <th>Kode Invoice</th>
                                  <th>Id Pembayaran</th>
                                  <th>Status Pembayaran</th>
                                  <th>Status</th>
                                  <th>Tanggal Order</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class="text-center">
                                @foreach($data as $key=>$d)
                                <tr>
                                  <td class="align-middle">{{ strtoupper($d->kode_invoice)}}</td>
                                  <td class="align-middle">
                                    <a target="_blank" href="https://dashboard.sandbox.midtrans.com/transactions/{{ $d->midtrans_transaksi_id }}">{{ $d->midtrans_transaksi_id }}</a>
                                  </td>
                                  <td class="align-middle">
                                    <span class="badge {{ $d->midtrans_badge() }}">{{ $d->midtrans_status }}</span>
                                  </td>
                                  <td class="align-middle">
                                    <span class="badge {{ $d->flag->badge() }}">{{ $d->flag->status }}</span>
                                  </td>
                                  <td class="align-middle">{{ $d->created_at }}</td>
                                  <td class="align-middle">
                                    <div class="btn-toolbar justify-content-center" role="group">
                                      @if ($d->flag_id == 10)
                                      <form action="{{route('admin.order.confirm', ['invoice_id'=>$d->kode_invoice])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="confirm btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Approve"><i class="fas fa-check"></i></button>
                                      </form>
                                      @endif
                                      @if (in_array($d->flag_id, [12,13]))
                                      {{-- <a href="" class="checkout btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" data-url="{{route('admin.booking.checkout', ['booking_id' => $d->booking_id])}}" data-original-title="Selesaikan"><i class="fas fa-check"></i></a> --}}
                                      <button class="next btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Lanjutkan" data-url="{{ route('admin.order.lanjutkan', ['invoice_id'=>$d->kode_invoice]) }}"><i class="fas fa-check"></i></button>
                                      @endif
                                      @if ($d->flag_id == 14)
                                      <button class="next btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Edit" data-url="{{ route('admin.order.lanjutkan', ['invoice_id'=>$d->kode_invoice]) }}"><i class="fas fa-edit"></i></button>
                                      @endif
                                      @if ($d->flag_id < 14 && !in_array($d->flag_id, [8,9,11]) && $d->midtrans_status)
                                      <form action="{{route('admin.order.cancel', ['invoice_id'=>$d->kode_invoice])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="cancel btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Tolak/Batalkan" data-nomor="{{$d->user->no_hp}}" data-email="{{$d->user->email}}"><i class="fas fa-times"></i></button>
                                      </form>
                                      @endif
                                      <a href="{{ route('admin.order.detail', ['invoice_id'=>$d->kode_invoice]) }}" class="view btn btn-icon btn-primary-shop" data-toggle="tooltip" data-placement="top" data-original-title="Detail"><i class="fas fa-eye"></i></a>
                                    </div>
                                  </td>
                                </tr>  
                                @endforeach                  
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>            
    </div>
  </section>
</div>
@endsection

@section('modalcontent')

<div id="modal_container">

</div>

<div class="modal fade" id="viewOrder" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
</div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/dropify/dist/js/dropify.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
@endsection

@section('scriptpage')

<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
{{-- <script src="{{ asset('assets/js/views/booking.js') }}"></script> --}}

@endsection

@section('scriptline')
<script>
  $('.view').click(function (e) { 
    e.preventDefault();
    var url = $(this).attr('href');
    // alert(url);
    $.ajax({
      type: "GET",
      url: url,
      dataType: "json",
      success: function (response) {
        $('#modal_container').html(response.modal);
        $('#detail').modal('show');
      }
    });
  });

  $(".confirm").click(function (event) {
    var form = $(this).closest("form");    
    event.preventDefault();
    Swal.fire({
        title: `Terima dan Proses Pesanan?`,
        // text: "Jika Kategori ini dihapus maka tidak dapat dikembalikan lagi.",
        icon: 'question',
        buttons: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
  });

  $(".cancel").click(function (event) {
    var form = $(this).closest("form");
    // var hp = '+62' + $(this).data('nomor').substring(1);
    var hp = $(this).data('nomor');
    var email = $(this).data('email');
    event.preventDefault();
    Swal.fire({
        title: `Tolak/Batalkan Pesanan?`,
        // text: "Jika Pesanan ditolak maka uang akan disimpan (jika sudah membayar).",
        html:`Jika Pesanan ditolak maka uang akan disimpan (jika sudah membayar). <br/> Hubungi pelanggan: <a href="tel:${hp}"><i class="fas fa-phone fa-lg"></i></a> <a href=" https://wa.me/62${hp.substring(1)}"><i class="fab fa-whatsapp fa-lg"></i></a> <a href="mailto: ${email}"><i class="fas fa-envelope fa-lg"></i></a> <br/> ${hp} <br/> ${email} `,
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Keterangan penolakan/pembatalan',
        inputPlaceholder: 'Tulis keterangan...',
        showCancelButton: true,
        buttons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.append(`<input type="hidden" name="keterangan" value="${result.value}" /> `);
            form.submit();
            // alert(result.value);
        }
    });
  });

  $(".next").click(function (event) {
    event.preventDefault();
    var url = $(this).data('url');
    $.ajax({
      type: "GET",
      url: url,
      dataType: "json",
      success: function (response) {
        $('#modal_container').html(response.modal);
        $('#temp_modal').modal('show');
      }
    });
  });
</script>
@endsection