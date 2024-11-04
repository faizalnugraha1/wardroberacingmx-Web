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
        <h1>Booking Bengkel</h1>
        <a href="#" class="btn btn-icon btn-info  ml-auto" data-toggle="modal" data-target="#printModal"><i class="fas fa-print"></i></a> 
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
                <div class="card-icon bg-warning">
                  <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Menunggu Konfirmasi</h4>
                </div>
                <div class="card-body">
                  {{$menunggu}}
                  {{-- {{count($menunggu)}} --}}
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="fas fa-calendar"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Jadwal Booking Hari Ini</h4>
                </div>
                <div class="card-body">
                  {{$today}}
                  {{-- {{count($today)}} --}}
                </div>
                </div>
            </div>
            </div>                  
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-times-circle"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Jadwal Dibatalkan</h4>
                </div>
                <div class="card-body">
                  {{$batal}}
                  {{-- {{count($batal)}} --}}
                </div>
                </div>
            </div>
            </div>                  
        </div>

        <div class="row">
            <div class="col-12">

                @include('admin.component.alert-error')
                <div id="badge-filter" class="badges">
                  <a href="{{ route('admin.booking.index', ['show'=>'all']) }}" class="badge badge-primary">Semua</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'menunggu']) }}" class="badge badge-warning">Menunggu Approve</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'diterima']) }}" class="badge badge-secondary">Diterima</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'pengerjaan']) }}" class="badge badge-info">Pengerjaan</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'batal']) }}" class="badge badge-danger">Tolak/Batal</a>
                  <a href="{{ route('admin.booking.index', ['show'=>'selesai']) }}" class="badge badge-success">Selesai</a>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                              <thead  class="text-center">
                                <tr>
                                  <th>Kode Booking</th>
                                  <th>Atas Nama</th>
                                  <th>Kebutuhan</th>
                                  <th>Tanggal-Jam</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class="text-center">
                                @foreach($data as $key=>$d)
                                <tr>
                                  <td class="align-middle">{{ strtoupper($d->booking_id)}}</td>
                                  <td class="align-middle">{{ $d->user->name }}</td>
                                  <td class="align-middle">{{ $d->kebutuhan }}</td>
                                  <td class="align-middle">{{ $d->tanggal_booking }} - {{ $d->jam_booking }}</td>
                                  <td class="align-middle">
                                    <a href=""  class="badge {{($d->flag->status== 'Menunggu' ? 'badge-info' : ($d->flag->status== 'Diterima' || $d->flag->status== 'Selesai' ? 'badge-success': ($d->flag->status== 'Pengerjaan' ? 'badge-warning' : ($d->flag->status== 'Dibatalkan'? 'badge-danger': ''))))}} text-white" data-toggle="tooltip" data-placement="top" title="{{$d->flag->keterangan}}">{{$d->flag->status}}</a>                              
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-toolbar justify-content-center" role="group">
                                      @if ($d->flag_id == 1)
                                      <form action="{{route('admin.booking.approve', ['booking_id' => $d->booking_id])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="approve btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Approve"><i class="fas fa-check"></i></button>
                                      </form>
                                      @endif
                                      @if ($d->flag_id == 3)
                                      <form action="{{ route('admin.booking.kerjakan', ['booking_id' => $d->booking_id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="pengerjaan btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Kerjakan"><i class="fas fa-wrench"></i></button>
                                      </form>
                                      @endif
                                      @if ($d->flag_id == 4)
                                      <a href="" class="checkout btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" data-url="{{route('admin.booking.checkout', ['booking_id' => $d->booking_id])}}" data-original-title="Selesaikan"><i class="fas fa-check"></i></a>
                                      @endif
                                      @if ($d->flag_id != 5 && $d->flag_id != 2 && $d->flag_id && 6)
                                      <form action="{{route('admin.booking.batal', ['booking_id' => $d->booking_id])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="tolak btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Tolak/Batalkan"><i class="fas fa-times"></i></button>
                                      </form>
                                      @endif
                                      <a href="" class="view btn btn-icon btn-primary-shop" data-id="{{ $d->booking_id }}"  data-toggle="tooltip" data-placement="top" data-url="{{route('admin.booking.detail', ['booking_id' => $d->booking_id])}}" data-original-title="Detail"><i class="fas fa-eye"></i></a>
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

<div class="modal fade" id="printModal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Print Laporan Booking Bengkel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.booking.print') }}" method="GET" target="_blank"> 
        <div class="row">
            <div class="col-9">
              <select class="form-control select2 " name="tanggal" data-placeholder="Pilih Bulan" data-hide-search="true">
                <option></option>
                @foreach($tanggal as $t)
                <option value="{{$t['year'].'-'.$t['month']}}">{{\Carbon\Carbon::parse($t['month'])->translatedFormat('F').' - '.$t['year']}}</option>
                @endforeach
              </select>              
            </div>
            <div class="col-3">
              <button type="submit" class="btn btn-success">Print</button>
            </div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>

        
    </div>
  </div>
</div>

<div class="modal fade" id="viewBooking" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
<script src="{{ asset('assets/js/views/booking.js') }}"></script>

@endsection

@section('scriptline')

@endsection