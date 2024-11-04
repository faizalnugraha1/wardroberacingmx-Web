var row = $('#tjasa').data('row1');
var row2 = 0;

function initOnchange()
{    
  $('.input-harga, .input-jumlah').on('input', function() {
    var trow = $(this).closest('tr');
    var harga = trow.find('.input-harga').val();
    var qty = trow.find('.input-jumlah').val();
    trow.find('.input-total').val(harga * qty);
    total_nilai();
  });
}
initOnchange();

function total_nilai() {
  var total = 0;
  $('.input-total').each(function(i, element) {
      total += +$(this).val();
  });
  $('#total-inv').html(total);
}

$('#addBtn').click(function (e) { 
  e.preventDefault();
  var j = $('#input-j').val();
  var p = $('#input-p').val();
  var q = $('#input-q').val();
  $('#input-j').val('');
  $('#input-p').val('');
  $('#input-q').val('');
  row++;

  var ele = `<tr>
              <td class="row_number">${row}</td>
              <td><input type="text" name="nama_jasa[]" class="form-control form-control-sm" value="${j}" required></td>
              <td class="text-center"><input type="text" name="harga_jasa[]" class="input-harga form-control form-control-sm" value="${p}" required></td>
              <td class="text-center"><input type="text" name="qty_jasa[]" class="input-jumlah form-control form-control-sm" value="${q}" required></td>
              <td class="text-right"><input type="text" name="total_jasa[]" class="input-total form-control form-control-sm" readonly value="${q * p}"></td>
              <td class="text-center">
                <button class="btn btn-danger remove"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>`

  $('#tjasa').append(ele);

  total_nilai();
  initOnchange();
  initRemove();
});

function initRemove(){
  $('.remove').click(function (e) { 
    e.preventDefault();
    row--;
    $(this).closest('tr').remove();

    var newRow = 1;
    $('#tjasa tr').each(function(){
      $(this).find('.row_number').text(newRow);      
      newRow++;
    })

    total_nilai();
  });
}
initRemove();

function initRemove2(){
  $('.remove2').click(function (e) { 
    e.preventDefault();
    row2--;
    $(this).closest('tr').remove();

    var newRow = 1;
    $('#tbarang tr').each(function(){
      $(this).find('.row_number').text(newRow);      
      newRow++;
    })

    total_nilai();
  });
}

$('#addBtn2').click(function (e) { 
  e.preventDefault();
  var route = $(this).data("url");
  $.ajax({
      type: "GET",
      url: route,
      dataType: "json",
      success: function (response) {
          $("#addBarang").html(response.modal);
          $("#addBarang").modal("show");
      },
      error: function (xhr, ajaxOptions, thrownError) {
          console.log(
              xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          );
      },
  });
});
