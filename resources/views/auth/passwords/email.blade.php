<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Request Forgot Password | {{str_replace('_',' ',env('APP_NAME'))}}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
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
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ asset('logo_hd_invert.png') }}" alt="Wardrobe Racing MX" width="200" class="shadow-light">
            </div>

            @if(Session::has('email_sent'))
            <div class="alert alert-success alert-has-icon">
              <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
              <div class="alert-body">
                <div class="alert-title">{{Session('email_sent')}}</div>
                Cek email anda ({{Session('email')}}) untuk reset Password. <a href="" onclick="event.preventDefault();
                document.getElementById('resend_reqpass').submit();" class="text-dark"> Klik disini</a> jika belum menerima email.
              </div>
              <form id="resend_reqpass" action="{{ route('request_reset') }}" method="POST" class="d-none">
                @csrf
                @method('POST')
                <input type="hidden" name="email" value="{{Session('email')}}">
              </form>
            </div>       
            @endif

            @if($errors->any())
            @foreach($errors->getMessages() as $this_error)
            <div class="alert alert-danger" role="alert">
              <i class="fas fa-exclamation-triangle  mr-3"></i> {{$this_error[0]}}
            </div> 
            @endforeach
            @endif 

            <div class="card card-primary">
              <div class="card-header"><h4>Lupa Password</h4></div>

              <div class="card-body">
                <form method="POST" action="{{ route('request_reset') }}">
                  @csrf
                  @method('POST')
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Submit
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
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/popper.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>