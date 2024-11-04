<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <title>@isset($title) {{ $title }} | @endisset()Admin Dashboard | {{str_replace('_',' ',env('APP_NAME'))}}</title>    

    <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome6/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/css/iziToast.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/sweetalert2/dist/sweetalert2.css') }}">
  @yield('csslib')
    
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
          <div class="navbar-bg"></div>
    
          <!-- Top Navigation Bar -->
          <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
              <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
              </ul>
            
            </form>
            <!-- Profile Nav Bar Kanan -->
            <ul class="navbar-nav navbar-right">          
              <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
                <div class="dropdown-menu dropdown-menu-right">
                  {{-- <a href="" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                  </a>
                  <div class="dropdown-divider"></div> --}}
                  <a href="" class="dropdown-item has-icon text-danger"  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    </form>
                </div>
              </li>
            </ul>
          </nav>
    
          <!-- Side Navigation Bar -->
          <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
              <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}">Wardrobe Racing MX</a>
              </div>
              <div class="sidebar-brand sidebar-brand-sm">
                <a href="{{ route('admin.dashboard') }}">WR</a>
              </div>
              <ul class="sidebar-menu">
                {{-- <li class="menu-header">Dashboard</li> --}}
                <li @if (request()->routeIs('admin.dashboard')) class="active" @endif ><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li @if (request()->routeIs('admin.order.index')) class="active" @endif ><a class="nav-link" href="{{ route('admin.order.index') }}"><i class="fas fa-shopping-basket"></i> <span>Order</span></a></li>
                <li @if (request()->routeIs('admin.booking.index')) class="active" @endif ><a class="nav-link" href="{{ route('admin.booking.index') }}"><i class="fas fa-wrench"></i> <span>Booking Bengkel</span></a></li>
                <li class="menu-header">Barang</li>
                {{-- <li @if (request()->routeIs('admin.dashboard')) class="active" @endif ><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li> --}}
                <li class="dropdown @if (request()->routeIs('admin.barang.*')) active @endif">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cubes"></i> <span>Barang</span></a>
                  <ul class="dropdown-menu">
                    <li @if (request()->routeIs('admin.barang.index')) class="active" @endif><a class="nav-link" href="{{ route('admin.barang.index') }}">Stok</a></li>
                    <li @if (request()->routeIs('admin.barang.kategori.index')) class="active" @endif><a class="nav-link" href="{{ route('admin.barang.kategori.index') }}">Kategori</a></li>
                    <li @if (request()->routeIs('admin.barang.brand.index')) class="active" @endif><a class="nav-link" href="{{ route('admin.barang.brand.index') }}">Brand/Merek</a></li>
                  </ul>
                </li>
                {{-- <li class="menu-header">Starter</li>
                <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Layout</span></a>
                  <ul class="dropdown-menu">
                    <li><a class="nav-link" href="layout-default.html">Default Layout</a></li>
                    <li><a class="nav-link" href="layout-transparent.html">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="layout-top-navigation.html">Top Navigation</a></li>
                  </ul>
                </li>
                <li><a class="nav-link" href="blank.html"><i class="far fa-square"></i> <span>Blank Page</span></a></li> --}}
              </ul>     
            </aside>
          </div>
                    
          @yield('maincontent')
                    
          <!-- Footer -->
          <footer class="main-footer">
            <div class="footer-left">
              Copyright &copy; 2022
            </div>
            <div class="footer-right">
              1.0.0
            </div>
          </footer>
        </div>
      </div>
      
  @yield('modalcontent')

   <!-- General JS Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  {{-- <script src="{{ asset('assets/modules/popper.js') }}"></script> --}}
  <script src="{{ asset('assets/modules/popper.min.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/modules/sweetalert2/dist/sweetalert2.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script> 

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
  @yield('scriptlib')

  <script src="{{ asset('assets/js/scripts.js') }}"></script>

  @yield('scriptpage')
  
  @yield('scriptline')
</body>
</html>
