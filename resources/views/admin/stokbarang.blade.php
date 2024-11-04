@extends('admin.appadmin')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/chocolat/dist/css/chocolat.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">

@endsection

@section('maincontent')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
        <h1>Stok Barang</h1>
        <a href="#" class="btn btn-icon btn-info  ml-auto" data-toggle="modal" data-target="#printModal"><i class="fas fa-print"></i></a> 
    </div>
    <div class="section-body">
      
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-server"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Barang</h4>
                </div>
                <div class="card-body">
                    {{count($data)}}
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-truck-loading"></i>
                  </div>
                  <div class="card-wrap">
                  <div class="card-header">
                      <h4>Hampir Habis</h4>
                  </div>
                  <div class="card-body">
                    @if (count($habis) == 0)
                    -
                    @else
                    {{count($habis)}} Barang
                    @endif
                  </div>
                  </div>
              </div>
              </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="row py-3 pr-3">
                  @if ($sipaling)
                  <div class="col-6 pl-0">
                    <p class="mb-1 text-muted">Paling Banyak terjual</p>
                    <h4>{{$sipaling->total_terjual}}</h4>
                  </div>
                  <div class="col-6 text-right align-middle my-auto p-0">
                    <h5>{{$sipaling->barang->nama}}</h5>
                  </div>
                  @else                    
                  <div class="col-6 pl-0">
                    <p class="mb-1 text-muted">Paling Banyak terjual</p>
                    <h4>-</h4>
                  </div>
                  <div class="col-6 text-right align-middle my-auto p-0">
                    <h5>-</h5>
                  </div>                    
                  @endif
                </div>
            </div>
            </div>                              
        </div>

        <div class="row">
            <div class="col-12">
                <div class="text-left mb-4">
                    <div>
                        <a href="#" class="btn btn-icon icon-right btn-success" data-toggle="modal" data-target="#barangModal">
                            <i class="fas fa-plus"></i> 
                            Tambah Barang Baru
                        </a>                        
                    </div>
                </div>
                @include('admin.component.alert-error')
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                              <thead  class="text-center">
                                <tr>
                                  <th>No</th>
                                  <th>Nama</th>
                                  <th>Kategori</th>
                                  <th>Brand</th>
                                  <th>Stok</th>
                                  <th>Harga</th>
                                  <th>Foto</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class="text-center">
                                @foreach($data as $key=>$d)
                                <tr>
                                  <td class="align-middle">{{++$key}}</td>
                                  <td class="align-middle">{{ $d->nama }}</td>
                                  <td class="align-middle">{{ $d->kategori->nama }}</td>
                                  <td class="align-middle">@if ($d->brand) {{ $d->brand->nama }} @else - @endif</td>
                                  <td class="align-middle">{{ $d->stok }}</td>
                                  <td class="align-middle">Rp. {{ number_format($d->harga_jual) }} @if($d->harga_asal || $d->harga_asal != 0) (<del>{{number_format($d->harga_asal)}}</del>) @endif</td>
                                  <td class="align-middle">
                                    <div class="gallery">
                                      <div class="gallery-item" data-image="{{ asset('images/'.$d->thumbnail) }}" data-title="{{ $d->nama }}" href="{{ asset('images/'.$d->thumbnail) }}" title="{{ $d->nama }}" style="background-image: url({{ asset('images/'.$d->thumbnail) }});"></div>                  
                                      @foreach ($d->images as $k=>$i)
                                      @if ($loop->first)
                                      <div class="gallery-item gallery-more" data-image="{{asset('images/'.$i->file)}}">
                                        <div>+{{count($d->images)}}</div>
                                      </div>                                        
                                      @else
                                      <div class="gallery-item gallery-hide" data-image="{{asset('images/'.$i->file)}}"></div>                                        
                                      @endif
                                      @endforeach                      
                                    </div>    
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-toolbar justify-content-center" role="group">
                                      <a href="" class="edit btn btn-icon btn-warning" data-slug="{{ $d->slug }}"  data-toggle="tooltip" data-placement="top" data-url="{{route('admin.barang.edit')}}" data-original-title="Edit"><i class="fas fa-edit"></i></a>
                                      <form action="{{ route('admin.barang.delete', [$d->slug]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="del btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Hapus"><i class="fas fa-trash"></i></button>
                                      </form>
                                    </div>
                                  </td>
                                </tr>  
                                @endforeach                  
                              </tbody>
                            </table>
                        </div>
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

