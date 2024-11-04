
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Tambah Barang ke Checkout</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!-- Judul -->
    <div class="modal-body">
      <div class="form-group">
        <label>Barang</label>
        <select id="select_barang" class="form-control select2" name="barang_id">
          <option></option>
          @foreach($barang as $b)
          <option value="{{ $b->id }}" data-route="{{ route('admin.barang.databarang', ['id'=>$b->id]) }}">{{ $b->nama }}</option>            
          @endforeach
        </select>
      </div>

      <input type="hidden" id="nama_barang">
      <input type="hidden" id="id_barang" name="id_barang[]">

      <div class="form-group">
        <label>Harga Barang</label>
        <input id="harga_barang" type="text" class="form-control" readonly value="">
      </div>

      <div class="form-group">
        <label>Jumlah</label>
        <input id="jumlah_barang" type="text" class="form-control" value="0" min="0" max="0">
      </div>

      <div class="form-group">
        <label>Total</label>
        <input id="total_barang" type="text" class="form-control" readonly value="">
      </div> 

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button id="btn-tambahkan" type="button" class="btn btn-success" data-dismiss="modal">Tambahkan</button>
    </div>
  </div>
</div>
</div>

<script>
 $('.select2').select2({
    width: '100%',
    placeholder: 'Pilih Barang',
    dropdownParent: $("#addBarang")
});

$('#jumlah_barang').inputSpinner();

$('#select_barang').on("select2:select", function (e) { 

  var url = $(this).select2().find(":selected").data("route");
  $.ajax({
    url: url,
    type: 'GET',
    success: function(response) {
        // console.log(response);
        $('#jumlah_barang').val(0);

        $("#nama_barang").val(response.barang.nama);
        $("#id_barang").val(response.barang.id);
        $("#harga_barang").val(response.barang.harga_jual);
        $("#total_barang").val(response.barang.harga_jual);
        $("#jumlah_barang").attr("max", response.barang.stok);

        hitung_total();
    }
  });

});

$('#jumlah_barang').on('input change', function() {
    hitung_total();
  });

function hitung_total(){
  var jumlah = $("#jumlah_barang").val();
  var harga = $("#harga_barang").val();
  var total = jumlah * harga;
  $("#total_barang").val(total);
}

$('#btn-tambahkan').click(function (e) { 
  e.preventDefault();
  var nama_barang = $("#nama_barang").val();
  var id_barang = $("#id_barang").val();
  var harga_barang = $("#harga_barang").val();
  var jumlah = $("#jumlah_barang").val();
  var total = $("#total_barang").val();

  row2++;

  var ele = `<tr>
              <td class="row_number">${row2}</td>
              <td><input type="hidden" name="id_barang[]" value="${id_barang}" required> <input type="hidden" name="nama_barang[]" value="${nama_barang}" required> ${nama_barang}</td>
              <td class="text-center"><input type="text" name="harga_barang[]" class="input-harga form-control form-control-sm" readonly value="${harga_barang}"></td>
              <td class="text-center"><input type="text" name="qty_barang[]" class="input-jumlah form-control form-control-sm" readonly value="${jumlah}" required></td>
              <td class="text-right"><input type="text" name="total_barang[]" class="input-total form-control form-control-sm" readonly value="${total}"></td>
              <td class="text-center">
                <button class="btn btn-danger remove2"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>`

  $('#tbarang').append(ele);
  total_nilai();
  initOnchange();
  initRemove2();
  // test();
});
</script>