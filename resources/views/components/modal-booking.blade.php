<div id="detail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollableModalTitle">Booking Detail</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <h6>Kode Booking</h6>
                        <h4 class="mb-1">{{$data->booking_id}}</h4>
                    </div>
                    <div class="col-6">
                        <h6>Status</h6>
                        <h5 class="mb-1"><span class="badge {{$data->flag->badge()}} badge-pill">{{$data->flag->status}}</span></h5>
                    </div>
                </div>
                <hr class="my-1">
                <h6>Motor</h6>
                <h4 class="mb-1">{{$data->model_motor}}</h4>
                <hr class="my-1">
                <h6>Dibuat Tanggal</h6>
                <h4 class="mb-1">{{$data->created_at}}</h4>
                <hr class="my-1">
                <h6>Jadwal Booking</h6>
                <h4>Tanggal: {{$data->tanggal_booking}}</h4>
                <h4 class="mb-1">Jam: {{$data->jam_booking}}</h4>
                <hr class="my-1">
                <h6>Keterangan</h6>
                <p class="mb-0"><strong>{{$data->kebutuhan}}</strong></p>
                <p class="mb-3">{{$data->keterangan}}</p>
                <hr class="my-1">
                <div id="accordion">
                    <div class="accordion">
                      <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1">
                        <h4>Log Status</h4>
                      </div>
                      <div class="accordion-body collapse" id="panel-body-1" data-parent="#accordion">
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
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </div>