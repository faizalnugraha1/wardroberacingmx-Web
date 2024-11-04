@extends('admin.appadmin')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/chocolat/dist/css/chocolat.css') }}">
@endsection

@section('maincontent')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
        <h1>Barang</h1>
    </div>
    <div class="section-body">
      <h2 class="section-title">Kategori</h2>
      
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-server"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Kategori</h4>
                </div>
                <div class="card-body">
                    {{count($data)}}
                </div>
                </div>
            </div>
            </div>
            {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Paling Banyak Terjual</h4>
                </div>
                <div class="card-body">
                    42
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Paling Banyak Terjual</h4>
                </div>
                <div class="card-body">
                    47
                </div>
                </div>
            </div>
            </div>                   --}}
        </div>

        <div class="row">
            <div class="col-12">
                <div class="text-left mb-4">
                    <div>
                        <a href="#" class="btn btn-icon icon-right btn-success" data-toggle="modal" data-target="#kategoriModal">
                            <i class="fas fa-plus"></i> 
                            Tambah Kategori
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
                                  <th>Kategori</th>
                                  <th>Thumbnail</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class="text-center">
                                @foreach($data as $key=>$d)
                                <tr>
                                  <td class="align-middle">{{++$key}}</td>
                                  <td class="align-middle">{{ $d->nama }}</td>
                                  <td class="align-middle">
                                    <div class="gallery">
                                      <div class="gallery-item" data-image="{{ asset('images/'.$d->thumbnail) }}" data-title="{{ $d->nama }}" href="{{ asset('images/'.$d->thumbnail) }}" title="{{ $d->nama }}" style="background-image: url({{ asset('images/'.$d->thumbnail) }});"></div>                  
                                    </div>                          
                                  </td>
                                  <td class="align-middle">
                                    <div class="btn-toolbar justify-content-center" role="group">
                                      <a href="" class="edit btn btn-icon btn-warning" data-slug="{{ $d->slug }}"  data-toggle="tooltip" data-placement="top" data-url="{{route('admin.barang.kategori.edit')}}" data-original-title="Edit"><i class="fas fa-edit"></i></a>
                                      <form action="{{ route('admin.barang.kategori.delete', [$d->slug]) }}" method="POST">
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
<!-- Modal Add Galery -->
<div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.barang.kategori.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
        <!-- nama -->
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input name="nama" type="text" class="form-control">
          </div>
          <!-- Gambar -->
          <div class="form-group">
            <label>Thumbnail</label>
            <div class="col-sm-12 col-md-auto">
                <input type="file" name="thumbnail" class="dropify" data-show-remove="false" data-height="300" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M"/>
            </div>
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

<div class="modal fade" id="EditKategori" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
</div>
@endsection

@section('scriptlib')
<script src="{{ asset('assets/modules/dropify/dist/js/dropify.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
@endsection

@section('scriptpage')
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
  <script src="{{ asset('assets/js/views/kategori.js') }}"></script>
@endsection

@section('scriptline')
@endsection