$(".approve").click(function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: `Terima Jadwal Boooking?`,
        // text: "Jika Kategori ini dihapus maka tidak dapat dikembalikan lagi.",
        icon: 'question',
        buttons: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

$(".tolak").click(function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: `Tolak/Batalkan Jadwal Boooking?`,
        // text: "Jika Kategori ini dihapus maka tidak dapat dikembalikan lagi.",
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Keterangan penolakan/pembatalan',
        inputPlaceholder: 'Tulis keterangan...',
        showCancelButton: true,
        buttons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.append(`<input type="hidden" name="keterangan" value="${result.value}" /> `);
            form.submit();
            // alert(result.value);
        }
    });
});

$(".pengerjaan").click(function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: `Lanjutkan ke Pengerjaan?`,
        icon: 'question',
        buttons: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

$(".checkout").click(function (event) {
    var route = $(this).data("url");
    event.preventDefault();
    Swal.fire({
        title: `Selesaikan pengerjaan?`,
        icon: 'question',
        buttons: true,
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = route;
        }
    });
});

$(document).on("click", ".view", function (e) {
    e.preventDefault();
    // var slug = $(this).data("slug");
    var route = $(this).data("url");
    // alert(route);
    $.ajax({
        type: "GET",
        url: route,
        dataType: "json",
        success: function (response) {
            $("#viewBooking").html(response.modal);
            $("#viewBooking").modal("show");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(
                xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            );
        },
    });
});
