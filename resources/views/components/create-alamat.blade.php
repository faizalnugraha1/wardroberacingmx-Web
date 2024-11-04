<div id="create_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Alamat</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="needs-validation" method="POST" novalidate="" action="{{ route('user.store.alamat') }}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                          <label>Provinsi</label>
                          <select id="select_provinsi" class="form-control select2" name="provinsi" data-url="{{ route('prov.list') }}" required>
                            <option></option>
                          </select>
                          <div class="invalid-feedback">
                            Provinsi harus diisi
                          </div>
                        </div>
                        <div class="form-group col-6">
                          <label>Kota/Kabupaten</label>
                          <select id="select_kota" class="form-control select2" name="kota" required data-placeholder="Pilih Kota/Kabupaten" data-hide-search="true" data-url="{{ route('kota.list') }}">
                            <option></option>
                          </select>
                          <div class="invalid-feedback">
                            Kota/Kabupaten harus diisi
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-6">
                          <label>Kecamatan</label>
                          <select id="select_kecamatan" class="form-control select2" name="kecamatan" required data-placeholder="Pilih Kecamatan" data-hide-search="true" data-url="{{ route('kecamatan.list') }}">
                            <option></option>
                          </select>
                          <div class="invalid-feedback">
                            Kecamatan harus diisi
                          </div>
                        </div>
                        <div class="form-group col-6">
                          <label for="kelurahan">Kelurahan</label>
                          <input type="text" class="form-control" name="kelurahan" placeholder="(Opsional)">
                        </div>
                      </div>
    
                      <div class="row">
                        <div class="form-group col-6">
                          <label for="kode_pos">Kode Pos</label>
                          <input type="text" class="form-control" name="kode_pos" required>
                        </div>
                      </div>
    
                      <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" name="alamat" required>
                        <div class="invalid-feedback">
                        </div>
                      </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>    

<script src="{{ asset('assets/js/views/registration.js') }}"></script>

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