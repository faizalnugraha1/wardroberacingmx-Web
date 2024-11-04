
<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Detail Booking</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- Judul -->
    <div class="modal-body">
      <div class="row">
        <div class="col-12 col-lg-6 mb-sm-3">

          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Booking ID : </label>
            <div class="col-8">
              <p>{{$data->booking_id}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Atas Nama : </label>
            <div class="col-8">
              <p class="mb-0">{{$data->user->name}}</p>
              <p class="mb-0">{{$data->user->no_hp}}</p>
              <p>{{$data->user->email}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Status : </label>
            <div class="col-8">
              <p class="mb-0">{{$data->flag->status}}</p>
              <p>{{$data->flag->keterangan}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Model Motor : </label>
            <div class="col-8">
              <p>{{$data->model_motor}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Tanggal : </label>
            <div class="col-8">
              <p>{{ \Carbon\Carbon::parse($data->tanggal_booking)->translatedFormat('d F Y') }}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Jam : </label>
            <div class="col-8">
              <p>{{$data->jam_booking}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Kebutuhan : </label>
            <div class="col-8">
              <p>{{ucfirst($data->kebutuhan)}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Ketarangan : </label>
            <div class="col-8">
              <p>{{$data->keterangan}}</p>
            </div>
          </div>
          <div class="form-group row mb-1">
            <label class="col-form-label text-md-right col-4">Dibuat pada : </label>
            <div class="col-8">
              <p>{{$data->created_at}}</p>
            </div>
          </div>
        
        </div>
        <div class="col-12 col-lg-6">
          <div id="accordion">
            <div class="accordion">
              <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
                <h4>Log Status</h4>
              </div>
              <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                      <div class="activities">
                        @foreach ($data->booking_log as $bl)
                        <div class="activity">
                          <div class="activity-icon bg-primary text-white shadow-primary">
                            {!!$bl->flag->icon()!!}
                          </div>
                          <div class="activity-detail">
                            <div class="mb-2">
                              <span class="text-muted">{{$bl->created_at}}</span>
                              <span class="bullet"></span>
                              {{-- <a class="text-job" href="#">View</a>                                       --}}
                            </div>
                            <p><span class="badge {{$bl->flag->badge()}} badge-pill mb-1">{{$bl->flag->status}}</span></p>
                            <p>{{$bl->flag->keterangan}}</p>
                            @if ($bl->keterangan)
                            <hr class="my-1">
                            <p>{{$bl->keterangan}}</p>                                        
                            @endif
                          </div>
                        </div>                                    
                        @endforeach
                      </div>
                    </div>
                  </div>
              </div>
            </div>

            @if ($invoice)
            <div class="accordion">
              <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-2">
                <h4>Detail Invoice</h4>
              </div>
              <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                @foreach ($invoice->detail as $b)
                @if ($b->barang_id)
                
                <div class="media">
                  <img class="mr-3" src="{{ asset('images/'. $b->barang->thumbnail) }}" width="50px">
                  <div class="media-body">
                    <a class="h6 text-decoration-none" href="{{ route('product_detail', [$b->barang->slug]) }}">{{$b->nama}}</a>
                    <div class="text-right">                              
                      <small>{{$b->qty}} x {{number_format($b->harga)}}</small>
                      <p class="mb-0">Rp. {{number_format($b->qty * $b->harga)}}</p>
                    </div>
                  </div>
                </div>
                <hr class="my-1"> 
                
                @else
                <div class="text-right">
                  <small>{{$b->nama}}</small>
                  <p class="mb-0">Rp. {{number_format($b->qty * $b->harga)}}</p>
                  <hr class="my-1">
                </div>
                
                @endif
                @endforeach
                <div class="text-right">
                  <h4>Total</h4>
                  <h5 class="mb-0">Rp. {{number_format($invoice->jumlah)}}</h5>
                </div>
              </div>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>