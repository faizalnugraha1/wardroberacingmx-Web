<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Registrasi | {{str_replace('_',' ',env('APP_NAME'))}}</title>


  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/modules/select2/dist/css/select2.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <img src="{{ asset('logo_hd_invert.png') }}" alt="Wardrobe Racing MX" width="200" class="shadow-light">
            </div>
            @if($errors->any())
            @foreach($errors->getMessages() as $this_error)
            <div class="alert alert-danger" role="alert">
              <i class="fas fa-exclamation-triangle  mr-3"></i> {{$this_error[0]}}
            </div> 
            @endforeach
            @endif 
            <div class="card card-primary mb-5">
              <div class="p-4">
                <h3>Daftar Sekarang</h3>
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
              </div>

              <div class="card-body">
                <form class="needs-validation" method="POST" novalidate="" action="{{ route('register') }}">
                  @csrf
                  @method('POST')
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="name">Nama</label>
                      <input type="text" class="form-control" name="name" autofocus required>
                      <div class="invalid-feedback">
                        Nama harus diisi
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label for="no_hp">No. Handphone</label>
                      <input type="text" class="form-control nomer_hp" name="no_hp" required placeholder="ex: 08123456789">
                      <div class="invalid-feedback">
                        Nomor handphone tidak boleh kosong
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" required>
                    <div class="invalid-feedback">
                      Email harus diisi
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-6">
                      <label for="password" class="d-block">Password</label>
                      <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" required>
                      <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                      </div>
                      <div class="invalid-feedback">
                        Password harus diisi
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">Konfirmasi Password</label>
                      <input id="password2" type="password" class="form-control" name="password_confirmation">
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-6">
                      <label>Provinsi</label>
                      <select id="select_provinsi" class="form-control select2" name="provinsi" data-url="{{ route('prov.list') }}" required>
                        <option></option>
                      </select>
                      <div class="invalid-feedback">
                        Provinsi harus diisi
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label>Kota/Kabupaten</label>
                      <select id="select_kota" class="form-control select2" name="kota" required data-placeholder="Pilih Kota/Kabupaten" data-hide-search="true" data-url="{{ route('kota.list') }}">
                        <option></option>
                      </select>
                      <div class="invalid-feedback">
                        Kota/Kabupaten harus diisi
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-6">
                      <label>Kecamatan</label>
                      <select id="select_kecamatan" class="form-control select2" name="kecamatan" required data-placeholder="Pilih Kecamatan" data-hide-search="true" data-url="{{ route('kecamatan.list') }}">
                        <option></option>
                      </select>
                      <div class="invalid-feedback">
                        Kecamatan harus diisi
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label for="kelurahan">Kelurahan</label>
                      <input type="text" class="form-control" name="kelurahan" placeholder="(Opsional)">
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-6">
                      <label for="kode_pos">Kode Pos</label>
                      <input type="text" class="form-control" name="kode_pos" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" name="alamat" required>
                    <div class="invalid-feedback">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="agree" class="custom-control-input" id="agree" required>
                      <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Register
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
  <script src="assets/modules/select2/dist/js/select2.full.min.js"></script>
  <script src="assets/modules/cleave-js/dist/cleave.min.js"></script>
  <script src="assets/modules/cleave-js/dist/addons/cleave-phone.id.js"></script>

  <!-- Page Specific JS File -->
  <script src="assets/js/page/auth-register.js"></script>
  
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  <script src="{{ asset('assets/js/views/registration.js') }}"></script>
</body>
</html>