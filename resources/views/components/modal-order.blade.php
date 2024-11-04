<div id="detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollableModalTitle">Detail Order</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12 col-lg-6 mb-sm-3">
                  <div class="row">
                    <div class="col-6">
                      <h6>Kode Invoice</h6>
                      <h5 class="mb-1">{{$data->kode_invoice}}</h5>
                    </div>
                    <div class="col-6">
                      <h6>Status</h6>
                      <h5 class="mb-1"><span class="badge {{$data->flag->badge()}} badge-pill">{{$data->flag->status}}</span></h5>
                    </div>
                  </div>
                  <hr class="my-1">

                  @role('user')
                  @if ($result && $result['status'] == 'pending')
                  <h6 class="mt-3">VA Number ({{strtoupper($result['bank'])}})</h6>
                  <h6 class="mb-1">{{$result['va']}}</h6>
                  <hr class="my-1">                    
                  @endif
                  @endrole
                  
                  @role('admin')
                  <h6 class="mt-3">{{$data->user->name}}</h6>
                  <h6 class="mb-1">{{$data->user->no_hp}}</h6>
                  <h6 class="mb-1">{{$data->user->email}}</h6>
                  <hr class="my-1">

                  <h6 class="mt-3">Status Pembayaran</h6>
                  <h5 class="mb-1">{{$data->midtrans_status}}</h5>
                  <hr class="my-1">
                  @endrole

                  <h6 class="mt-3">Tanggal Order</h6>
                  <h5 class="mb-1">{{$data->created_at}}</h5>
                  <hr class="my-1">
                  
                  <h6 class="mt-3">Total</h6>
                  <h5 class="mb-1">Rp. {{number_format($data->jumlah)}}</h5>
                  <hr class="my-1">

                  <h6 class="mt-3">Kurir</h6>
                  <h5 class="mb-1">{{$data->kurir}} ({{$data->kurir_service}})</h5>
                  <hr class="my-1">

                  @if ($data->resi)
                  <h6 class="mt-3">Resi</h6>
                  <h5 class="mb-1">{{$data->resi}}</h5>
                  @endif
                  
                  <h6 class="mt-3">Alamat</h6>
                  <h6 class="mb-1">{{$data->alamat}}</h6>
 
                </div>
                <div class="col-12 col-lg-6">
                  <div id="accordion">
                    <div class="accordion">
                      <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
                        <h4>Detail</h4>
                      </div>
                      <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                        @foreach ($data->detail as $b)
                        @if ($b->barang_id)
                        
                        <div class="media">
                          <img class="mr-3" src="{{ asset('images/'. $b->barang->thumbnail) }}" width="50px">
                          <div class="media-body">
                            <a class="h6 text-decoration-none" href="{{ route('product_detail', [$b->barang->slug]) }}">{{$b->nama}}</a>
                            {{-- <h6 class="mt-0"><a href="{{ route('product_detail', [$b->barang->slug]) }}">{{$b->nama}}</a></h6> --}}
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
                      </div>
                    </div>

                    <div class="accordion">
                      <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-2">
                        <h4>Log Status</h4>
                      </div>
                      <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                        <div class="row">
                            <div class="col-12">
                              <div class="activities">
                                @foreach ($data->order_log as $bl)
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