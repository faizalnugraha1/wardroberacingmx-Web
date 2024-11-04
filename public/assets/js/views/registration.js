if ($('.nomer_hp').length > 0){
  var cleavePN = new Cleave('.nomer_hp', {
    phone: true,
    phoneRegionCode: 'id'
  });
}

$('input[name=kode_pos]').on('keypress keyup paste', function(key) {
  if(key.charCode < 48 || key.charCode > 57) return false;
});

var list_provinsi;
function data() {
  var url = $('#select_provinsi').data('url');

  $.ajax({
    url: url,
    type: 'GET',
    success: function(response) {
        list_provinsi = response;

        $('#select_provinsi').select2({
          placeholder: "Pilih Provinsi",
          width: "100%",
          data: response
        });
    }
  });
}
data();

$('#select_provinsi').on("select2:select", function (e) { 
  var url = $('#select_kota').data('url');

  $.ajax({
    url: url,
    type: 'GET',
    data: {
      provinsi_id: e.params.data.id
    },
    success: function(response) {

        $("#select_kota").empty().select2({
          width: "100%",
          data: response,
          placeholder: "--Pilih Kota--"
        });

        $("#select_kota").val(null).trigger("change");

        $("#select_kecamatan").empty();
        $("#select_kelurahan").empty();
    }
  });

});

$('#select_kota').on("select2:select", function (e) { 
  var url = $('#select_kecamatan').data('url');

  $.ajax({
    url: url,
    type: 'GET',
    data: {
      kota_id: e.params.data.id
    },
    success: function(response) {
        $("#select_kecamatan").empty().select2({
          width: "100%",
          data: response,
          placeholder: "--Pilih Kecamatan--"
        });

        $("#select_kecamatan").val(null).trigger("change");

        $("#select_kelurahan").empty();
    }
  });

});