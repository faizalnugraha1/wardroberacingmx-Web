@extends('layouts.app')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css') }}">
@endsection

@section('content')

    <!-- Carousel Start -->
    <div class="container-fluid">
        <div class="row px-xl-5 justify-content-center">
            <div class="col-lg-9">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#header-carousel" data-slide-to="1"></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="carousel-item position-relative active" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset('assets/img/IMG_20191111_080914_099.jpg') }}" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Monthly Maintenance</h1>
                                {{-- <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item position-relative" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset('assets/img/IMG_20191111_081823_841.jpg') }}" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Performance Upgrades</h1>
                                {{-- <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a> --}}
                            </div>
                        </div>
                    </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <div class="pt-5 pb-3 container-fluid">
        <div class="row px-xl-5 justify-content-center">
            <div class="col-9">                
                <h2 class="section-title position-relative text-uppercase mb-4"><span class="bg-secondary pr-3">Buat Booking</span></h2>
                <div class="bg-light p-30 mb-5">
                    <form action="{{ route('booking_create') }}" class="needs-validation" method="POST" novalidate="" >
                    @csrf
                    @method('POST')
                      <div class="form-row">
                          <div class="form-group col-md-6">
                            <label>Tanggal</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-calendar"></i>
                                </div>
                              </div>
                              <input type="text" name="tanggal" class="form-control datepicker" required autocomplete="off">
                            </div>
                            <div class="invalid-feedback">
                              Tanggal harus diisi
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Jam</label>
                            <div class="input-group clockpicker" data-align="top" data-autoclose="true">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-clock"></i>
                                </div>
                              </div>
                              <input type="text" name="jam" class="form-control" value="09:00">
                            </div>
                            <div class="invalid-feedback">
                              Jam harus diisi
                            </div>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label>Keperluan</label>
                          <select name="keperluan[]" class="form-control select2" multiple required>
                            <option value="Service">Service</option>
                            <option value="Pasang Part">Pemasangan Part</option>
                            <option value="Cek mesin">Cek Mesin</option>
                            <option value="Tune up">Tune Up</option>
                          </select>
                          <div class="invalid-feedback">
                            Keperluan harus diisi
                          </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Model Motor</label>
                            <input type="text" name="model_motor" class="form-control" placeholder="Masukan Model Motor (ex. KLX 150)" required>
                            <div class="invalid-feedback">
                              Model motor harus diisi
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Keterangan</label>
                          <textarea class="form-control" name="keterangan" rows="4" placeholder="Jika perlu..."></textarea>
                        </div>
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" required>
                            <label class="form-check-label">
                              Saya mengisi data ini dengan benar.
                            </label>
                          </div>
                        </div>
                        <div class="flex justify-content-right text-right">
                          <button type="submit" class="ml-auto btn btn-primary text-right">Booking Sekarang</button>
                        </div>
                      </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
<script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/modules/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
<script>
    $(".needs-validation").submit(function () {
        var form = $(this);
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.addClass("was-validated");
    });

    $('.select2').select2({
        width: '100%',
        minimumResultsForSearch: -1,
        placeholder: ' -Pilih satu atau lebih-',
    });

   $(document).ready(function () {
    $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });
   });
   $('.clockpicker').clockpicker();
</script>
@endsection