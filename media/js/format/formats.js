Object.assign(view, {
  getFormats: (lower_limit, upper_limit = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    ajax({
      data: {
        limit: 100,
        lower_limit: lower_limit,
        sub_group_by: 'album',
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topListeningFormatTypesLoader, #topListeningFormatTypesLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#topListeningFormatTypes').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListeningFormatTypesLoader').classList.add('hidden');
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListeningFormatTypesLoader').classList.add('hidden');
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/format/get'
    });
  },
  // Get album listenings.
  getListenings: () => {
    ajax({
      data: {
        limit: 10,
        sub_group_by: 'album'
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
view.getFormats('<?=$lower_limit?>');
view.getListenings();
