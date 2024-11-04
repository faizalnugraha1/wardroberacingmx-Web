@extends('admin.appadmin')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('maincontent')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
        <h1>Chekout</h1>
    </div>
    <div class="section-body">
      <form action="{{ route('admin.booking.store', ['booking_id'=>$data->booking_id]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="" >
        @csrf
        @method('POST')
        <input type="hidden" name="kode_invoice" value="{{strtoupper($invoice)}}">
      <div class="invoice">
        <div class="invoice-print">
          <div class="row">
            <div class="col-lg-12">
              <div class="invoice-title">
                <h2>Kode Invoice</h2>
                <div class="invoice-number">{{strtoupper($invoice)}}</div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <strong>Nama :</strong><br>
                      {{$data->user->name}}<br>
                  </address>
                  <strong>Kode Booking :</strong><br>
                    {{strtoupper($data->booking_id)}}<br>

                  <strong>Model Motor :</strong><br>
                    {{$data->model_motor}}<br>
   
                </div>
                <div class="col-md-6 text-md-right">
                  <address>
                    <strong>Tanggal Booking :</strong><br>
                    {{$data->tanggal_booking}} {{$data->jam_booking}}<br>
                    <strong>Pengerjaan :</strong><br>
                    {{$data->pengerjaan[0]->created_at}}<br>
                    <strong>Selesai :</strong><br>
                    {{now()}}<br>
                  </address>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="section-title">Jasa</div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-md">
                  <thead>
                    <tr>
                      <th data-width="40">#</th>
                      <th>Jasa</th>
                      <th class="text-center">Harga</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-right">Total</th>
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tbody id="tjasa" data-row1="{{count(explode(',',$data->kebutuhan))}}">
                    @foreach(explode(',',$data->kebutuhan) as $key => $jasa)
                    <tr>
                      <td class="row_number">{{++$key}}</td>
                      <td><input type="text" name="nama_jasa[]" class="form-control form-control-sm" value="{{$jasa}}" required></td>
                      <td class="text-center"><input type="text" name="harga_jasa[]" class="input-harga form-control form-control-sm" required></td>
                      <td class="text-center"><input type="text" name="qty_jasa[]" class="input-jumlah form-control form-control-sm" value="1" required></td>
                      <td class="text-right"><input type="text" name="total_jasa[]" class="input-total form-control form-control-sm" readonly></td>
                      <td class="text-center">
                        <button class="btn btn-danger remove"><i class="fas fa-trash-alt"></i></button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td><input id="input-j" type="text" class="form-control form-control-sm"></td>
                      <td class="text-center"><input id="input-p" type="text" class="form-control form-control-sm"></td>
                      <td class="text-center"><input id="input-q" type="text" class="form-control form-control-sm"></td>
                      <td class="text-right"></td>
                      <td class="text-center">
                        <button id="addBtn" class="btn btn-success"><i class="fas fa-plus"></i></button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="section-title">Tambahan Barang</div>
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-md">
                  <thead>
                    <tr>
                      <th data-width="40">#</th>
                      <th>Nama barang</th>
                      <th class="text-center">Harga</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-right">Total</th>
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tbody id="tbarang">
                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-center">
                        <button id="addBtn2" class="btn btn-success" data-url="{{ route('admin.booking.addbarang') }}"><i class="fas fa-plus"></i></button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="row mt-4">
                <div class="col-lg-12 text-right">
                  <div class="invoice-detail-item">
                    <div class="invoice-detail-name">Total</div>
                    <div class="invoice-detail-value invoice-detail-value-lg">Rp. <span id="total-inv" >0</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="text-right">
          <button type="submit" class="btn btn-warning btn-icon icon-left"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </div>
    </form>
    </div>       
    </div>
  </section>
</div>
@endsection

@section('modalcontent')

<div class="modal fade" id="addBarang" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
</div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap-input-spinner/src/bootstrap-input-spinner.js') }}"></script>
@endsection

@section('scriptpage')
<script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
<script src="{{ asset('assets/js/views/booking-checkout.js') }}"></script>
@endsection