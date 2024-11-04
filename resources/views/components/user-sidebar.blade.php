       
        <div class="col-lg-3 col-md-4">          

            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">My Account</span></h5>
            
            <div class="media mb-3">
                <figure class="avatar mr-3 avatar-lg" data-initial="{{Auth::user()->initials()}}"></figure>
                <div class="media-body">
                  <div class="mt-0 mb-1 font-weight-bold">{{Auth::user()->name}}</div>
                  <a href="{{ route('user.settings') }}"> Change Profile</a>
                </div>
            </div>

            <div class="bg-light mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center container mb-3">
                        <i class="fas fa-money-bill-wave-alt fa-lg p-1 mr-2"></i>
                        <div>
                            <h6 class="font-weight-bold mb-1">Dana Tersimpan</h6>
                            <p class="text-muted m-0">Rp. {{number_format(Auth::user()->deposit)}}</p>
                        </div>
                    </div>
                    <div class="list-group mb-2">
                        <a href="{{ route('user.order.history') }}" class="list-group-item list-group-item-action">Order</a>
                        <a href="{{ route('user.booking.history') }}" class="list-group-item list-group-item-action">Booking</a>
                    </div>
                    <div class="list-group">
                        <a href="{{ route('fav') }}" class="list-group-item list-group-item-action d-flex justify-content-between">My Favourite <span class="badge badge-primary badge-pill">{{count(Auth::user()->favorite)}}</span></a>
                        <a href="{{ route('cart') }}" class="list-group-item list-group-item-action d-flex justify-content-between">My Cart <span class="badge badge-primary badge-pill">{{count(Auth::user()->cart)}}</span></a>
                        <a href="{{ route('user.review') }}" class="list-group-item list-group-item-action">My Review</a>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <a href="" class="btn btn-danger px-3"  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                </form>
            </div>

        </div>