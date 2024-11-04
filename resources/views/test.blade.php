@extends('layouts.app')

@section('script_line')
    <script>    
        $.ajax({
            mode: 'cors',
            type: "GET",
            url: "https://api.rajaongkir.com/starter/province",
            data: {
                key: '2dc678be7192944adb7ab700e5d73dff'
            },
            dataType: "jsonp",
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Content-type': 'application/json',
            },
            success: function (response) {
                console.log(response);
            }
        });
    </script>
@endsection