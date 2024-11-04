@extends('admin.appadmin')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/fullcalendar/fullcalendar.min.css') }}">
@endsection

@section('maincontent')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    {{-- <div class="section-header">
        <h1>Wardrobe Racing MX</h1>
    </div> --}}
    <div class="section-body">
      {{-- <h2 class="section-title">Dashboard</h2> --}}
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-statistic-2">
            <div class="card-stats">
              <div class="card-stats-title">
                Statistik Bulan Ini 
              </div>
              <div class="card-stats-items">
                <div class="card-stats-item">
                  <div class="card-stats-item-count">{{$diproses}}</div>
                  <div class="card-stats-item-label">Diproses</div>
                </div>
                <div class="card-stats-item">
                  <div class="card-stats-item-count">{{$kurir}}</div>
                  <div class="card-stats-item-label">P. Kurir</div>
                </div>
                <div class="card-stats-item">
                  <div class="card-stats-item-count">{{$selesai}}</div>
                  <div class="card-stats-item-label">Selesai</div>
                </div>
              </div>
            </div>
            <div class="card-icon shadow-primary bg-primary">
              <i class="fas fa-archive"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Total Order</h4>
              </div>
              <div class="card-body">
                {{$total}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-statistic-2">
            <div class="card-chart"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
              <canvas id="balance-chart" height="77" width="327" style="display: block; height: 62px; width: 262px;" class="chartjs-render-monitor"></canvas>
            </div>
            <div class="card-icon shadow-primary bg-primary">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Pemasukan (Barang)</h4>
              </div>
              <div class="card-body">
                Rp. {{number_format($barang->pemasukan)}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-statistic-2">
            <div class="card-chart"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
              <canvas id="sales-chart" height="77" width="327" style="display: block; height: 62px; width: 262px;" class="chartjs-render-monitor"></canvas>
            </div>
            <div class="card-icon shadow-primary bg-primary">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Pemasukan (Jasa)</h4>
              </div>
              <div class="card-body">
                Rp. {{number_format($jasa->pemasukan)}}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8 col-12">
          <div class="card">
            <div class="card-header">
              <h4>Kalender Booking Bengkel</h4>
            </div>
            <div class="card-body">
              <div class="fc-overflow">                            
                <div id="myEvent" data-url="{{ route('admin.booking.list') }}"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card card-hero">
            <div class="card-header">
              <div class="card-icon">
                <i class="fas fa-comments-dollar"></i>
              </div>
              <div class="card-description">Transaksi terbaru</div>
            </div>
            <div class="card-body p-0">
              @if (count($transaksi) <= 0)
                <div class="row">
                  <div class="col-12">
                    <div class="align-center text-center p-2 mb-2">     
                        <h4 class="text-center mb-1">Oooops..</h4>
                        <h6 class="text-center mb-3">Tidak ada transaksi</h6>
                        <i class="fa-solid fa-file-circle-exclamation fa-3x"></i>
                    </div>
                </div>
                </div>
                @else
                <div class="tickets-list">
                  @foreach ($transaksi as $t)                    
                  <a href="{{ route('admin.order.detail', ['invoice_id'=>$t->kode_invoice]) }}" class="ticket-item">
                    <div class="ticket-title">
                      <h4>{{$t->kode_invoice}} &bull; Rp. {{number_format($t->jumlah)}}</h4>
                    </div>
                    <div class="ticket-info">
                      <div>{{$t->user->name}}</div>
                      <div class="bullet"></div>
                      <div class="text-primary">{{\Carbon\Carbon::parse($t->midtrans_date)->diffForHumans();}}</div>
                    </div>
                  </a>
                  @endforeach
                  
                </div>
                @endif
            </div>
          </div>

          <div class="card gradient-bottom">
            <div class="card-header">
              <h4>Produk Terlaris</h4>
            </div>
            <div class="card-body" id="top-5-scroll" tabindex="2" style="height: 400px; overflow: hidden; outline: none;">
              <ul class="list-unstyled list-unstyled-border">
                @if (count($terlaris) <= 0)
                <div class="row">
                  <div class="col-12">
                    <div class="align-center text-center p-2 mb-2">     
                        <h4 class="text-center mb-1">Oooops..</h4>
                        <h6 class="text-center mb-3">Tidak ada data Record</h6>
                        <i class="fas fa-box-open fa-3x"></i>
                    </div>
                  </div>
                </div>         
                @endif
                @foreach ($terlaris as $t)
                @if ($loop->first)
                  @php
                    $top = $t->total_terjual;
                    $val = 100;
                  @endphp
                @endif
                <li class="media">
                  <img class="mr-3 rounded" width="55" src="{{ asset('images/'. $t->barang->thumbnail) }}" alt="product">
                  <div class="media-body">
                    <div class="float-right ml-2"><div class="font-weight-600 text-muted text-small">Rp. {{number_format($t->total_pemasukan)}}</div></div>
                    <div class="media-title">{{$t->barang->nama}}</div>
                    <div class="mt-1">
                      <div class="budget-price">
                        @php
                          $val = $t->total_terjual * 100 / $top;
                        @endphp
                        <div class="budget-price-square bg-primary" data-width="{{$val}}%" style="width: {{$val}}%;"></div>
                        <div class="budget-price-label">{{$t->total_terjual}} Terjual</div>
                      </div>
                      {{-- <div class="budget-price">
                        <div class="budget-price-square bg-danger" data-width="43%" style="width: 43%;"></div>
                        <div class="budget-price-label">$38,700</div>
                      </div> --}}
                    </div>
                  </div>
                </li>                  
                @endforeach
              </ul>
            </div>
            <div class="card-footer pt-3 d-flex justify-content-center">
              {{-- <div class="budget-price justify-content-center">
                <div class="budget-price-square bg-primary" data-width="20" style="width: 20px;"></div>
                <div class="budget-price-label">Selling Price</div>
              </div>
              <div class="budget-price justify-content-center">
                <div class="budget-price-square bg-danger" data-width="20" style="width: 20px;"></div>
                <div class="budget-price-label">Budget Price</div>
              </div> --}}
            </div>
          </div>          
        </div>
      </div>

      </div>            
    </div>
  </section>
</div>
@endsection

@section('modalcontent')

<div id="modal_container">

</div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/chart.min.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="{{ asset('assets/modules/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/js/page/modules-calendar.js') }}"></script>
@endsection

@section('scriptpage')
  <script>

  var balance_chart = document.getElementById("balance-chart").getContext('2d');
  var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
  balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

  var myChart = new Chart(balance_chart, {
    type: 'line',
    data: {
      labels: {!! json_encode($data[0]) !!},
      datasets: [{
        data: [{{implode(',', $data[1])}}],
        backgroundColor: balance_chart_bg_color,
        borderWidth: 3,
        borderColor: 'rgba(63,82,227,1)',
        pointBorderWidth: 0,
        pointBorderColor: 'transparent',
        pointRadius: 3,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
      }]
    },
    options: {
      layout: {
        padding: {
          bottom: -1,
          left: -1
        }
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            beginAtZero: true,
            display: false
          }
        }],
        xAxes: [{
          gridLines: {
            drawBorder: false,
            display: false,
          },
          ticks: {
            display: false
          }
        }]
      },
    }
  });

var sales_chart = document.getElementById("sales-chart").getContext('2d');

var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
sales_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
sales_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

var myChart = new Chart(sales_chart, {
  type: 'line',
  data: {
    labels: {!! json_encode($data2[0]) !!},
      datasets: [{
      data: [{{implode(',', $data2[1])}}],
      borderWidth: 2,
      backgroundColor: sales_chart_bg_color,
      borderWidth: 3,
      borderColor: 'rgba(63,82,227,1)',
      pointBorderWidth: 0,
      pointBorderColor: 'transparent',
      pointRadius: 3,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,1)',
    }]
  },
  options: {
    layout: {
      padding: {
        bottom: -1,
        left: -1
      }
    },
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          beginAtZero: true,
          display: false
        }
      }],
      xAxes: [{
        gridLines: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: false
        }
      }]
    },
  }
});

$('.ticket-item').click(function (e) { 
    e.preventDefault();
    var url = $(this).attr('href');
    // alert(url);
    $.ajax({
      type: "GET",
      url: url,
      dataType: "json",
      success: function (response) {
        $('#modal_container').html(response.modal);
        $('#detail').modal('show');
      }
    });
  });
  </script>
@endsection