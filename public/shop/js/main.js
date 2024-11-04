(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:2
            },
            576:{
                items:3
            },
            768:{
                items:4
            },
            992:{
                items:5
            },
            1200:{
                items:6
            }
        }
    });


    // Related carousel
    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        }
    });


    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        var max = button.parent().parent().find('input').attr('max');
        if (button.hasClass('btn-plus')) {
            if(oldValue < max){
                var newVal = parseFloat(oldValue) + 1;
            } else {
                var newVal = oldValue;
            }
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        button.parent().parent().find('input').val(newVal);
        // alert(button.parent().parent().find('input').attr('max'));
    });

    $('.fav-button').click(function (e) { 
        e.preventDefault();
        var slug = $(this).data("slug");
        var route = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: route,
            data: {
                slug: slug,
            },
            dataType: "json",
            success: function (response) {
                if (response) {
                    Swal.fire({
                        icon: response.status,
                        title: response.title,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    $('#fav_count').html(response.fav_count);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(
                    xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                );
            },
        });
    });

    $('.cart-button').click(function (e) { 
        e.preventDefault();
        var slug = $(this).data("slug");
        var route = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: route,
            data: {
                slug: slug,
            },
            dataType: "json",
            success: function (response) {
                if (response) {
                    Swal.fire({
                        icon: response.status,
                        title: response.title,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    $('#cart_count').html(response.cart_count);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(
                    xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                );
            },
        });
    });
    
    $('.cart-button2').click(function (e) { 
        e.preventDefault();
        var slug = $(this).data("slug");
        var route = $(this).attr("href");
        var qty = $(this).parent().find('.qty').val();
        $.ajax({
            type: "GET",
            url: route,
            data: {
                slug: slug,
                qty: qty,
            },
            dataType: "json",
            success: function (response) {
                if (response) {
                    Swal.fire({
                        icon: response.status,
                        title: response.title,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    $('#cart_count').html(response.cart_count);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(
                    xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                );
            },
        });
    });
})(jQuery);

