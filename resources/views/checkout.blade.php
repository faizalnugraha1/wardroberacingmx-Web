@extends('layouts.app')


@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')

<!-- Checkout Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
      
      <div class="col-lg-8">
        <form id="checkout_form" action="{{ route('pay') }}" enctype="multipart/form-data" method="POST">
          @csrf
          @method('POST')
        <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Invoice</span></h5>
            <div class="bg-light p-30 mb-5"><div class="section-body">
                <div class="invoice">
                  <div class="invoice-print">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="invoice-title">
                            <h3>#{{$invoice}}</h3>
                            <input type="hidden" name="invoice" value="{{$invoice}}">
                          {{-- <div class="invoice-number">Order #12345</div> --}}
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                              <address>
                                <strong>Nama :</strong><br>
                                  {{Auth::user()->name}}<br>
                              </address>
                              <strong>Alamat :</strong><br>
                              <span id="alamat_invoice">                                
                                {{$alamat[0]->detail}}<br>
                                Kec. {{$alamat[0]->kecamatan->nama}}<br>
                                {{$alamat[0]->kota->nama}}<br>
                                {{$alamat[0]->provinsi->nama}}<br>
                                {{$alamat[0]->kode_pos}}<br>
                              </span>
                              <input type="hidden" name="alamat_id" value="{{$alamat[0]->enc_id()}}">
                                
                              {{-- <strong>Model Motor :</strong><br>
                                {{$data->model_motor}}<br> --}}
               
                            </div>
                            <div class="col-md-6 text-md-right">
                              <address>
                                <strong>Tanggal Pemesanan :</strong><br>
                                {{now()}}<br>
                              </address>
                            </div>
                          </div>                       
                      </div>
                    </div>
                    
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table table-striped table-hover table-md">
                            <tr>
                              <th data-width="40">#</th>
                              <th>Item</th>
                              <th class="text-center">Harga</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-right">Totals</th>
                            </tr>

                            @foreach ($data as $key=> $d)
                            <tr>
                              <td>{{++$key}}</td>
                              <td>{{$d->barang->nama}}</td>
                              <td class="text-center">{{number_format($d->barang->harga_jual)}}</td>
                              <td class="text-center">{{$d->qty}}</td>
                              <td class="text-right">{{number_format($d->qty * $d->barang->harga_jual)}}</td>
                            </tr>                              
                            @endforeach                           
                          </table>
                        </div>
                        <div class="row mt-4">
                          <div class="col-lg-8">
                           <input type="hidden" id="berat" value="{{$berat}}">
                          </div>
                          <div class="col-lg-4 text-right">
                            <div class="invoice-detail-item mb-2">
                              <div class="invoice-detail-name">Subtotal</div>
                              <div class="invoice-detail-value">Rp. {{number_format($subtotalbe)}}</div>
                              <input type="hidden" name="subtotal" value="{{$subtotalbe}}">
                            </div>
                            <div class="invoice-detail-item mb-2">
                              <div class="invoice-detail-name">Biaya Admin</div>
                              <div class="invoice-detail-value">Rp. {{number_format(env('MIDTRANS_ADMIN'))}}</div>
                              <input type="hidden" name="biaya_admin" value="{{env('MIDTRANS_ADMIN')}}">
                            </div>
                            <div class="invoice-detail-item mb-2">
                              <div class="invoice-detail-name">Ongkos Kirim</div>
                              <div class="invoice-detail-value">Rp. <span id="ongkir_txt">-</span></div>
                              <input type="hidden" name="ongkir" value="">
                              <input type="hidden" name="kurir_code">
                              <input type="hidden" name="kurir_service">
                              <input type="hidden" name="kurir_estimasi">
                            </div>
                            <div id="dana_container">
                              
                            </div>
                            <hr class="mt-2 mb-2">
                            <div class="invoice-detail-item">
                              <div class="invoice-detail-name">Total</div>
                              <div class="invoice-detail-value invoice-detail-value-lg">Rp. <span id="total_txt">-</span></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>
              </div></div>    
        </form>       
        </div>
        <div class="col-lg-4">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Alamat</span></h5>
            <div class="bg-light p-30 mb-5">
              <div class="form-group">
                {{-- <label>Keperluan</label> --}}
              <select id="select_alamat" name="alamat" class="form-control select2" required>
                @foreach ($alamat as $a)
                  <option value="{{$a->enc_id()}}">{{$a->full()}}</option>
                @endforeach
              </select>
              <div class="invalid-feedback">
                Alamat harus dipilih
              </div>
              </div>
            </div>
            <div class="mb-5">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Kurir</span></h5>
                <div class="bg-light p-30">                    
                    <div id="accordion">

                      @include('components.kurir')

                    </div>
                </div>
            </div>

            @if (Auth::user()->deposit > 0)
            <div class="mb-5">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Dana Tersimpan</span></h5>
                <div class="bg-light p-30">
                    <small>Gunakan dana tersimpan</small>
                    <div class="form-group mb-0 ml-1">
                      <div class="form-check">
                        <input type="checkbox" class="custom-control-input" id="user_dana" name="size" value="{{Auth::user()->deposit}}">
                        <label class="custom-control-label" for="user_dana">Rp. {{number_format(Auth::user()->deposit)}}</label>
                      </div>
                    </div>
                </div>
            </div>              
            @endif

            <button id="btn-submit" type="submit" class="submit_btn btn btn-block btn-primary font-weight-bold py-3" disabled>Bayar</button>
        </div>
    </div>
</div>
<!-- Checkout End -->

@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script type='text/javascript' src='{{ asset('assets/modules/currencyjs/currencyFormatter.js') }}'></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_CLIENT_KEY')}}"></script>
  <script>    
    $('.select2').select2({
        width: '100%',
        minimumResultsForSearch: -1,
    });

    $('#select_alamat').on("select2:select", function (e) { 
      var id = e.params.data.id;
      $('input[name=alamat_id]').val(id);
      // alert(id);
      $.ajax({
        type: "GET",
        url: "{{ route('update_ongkir') }}",
        data: {
          id: id,
          berat: $('#berat').val(),        
        },
        dataType: "json",
        beforeSend: function() {
          $('#accordion').html("<div class='flex h-full text-center'><i class='fas fa-circle-notch fa-2xl fa-spin'></i></div>");
          $('.submit_btn').attr('disabled', true);
          $('#ongkir_txt').html('-');
        },
        success: function (response) {
          $('#accordion').html(response.view);
          $('#alamat_invoice').html(response.alamat);
          init_ongkir();
        }
      });
    });

    function init_ongkir(){
      $("input[name=ongkir_select]").change(function (e) { 
        e.preventDefault();
        $('#ongkir_txt').html($(this).val());
        $('.submit_btn').attr('disabled', false);
        $('input[name=ongkir]').val($(this).val());
        $('input[name=kurir_code]').val($(this).data('code'));
        $('input[name=kurir_service]').val($(this).data('service'));
        $('input[name=kurir_estimasi]').val($(this).data('estimasi'));
        var subtot = parseInt($('input[name=subtotal]').val());
        var admin = parseInt($('input[name=biaya_admin]').val());
        if ($('input[name=dana_tersimpan]').val()){
          var dana = parseInt($('input[name=dana_tersimpan]').val());
        } else {
          var dana = 0;
        }
        var total  = subtot + admin + parseInt($(this).val()) - dana;
        $('#total_txt').html(total);

        if (total <= 0){
          $('#btn-submit').html('Pesan Sekarang');
        } else {
          $('#btn-submit').html('Bayar');
        }
      });
    }
    init_ongkir();

  $('#user_dana').change(function (e) { 
    // e.preventDefault();
    var dana = parseInt($(this).val());
    if($(this).is(':checked')){
      $('#dana_container').html(`
        <hr class="mt-2 mb-2">
        <div class="invoice-detail-item">
          <div class="invoice-detail-name">Dana Tersimpan</div>
          <div class="invoice-detail-value invoice-detail-value-lg">-Rp. ${dana}</div>
          <input type="hidden" name="dana_tersimpan" value="${dana}">
        </div>`);
    }else{
      $('#dana_container').html('');
    }

    if($('input[name=ongkir]').val()){
        var ongkir = parseInt($('input[name=ongkir]').val());
        var subtot = parseInt($('input[name=subtotal]').val());
        var admin = parseInt($('input[name=biaya_admin]').val());
        if ($('input[name=dana_tersimpan]').val()){
          var dana = parseInt($('input[name=dana_tersimpan]').val());
        } else {
          var dana = 0;
        }
        var total  = subtot + admin + ongkir - dana;
        $('#total_txt').html(total);

        if (total <= 0){
          $('#btn-submit').html('Pesan Sekarang');
        } else {
          $('#btn-submit').html('Bayar');
        }
    }
  });    

  $('.submit_btn').click(function (e) { 
    e.preventDefault();
    var form = $('#checkout_form');
    var total = parseInt($('#total_txt').html());
    if (total <= 0){
      Swal.fire({
        title: `Lanjutkan pesanan?`,
        icon: 'question',
        buttons: true,
        showCancelButton: true,
      }).then((result) => {
          if (result.isConfirmed) {
              form.submit();
          }
      });
    } else {
      $.ajax({
      type: "POST",
      url: form.attr('action'),
      data: form.serialize(),
      dataType: "json",
      success: function (response) {
        snap.pay(response.token,{
          onClose: function(result){
            $.ajax({
              type: "GET",
              url: response.cancel,
              dataType: "json",
              success: function (response) {
                Swal.fire({
                  title: response.title,
                  text: response.message,
                  icon: response.status,
                  showConfirmButton: false,
                  timer: 2000
                })
              }
            });
          }
        });
      
      }
    });
    }
    
  });

  </script>
@endsection