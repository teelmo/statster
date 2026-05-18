$.extend(view, {
  recentlyListened: () => {
    $.ajax({
      data: {
        limit: 5
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data,
              size: 32,
              hide: {
                artist: true,
                calendar: true,
                count: true,
                rank: true,
                spotify: true
              }
            },
            success: data => {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  topArtist: () => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: '1970-00-00'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        }
      },
      type: 'GET',
      url: '/api/artist/get'
    });
  },
  initWelcomeEvents: () => {
    $('#toggleRegisterForm').click(() => {
      $('#registerForm').slideToggle();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.recentlyListened();
  view.topArtist();
  view.initWelcomeEvents();
});
