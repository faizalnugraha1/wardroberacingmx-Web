{{-- <link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}"> --}}
<div id="temp_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Periksa Order</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form id="recheck_form" method="POST" action="{{ route('admin.order.to_kurir', ['invoice_id'=>$data[0]->invoice->kode_invoice]) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
  
    <div class="modal-body">

      <div class="table-responsive">
        <table class="table table-striped" id="table-2">
          <thead>
            <tr>
              <th class="text-center">
                <div class="custom-checkbox custom-control">
                  <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                  <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                </div>
              </th>
              <th>Barang</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $key => $d)
            <tr>
              <td>
                <div class="custom-checkbox custom-control">
                  <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-{{++$key}}">
                  <label for="checkbox-{{$key}}" class="custom-control-label">&nbsp;</label>
                </div>
              </td>
              <td class="align-left">{{$d->barang->nama}}</td>
              <td class="align-middle">x{{$d->qty}}</td>
            </tr>              
            @endforeach
          </tbody>
        </table>
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

{{-- <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script> --}}

<script>
  $("[data-checkboxes]").each(function() {
  var me = $(this),
    group = me.data('checkboxes'),
    role = me.data('checkbox-role');

  me.change(function() {
    var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
      checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
      dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
      total = all.length,
      checked_length = checked.length;

    if(role == 'dad') {
      if(me.is(':checked')) {
        all.prop('checked', true);
      }else{
        all.prop('checked', false);
      }
    }else{
      if(checked_length >= total) {
        dad.prop('checked', true);
      }else{
        dad.prop('checked', false);
      }
    }
  });
});

$('#recheck_form').submit(function (e) { 
  // e.preventDefault();
  var all = $('[data-checkboxes]').length;
  var checked = $('[data-checkboxes]:checked').length;
  if (checked != all) {
    Swal.fire({
        title: `Perhatian!`,
        text: "Pastikan semua barang telah dichecklist.",
        icon: 'warning',
        showConfirmButton: false,
        timer: 1500
    })

    return false;
  }
});

</script>