$(".dropify").dropify({
    messages: {
        default: "Tarik dan lepaskan file atau klik disini",
        replace: "Tarik dan lepaskan file atau klik disini untuk mengganti",
        remove: "Remove",
        error: "Ooops, kesalahan terjadi.",
    },
});


$(".del").click(function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal({
        title: `Hapus Brand yang dipilih?`,
        text: "Jika Brand ini dihapus maka tidak dapat dikembalikan lagi.",
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
            $("#EditBrand").html(response.modal);
            $("#EditBrand").modal("show");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(
                xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            );
        },
    });
});