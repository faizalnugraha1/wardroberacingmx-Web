$vidlink = $('#yt_single').attr('yt-link');
var regExp = /^https?\:\/\/(?:www\.youtube(?:\-nocookie)?\.com\/|m\.youtube\.com\/|youtube\.com\/)?(?:ytscreeningroom\?vi?=|youtu\.be\/|vi?\/|user\/.+\/u\/\w{1,2}\/|embed\/|watch\?(?:.*\&)?vi?=|\&vi?=|\?(?:.*\&)?vi?=)([^#\&\?\n\/<>"']*)/i;
$singleID = $vidlink.match(regExp);
$channelId = $('#yt_res').attr('yt-id');
$apikey = "AIzaSyBswKibMjCpmTpH9Z9g7v88KJl7xxhvGa8";
$total = $('#yt_res').data('item');

$.get(
  "https://www.googleapis.com/youtube/v3/videos",
  {
    part: "snippet",
    key: $apikey,
    id: $singleID[1]
  },
  function(data){
    thumb = data.items[0].snippet.thumbnails;
    if ('maxres' in thumb) {
      vidThumb = thumb.maxres.url;
    } else {
      vidThumb = thumb.medium.url;
    }

    output = '<div class="yt-thumb"><a href="'+ $vidlink +'"  data-lity><img src="' + vidThumb + 
    '" alt="" /><div class="overlay d-flex align-items-center justify-content-center"><i class="fab fa-youtube"></i></div></a></div>';

    $("#yt_single").append(output);
  }
)
$.get(
  "https://www.googleapis.com/youtube/v3/channels",
  {
    part: "snippet,statistics,contentDetails",
    key: $apikey,
    id: $channelId,
  },
  function (data) {
    $pl_id = data.items[0].contentDetails.relatedPlaylists.uploads;
    $.get(
      "https://www.googleapis.com/youtube/v3/playlistItems",
      {
        part: "snippet",
        maxResults: $total,
        key: $apikey,
        playlistId: $pl_id,
      },
      function (data) {
        $.each(data.items, function (i, item) {
          vidTittle = item.snippet.title;
          vidId = item.snippet.resourceId.videoId;
          vidURL = "https://www.youtube.com/watch?v=" + vidId;
          if ('maxres' in item.snippet.thumbnails) {
            vidThumb = item.snippet.thumbnails.maxres.url;
          } else {
            vidThumb = item.snippet.thumbnails.medium.url;
          }

          output = '<div class="slide-item"><div class="yt-thumb"><a href="'+ vidURL +'"  data-lity><img src="' + vidThumb + 
          '" alt="" /><div class="overlay d-flex align-items-center justify-content-center"><i class="fab fa-youtube"></i></div></a></div></div>';

          $("#yt_res").append(output);
        });

        $("#yt_res").slick({
          lazyLoad: 'ondemand',
          rows: 2,
          dots: true,
          prevArrow: '<i class="fas fa-chevron-left sa-left"></i>',
          nextArrow: '<i class="fas fa-chevron-right sa-right"></i>',
          infinite: false,
          speed: 300,
          slidesToShow: 1,
          slidesToScroll: 1,
          responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll:1,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    rows : 1,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }    
          ]
        });
      }
    );
  }
);
