/**
 * Template Name: Arsha - v4.3.0
 * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
(function () {
  "use strict";

  const onscroll = (el, listener) => {
    el.addEventListener("scroll", listener);
  };
  
  if($(window).scrollTop()){
    $("#header").addClass("header-scrolled");
  };

  $(window).scroll(function () {
    if ($(window).scrollTop() >= 50) {
      $("#header").addClass("header-scrolled");
    } else {
      $("#header").removeClass("header-scrolled");
    }
  });

  var fullHeight = function () {
    $(".js-fullheight").css("height", $(window).height());
    $(window).resize(function () {
      $(".js-fullheight").css("height", $(window).height());
    });
  };
  fullHeight();

  // loader
  var loader = function () {
    setTimeout(function () {
      if ($("#ftco-loader").length > 0) {
        $("#ftco-loader").removeClass("show");
      }
    }, 1);
  };
  loader();

  if($(".marquee").length){
    $(".marquee").marquee({
      speed : 50,
      gap: 50,
      delayBeforeStart: 0,
      direction: "left",      
      duplicated: true,
      pauseOnHover : true,
    });    
  }

  // if(jQuery().carousel) 
  if($(".home-slider").length){
    $(".home-slider").owlCarousel({
      loop: true,
      autoplay: true,
      margin: 0,
      animateOut: "fadeOut",
      animateIn: "fadeIn",
      nav: true,
      dots: true,
      autoplayHoverPause: false,
      items: 1,
      navText: [
        "<span class='ion-ios-arrow-back'></span>",
        "<span class='ion-ios-arrow-forward'></span>",
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 1,
        },
        1000: {
          items: 1,
        },
      },
    });
  } 
  if($(".owl-banner").length){
    $(".owl-banner").owlCarousel({
      items: 1,
      loop: true,
      dots: true,
      nav: false,
      autoplay: true,
      margin: 0,
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 1,
        },
        1000: {
          items: 1,
        },
        1600: {
          items: 1,
        },
      },
    });
  }
  
  if($(".owl-services").length){
    $(".owl-services").owlCarousel({
      items: 4,
      loop: true,
      dots: true,
      nav: false,
      autoplay: true,
      margin: 5,
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 2,
        },
        1000: {
          items: 3,
        },
        1600: {
          items: 4,
        },
      },
    });    
  }

  if($(".owl-portfolio").length){
    $(".owl-portfolio").owlCarousel({
      items: 4,
      loop: false,
      dots: true,
      nav: true,
      navText: [
        '<i class="fas fa-chevron-left owl-cust-btn"></i>',
        '<i class="fas fa-chevron-right owl-cust-btn"></i>',
      ],
      autoplay: false,
      margin: 30,
      responsive: {
        0: {
          items: 1,
        },
        700: {
          items: 2,
        },
        1000: {
          items: 3,
        },
        1600: {
          items: 4,
        },
      },
    });    
  }

  if($(".owl-gallery").length){
    $(".owl-gallery").owlCarousel({
      items: 4,
      rows: 2,
      loop: false,
      dots: true,
      nav: true,
      navText: [
        '<i class="fas fa-chevron-left owl-cust-btn"></i>',
        '<i class="fas fa-chevron-right owl-cust-btn"></i>',
      ],
      autoplay: false,
      margin: 30,
      responsive: {
        0: {
          items: 1,
        },
        700: {
          items: 2,
        },
        1000: {
          items: 3,
        },
        1600: {
          items: 4,
        },
      },
    });    
  }

  if($(".carousel-testimony").length){
    $(".carousel-testimony").owlCarousel({
      center: true,
      loop: true,
      items: 1,
      margin: 30,
      stagePadding: 0,
      nav: false,
      navText: [
        '<span class="ion-ios-arrow-back">',
        '<span class="ion-ios-arrow-forward">',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 2,
        },
        1000: {
          items: 3,
        },
      },
    });
    
  }

  if($("#owl-donasi").length){
    $("#owl-donasi").owlCarousel({
      center: true,
      loop: true,
      items: 1,
      nav: false,
      dots: false,
      margin: 0,
      animateOut: "fadeOut",
      animateIn: "fadeIn",
      autoplay: true,
      mouseDrag: false,
      touchDrag: false
    });  
  }

  if($("#slick-gallery").length){
    $("#slick-gallery").slick({
      lazyLoad: 'ondemand',
      rows: 2,
      dots: true,
      prevArrow: '<i class="fas fa-chevron-left sa-left"></i>',
      nextArrow: '<i class="fas fa-chevron-right sa-right"></i>',
      infinite: false,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 2,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          },
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });
  }


  if (typeof yourFunctionName == 'function') { 
    lightbox.option({
      fitImagesInViewport: true,
      disableScrolling: true,
    });
  }

  // NAVBAR TOGGLE MOBILE VIEW
  $(".mobile-nav-toggle").on("click", function () {
    $("#navbar").toggleClass("navbar-mobile");
    $(this).toggleClass("fa-bars");
    $(this).toggleClass("fa-times");
  });

  $(".navbar .dropdown > a").on("click", function (e) {
    if ($("#navbar").hasClass("navbar-mobile")) {
      e.preventDefault();
      $(this).next().toggleClass("dropdown-active");
    }
  });

  window.addEventListener('load', () => {
    AOS.init({
      container: ".aos-container",
      duration: 1000,
      easing: "ease-in-out",
      once: true,
      mirror: false
    });
  });

})();
