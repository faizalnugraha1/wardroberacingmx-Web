{{-- <link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}"> --}}

<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form action="{{ route('admin.barang.update', [$barang->slug]) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
    <!-- Judul -->
    <div class="modal-body">
      <div class="form-group">
        <label>Nama Barang</label>
        <input name="nama" type="text" class="form-control" required value="{{$barang->nama}}">
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select class="form-control select2" name="kategori_id" data-placeholder="-Pilih Satu-" required>
          @foreach($kategori as $k)
          <option @if ($barang->kategori->id == $k->id) selected @endif value="{{ $k->id }}">{{ $k->nama }}</option>
          @endforeach
        </select>
        <div class="invalid-feedback">
          Kategori harus diisi
        </div>
      </div>

      <div class="form-group">
        <label>Brand/Merek</label>
        <select class="form-control select2" name="brand_id">
          <option> - </option>
          @foreach($brand as $b)
          @if ($barang->brand)
          <option @if ($barang->brand->id == $b->id) selected @endif value="{{ $b->id }}">{{ $b->nama }}</option>            
          @else
          <option value="{{ $b->id }}">{{ $b->nama }}</option>
          @endif
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
          <input type="text" name="harga_jual" class="form-control rupiah" required value="{{number_format($barang->harga_jual)}}">
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
          <input type="text" name="harga_asal" class="form-control rupiah" value="{{number_format($barang->harga_asal)}}">
        </div>
      </div>

      <div class="form-group">
        <label>Berat Barang</label>
        <div class="input-group">
          <input type="text" name="berat" class="form-control rupiah" required value="{{$barang->berat}}">
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
          <input class="input_stok2" type="number" min="0" name="stok" value="{{$barang->stok}}" step="1"/>
        </div>
        <div class="invalid-feedback">
          Stok barang tidak boleh dikosongkan
        </div>
      </div> 

      <!-- Gambar -->
      <div class="form-group">
        <label>Thumbnail</label>
        <div class="col-sm-12 col-md-auto">
            <input type="file" name="thumbnail" class="dropify" data-show-remove="false" data-height="300" data-default-file="{{ asset('images/'.$barang->thumbnail) }}" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M"/>
        </div>
      </div>              
      <div class="form-group">
        <label>Gambar Lainnya</label>
        <div class="row">
          <input type="hidden" name="images_old[]" value="">
          @foreach ($barang->images as $k=>$i)
          <div class="col-sm-4 my-2">
            <div class="avatar-item">
              <input type="hidden" name="images_old[]" value="{{$i->id}}">
              <img src="{{asset('images/'.$i->file)}}" class="img-fluid rounded-0">
              <div class="avatar-badge bg-danger text-white delete-images"><i class="fas fa-trash"></i></div>
            </div>
          </div>
          @endforeach   
          <div class="col-sm-4 my-2 dropify-col">
            <input type="file" name="images[]" class="dropify" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M" multiple/>
          </div>  
          <div class="col-sm-4 my-2 d-flex justify-content-center align-items-center p-3">
            <button type="button" id="new_dropify2" class="btn btn-icon btn-lg btn-outline-primary"><i class="fas fa-plus"></i></button>
          </div>              
        </div>
      </div>    
      
      <div class="form-group">
        <label>Deskripsi</label>
        <textarea id="edit_deskripsi" name="deskripsi" class="summernote-deskripsi"></textarea>
      </div>

      <div class="form-group">
        <label>Keywords (pencarian)</label>
        <textarea class="form-control" name="keywords" id="" rows="3">{{$barang->keywords}}</textarea>
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
   function init_dropify(){
    $(".dropify").dropify({
        showRemove: true,
        messages: {
            default: "Tarik dan lepaskan file atau klik disini",
            replace: "Tarik dan lepaskan file atau klik disini untuk mengganti",
            remove: "Remove",
            error: "Ooops, terjadi kesalahan.",
        },
    });

    
    $('.dropify-clear').click(function (event) {
        $(this).closest('.dropify-col').remove();
    });
  }
  init_dropify();

  $('.select2').select2({
    width: '100%',
});

  $('.rupiah').toArray().forEach(function(field){
    new Cleave(field, {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
    });
  });

  $(".input_stok2").inputSpinner();  

  $('.input_stok2').on('keypress keyup paste', function(key) {
    if(key.charCode < 48 || key.charCode > 57) return false;
  });

  
  $("#edit_deskripsi").summernote({
    dialogsInBody: true,
    minHeight: 250,
    toolbar: [
        // [groupName, [list of button]]
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
      ],
    lang: "en-US", // Change to your chosen language
    imageAttributes: {
        icon: '<i class="note-icon-pencil"/>',
        removeEmpty: false, // true = remove attributes | false = leave empty if present
        disableUpload: false, // true = don't display Upload Options | Display Upload Options
    },
  });
  $("#edit_deskripsi").summernote("code", `{!! $barang->deskripsi !!}`);

  $('.delete-images').click(function (e) { 
    e.preventDefault();
    $(this).parent().parent().remove();
  });

  $("#new_dropify2").click(function (event) {
    event.preventDefault();
    ele = `<div class="col-sm-4 my-2 dropify-col">
    <input type="file" name="images[]" class="dropify" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M" multiple/>
    </div>`;
    $(ele).insertBefore($(this).parent());
    init_dropify();
  });

</script>