<div class="modal fade" id="barangModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.barang.store') }}" class="needs-validation" method="POST" novalidate="" enctype="multipart/form-data">
          @csrf
          @method('POST')
        <!-- nama -->
        <div class="modal-body">
          
          <div class="form-group">
            <label>Nama Barang</label>
            <input name="nama" type="text" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <select class="form-control select2 tambah_select" name="kategori_id" data-placeholder="-Pilih Satu-" required>
              <option></option>
              @foreach($kategori as $k)
              <option value="{{ $k->id }}">{{ $k->nama }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">
              Kategori harus diisi
            </div>
          </div>

          <div class="form-group">
            <label>Brand/Merek</label>
            <select class="form-control select2 tambah_select" name="brand_id">
              <option> - </option>
              @foreach($brand as $b)
              <option value="{{ $b->id }}">{{ $b->nama }}</option>
              @endforeach
            </select>
          </div>


          <div class="form-group">
            <label>Harga Jual</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  Rp.
                </div>
              </div>
              <input type="text" name="harga_jual" class="form-control rupiah" required>
              <div class="invalid-feedback">
                Harga Jual tidak boleh kosong
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Harga Asal (Diskon)</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  Rp.
                </div>
              </div>
              <input type="text" name="harga_asal" class="form-control rupiah">
            </div>
          </div>

          <div class="form-group">
            <label>Berat Barang</label>
            <div class="input-group">
              <input type="text" name="berat" class="form-control rupiah" required>
              <div class="input-group-prepend">
                <div class="input-group-text">
                  Gram
                </div>
              </div>              
            </div>
            <div class="invalid-feedback">
                Berat barang tidak boleh kosong
              </div>
          </div>

          <div class="form-group">
            <label>Jumlah stok</label>
            <div class="col-12">
              <input class="input_stok" type="number" min="0" name="stok" value="0" step="1"/>
            </div>
            <div class="invalid-feedback">
              Stok barang tidak boleh dikosongkan
            </div>
          </div> 

          <!-- Gambar -->
          <div class="form-group">
            <label>Thumbnail</label>
            <div class="col-sm-12 col-md-auto">
                <input type="file" name="thumbnail" class="dropify" data-show-remove="false" data-height="300" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M"/>
            </div>
          </div>              
          <div class="form-group">
            <label>Gambar Lainnya
            </label>
            <div class="row">
              <div class="col-sm-4 my-2 dropify-col">
                  <input type="file" name="images[]" class="dropify" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M"/>
              </div>
              <div class="col-sm-4 my-2 d-flex justify-content-center align-items-center p-3">
                <button id="new_dropify" class="btn btn-icon btn-lg btn-outline-primary"><i class="fas fa-plus"></i></button>
              </div>              
            </div>
          </div>    
          
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="summernote-deskripsi"></textarea>
          </div>

          <div class="form-group">
            <label>Keywords (pencarian)</label>
            <textarea class="form-control" name="keywords" id="" rows="3"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
          
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="printModal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Print Laporan Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.barang.print') }}" method="GET" target="_blank"> 
        <div class="row">
            <div class="col-9">
              <select class="form-control select2 tambah_select" name="tanggal" data-placeholder="Pilih Bulan" data-hide-search="true">
                <option></option>
                @foreach($tanggal as $t)
                <option value="{{$t['year'].'-'.$t['month']}}">{{\Carbon\Carbon::parse($t['month'])->translatedFormat('F').' - '.$t['year']}}</option>
                @endforeach
              </select>              
            </div>
            <div class="col-3">
              <button type="submit" class="btn btn-success">Print</button>
            </div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        {{-- <button type="submit" class="btn btn-success">Submit</button> --}}
      </div>

        
    </div>
  </div>
</div>

<div class="modal fade" id="EditBarang" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
</div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/dropify/dist/js/dropify.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/modules/cleave-js/dist/cleave.min.js') }}"></script>
<script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap-input-spinner/src/bootstrap-input-spinner.js') }}"></script>
@endsection

@section('scriptpage')
<script src="{{ asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
<script src="{{ asset('assets/js/page/auth-register.js') }}"></script>
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
<script src="{{ asset('assets/js/views/barang.js') }}"></script>
@endsection

@section('scriptline')
<script>
  $('#select_print').select2({
    width: '100%',
    dropdownParent: "#printModal",
    minimumResultsForSearch: -1,
    dropdownParent: "#printModal"
});
</script>
@endsection