Object.assign(view, {
  topListeners: () => {
    ajax({
      data: {
        limit: 100,
        sub_group_by: 'album'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32,
              type: 'user'
            },
            success: data => {
              document.querySelector('#topListenerLoader').classList.add('hidden');
              document.querySelector('#topListener').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  getListenings: () => {
    ajax({
      data: {
        limit: 10
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                artist: true,
                calendar: true,
                count: true,
                rank: true,
                spotify: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').classList.add('hidden');
              document.querySelector('#recentlyListened').innerHTML = data;
            },
            type: 'POST',
            url: `<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>`
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.topListeners();
view.getListenings();
