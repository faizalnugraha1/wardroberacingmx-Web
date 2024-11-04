<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="utf-8">
    <title>@isset (session('meta')['title']) {{session('meta')['title']}} | @endisset{{'Wardrobe Racing MX'}}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="copyright" href="{{env('APP_URL')}}">

    @isset (session('meta')['keywords']) <meta name="keywords" content="{{session('meta')['keywords']}}">@endisset    
    @isset (session('meta')['url']) <meta property="og:url" content="{{session('meta')['url']}}">@endisset   
    @isset (session('meta')['title']) <meta property="og:title" content="{{session('meta')['title']}}">@endisset       
    @isset (session('meta')['image']) <meta property="og:image" content="{{session('meta')['image']}}">@endisset           
    {{-- <meta property="og:image:width" content="480">
    <meta property="og:image:height" content="360"> --}}
    @isset (session('meta')['description']) <meta property="og:description" content="{{session('meta')['description']}}">@endisset               
    
    @isset (session('meta')['twitter_card']) <meta content='{{session('meta')['twitter_card']}}' name='twitter:card'/>@endisset
    @isset (session('meta')['title']) <meta name='twitter:title' content="{{session('meta')['title']}}"/>@endisset           
    @isset (session('meta')['image']) <meta name='twitter:image' content="{{session('meta')['image']}}"/>@endisset           
    

    <!-- Favicon -->
    <link href="{{ asset('logo_hd.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome6/css/all.min.css') }}">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('shop/lib/animate/animate.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('shop/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/sweetalert2/dist/sweetalert2.css') }}">
    <link href="{{ asset('assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css') }}" rel="stylesheet">
    @yield('csslib')

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('shop/css/style.css') }}" rel="stylesheet">
    @yield('csscustom')
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-30">
        <div class="d-flex w-25">
            {{-- <a class="navbar-brand mr-0" href="#">Bootstrap 4</a> --}}
            <a class="navbar-brand text-decoration-none text-white my-auto" href="{{ route('home') }}">
                <img src="{{ asset('logo_hd.png') }}" height="50" class="d-inline-block align-top" alt="">
                
            </a>
            <a class="navbar-brand text-decoration-none text-white my-auto logo-text" href="{{ route('home') }}">
                Wardrobe Racing MX
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse justify-content-center" id="collapsingNavbar">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('booking') }}">Booking</a>
                </li>
            </ul>
            <form class="ml-3 my-auto d-inline w-100" action="{{ route('shop_query') }}">
                <div class="input-group">                
                  <input type="text" name="q" class="form-control border-right-0" placeholder="@if(Request::has('q')){{request()->get('q')}}@else Cari Barang @endif"> 
                  <div class="input-group-append">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary border-left-0" type="button"><i class="fa fa-search"></i></button>
                      </div>
                  </div>
                </div>
            </form>
            {{-- <span class="navbar-text small text-truncate mt-1 w-25 text-right">always show</span> --}}
            <ul class="navbar-nav w-50 kanan">
                @auth
                @role('user')
                @if (!request()->routeIs('user.*')) 
                <li class="nav-item ml-auto">
                    <a href="{{ route('fav') }}" class="btn px-0 nav-link">
                        <i class="fas fa-heart text-primary"></i>
                        <span id="fav_count" class="badge text-secondary border border-secondary rounded-circle" >{{count(Auth::user()->favorite)}}</span>
                    </a>
                </li>
                <li class="nav-item ml-3">
                    <a href="{{ route('cart') }}" class="btn px-0 nav-link">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <span id="cart_count" class="badge text-secondary border border-secondary rounded-circle">{{count(Auth::user()->cart)}}</span>
                    </a>
                </li>
                @endif
                <li class="nav-item @if (request()->routeIs('user.*')) ml-auto @else ml-3 @endif">
                    <a href="{{ route('user.settings') }}" class="btn px-0 nav-link">
                        <i class="fas fa-user text-primary"></i>
                    </a>
                </li>
                @endrole
                @role('admin')
                <li class="nav-item ml-auto">
                    <a href="{{ route('admin.dashboard')}}" class="btn px-0 nav-link">
                        <i class="fas fa-user text-primary"></i>
                    </a>
                </li>
                @endrole
                @else
                <li class="nav-item ml-auto">
                    <a href="{{ route('login') }}" class="btn px-0 nav-link text-primary"> LOGIN </a>
                </li>
                @endauth
            </ul>
        </div>
    </nav>

    @yield('content')


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-3 col-md-12 mb-5 pr-1 flex flex-column justify-content-center">
                <h5 class="text-secondary text-uppercase mb-2">Alamat</h5>
                <p class="mb-4"><i class="fas fa-map-marker-alt text-primary mr-3"></i> <a href="https://goo.gl/maps/WPdh1dGpfoQaz6Tx6" target="_blank" class="text-white"> Jl AH. Nasution No 162 (Depan SD Sindanglaya) </a></p>
                <h5 class="text-secondary text-uppercase mb-2">Kontak</h5>
                <p class="mb-2"><i class="fab fa-whatsapp text-primary mr-3"></i> <a href="https://wa.me/628112478055" class="text-white"> 08112478055 </a></p>
                <p class="mb-4"><i class="fab fa-whatsapp text-primary mr-3"></i> <a href="https://wa.me/6281214232086" class="text-white"> 081214232086 </a></p>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="row">
                    <div class="col-md-8 mb-5">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d404.4837643573266!2d107.68004943315131!3d-6.905247299070902!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68dd0ca89331b9%3A0x234d69df9cc3618f!2sWardrobe%20Mx%20%26%20Wardrobe%20Racing%20Concept!5e0!3m2!1sen!2sid!4v1656674327317!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="{{ route('home') }}"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Kategori</a>
                            <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Brand</a>
                        </div>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="https://www.instagram.com/wardroberacing.id"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-primary btn-square" href="https://www.instagram.com/wardroberacing.catalog"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    @yield('modal')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('shop/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('shop/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/modules/sweetalert2/dist/sweetalert2.js') }}"></script>
    @yield('scriptlib')
    <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
    @if(Session::has('toast_success'))
    <script>
        iziToast.success({
            title: 'Success!',
            message: '{{ Session('toast_success') }}' ,
            position: 'bottomRight'
        });
    </script>        
    @endif
    @if(Session::has('swal'))
    <script>
        Swal.fire({
            icon: '{{session('swal')['icon']}}',
            title: '{{session('swal')['title']}}',
            text: '{{session('swal')['text']}}',
            showConfirmButton: false,
            timer: 1500,
        });
    </script>        
    @endif
    <script src="{{ asset('shop/js/main.js') }}"></script>

    @yield('script_line')
</body>

</html>