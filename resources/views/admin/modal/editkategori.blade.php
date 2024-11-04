{{-- <link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}"> --}}

<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form action="{{ route('admin.barang.kategori.update', [$kategori->slug]) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
    <!-- Judul -->
    <div class="modal-body">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input name="nama" type="text" class="form-control" value="{{$kategori->nama}}">
      </div>
      <!-- Gambar -->
      <div class="form-group">
        <label>Thumbnail</label>
        <div class="col-sm-12 col-md-auto">
            <input type="file" name="thumbnail" class="dropify" data-show-remove="false" data-height="300" data-default-file="{{ asset('images/'.$kategori->thumbnail) }}" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M"/>
        </div>
      </div>              
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-success">Update</button>
    </div>
  </form>
  </div>
</div>
</div>

{{-- <script src="{{ asset('assets/modules/dropify/dist/js/dropify.js') }}"></script> --}}
<script>
   $('.dropify').dropify({
    messages: {
        'default': 'Tarik dan lepaskan file atau klik disini',
        'replace': 'Tarik dan lepaskan file atau klik disini untuk mengganti',
        'remove':  'Remove',
        'error':   'Ooops, kesalahan terjadi.'
    }
  }); 
</script>