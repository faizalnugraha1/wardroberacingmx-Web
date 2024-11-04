{{-- <link rel="stylesheet" href="{{ asset('assets/modules/dropify/dist/css/dropify.css') }}"> --}}
<div id="temp_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Update Resi Kurir</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form enctype="multipart/form-data" class="needs-validation" novalidate=""  method="POST" action="{{ route('admin.order.update', ['invoice_id'=>$data->kode_invoice]) }}" >
      @csrf
      @method('PUT')

      <div class="modal-body">     
        
        <div class="form-group">
          <label>Kurir</label>
          <input name="kurir" type="text" class="form-control" required value="{{$data->kurir}}">
        </div>

        <div class="form-group">
          <label>Service</label>
          <input name="service" type="text" class="form-control" required value="{{$data->kurir_service}}">
        </div>

        <div class="form-group">
          <label>Resi</label>
          <input name="resi" type="text" class="form-control" required autofocus value="{{$data->resi}}">
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
    $(".needs-validation").submit(function () {
        var form = $(this);
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.addClass("was-validated");
    });
</script>