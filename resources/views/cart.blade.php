@extends('layouts.app')

@section('content')

    <!-- Cart Start -->
    <div id="cart_container" class="container-fluid">
       @include('components.cart-container')
    </div>

    <!-- Cart End -->

@endsection

@section('script_line')
<script>
    
    function initFunction()
    {
        $('.del_cart').click(function (e) { 
            e.preventDefault();
            // var slug = $(this).data("slug");
            var route = $(this).data("url");
            var id = $(this).closest('tr').data('val');
            var row = $(this).closest('tr');
            // alert(route + '/' + id);
            $.ajax({
                type: "GET",
                url: route,
                data: {
                    id: id,
                },
                dataType: "json",
                success: function (response) {
                    if (response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: response.status,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true,
                                position: 'bottom-right'
                            });

                            $('#cart_count').html(response.cart_count);
                            $('#cart_total').html(response.subtotal);
                            row.remove();

                            if (response.jumlah_kosong > 0) {
                                $('#kosong_devider').show();
                            } else {
                                $('#kosong_devider').hide();
                            }
                        } else if (response.status == 'warning'){
                            location.reload();
                        }
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(
                        xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                    );
                },
            });
        });
        
        $('.quantity2 button').on('click', function () {
            var button = $(this);
            var route = $(this).parent().parent().data("url");
            var id = $(this).closest('tr').data('val');
            var oldValue = parseFloat(button.parent().parent().find('input').val());
            var max = button.parent().parent().find('input').attr('max');
            if (button.hasClass('btn-plus')) {
                if(oldValue < max){
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    var newVal = oldValue;
                }
            } else {
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }

            button.parent().parent().find('input').val(newVal);

            if (newVal > 1) {
                button.parent().parent().find('button.btn-minus').removeAttr('disabled');
            } else {
                button.parent().parent().find('button.btn-minus').attr('disabled', 'disabled');
            }

            if (newVal == max) {
                button.parent().parent().find('button.btn-plus').attr('disabled', 'disabled');
            } else {
                button.parent().parent().find('button.btn-plus').removeAttr('disabled');
            }

            clearTimeout(timer);
            timer = setTimeout(function() {
                // alert(route + '/' + id + '/' + newVal);
                // console.log(newVal);
                $.ajax({
                    type: "GET",
                    url: route,
                    data: {
                        id: id,
                        qty: newVal,
                    },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            toast: true,
                            position: 'bottom-right'
                        });
                        if (response.status == 'success') {
                            $('#cart_container').html(response.view);
                            initFunction();
                        } else if (response.status == 'error') {
                            button.parent().parent().find('input').val(response.qty);
                            button.parent().parent().find('input').attr('max', response.max);
                            if (response.qty == response.max) {
                                button.parent().parent().find('button.btn-plus').attr('disabled', 'disabled');
                            } 
                        }
                    }
                });
            }, delay );
        });
    };

    var timer, delay = 500;
    initFunction();
</script>
@endsection