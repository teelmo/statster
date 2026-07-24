Object.assign(view, {
  recentlyListened: () => {
    ajax({
      data: {
        limit: 5
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
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
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
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
    ajax({
      data: {
        limit: 5,
        lower_limit: '1970-00-00'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#topArtistLoader').style.display = 'none';
              document.querySelector('#topArtist').innerHTML = data;
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
    document.querySelector('#toggleRegisterForm').addEventListener('click', () => {
      // Note: jQuery's slideToggle() animated this; plain class toggle drops
      // the slide animation but keeps the same show/hide behavior.
      document.querySelector('#registerForm').classList.toggle('hidden');
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.recentlyListened();
view.topArtist();
view.initWelcomeEvents();
