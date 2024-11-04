"use strict";

var url = $("#myEvent").data('url');
$.ajax({
  type: "GET",
  url: url,
  success: function (response) {
    console.log(response);
    $("#myEvent").fullCalendar({
      height: 'auto',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
      editable: false,
      eventLimit: 3,
      eventClick: function(calEvent, jsEvent, view) {
        // alert('Event: ' + calEvent.kebutuhan);
        Swal.fire({
          html:`Jam : ${calEvent.jam_booking} <br> Atas Nama : ${calEvent.nama} <br> Kebutuhan : ${calEvent.kebutuhan} <br> Model Motor : ${calEvent.model_motor} <br> Keterangan : ${calEvent.keterangan}`,
        });
      },
      events: response
    });
  }
});