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

$('.tambah_select').select2({
    width: '100%',
    dropdownParent: "#barangModal"
});

$('.rupiah').toArray().forEach(function(field){
    new Cleave(field, {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
    });
});

$(".input_stok").inputSpinner();

$('.input_stok').on('keypress keyup paste', function(key) {
    if(key.charCode < 48 || key.charCode > 57) return false;
});

$(".summernote-deskripsi").summernote({
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


$(".del").click(function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
        title: `Hapus barang yang dipilih?`,
        text: "Jika barang ini dihapus maka tidak dapat dikembalikan lagi.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            form.submit();
        }
    });
});

$(document).on("click", ".edit", function (e) {
    e.preventDefault();
    var slug = $(this).data("slug");
    var route = $(this).data("url");
    $.ajax({
        type: "GET",
        url: route,
        data: {
            slug: slug,
        },
        dataType: "json",
        success: function (response) {
            $("#EditBarang").html(response.modal);
            $("#EditBarang").modal("show");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(
                xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            );
        },
    });
});

$("#new_dropify").click(function (event) {
    event.preventDefault();
    ele = `<div class="col-sm-4 my-2 dropify-col">
    <input type="file" name="images[]" class="dropify" accept=".png, .jpg, .jpeg" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="2M" multiple/>
    </div>`;
    $(ele).insertBefore($(this).parent());
    init_dropify();
});

