$.extend(view, {
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
    $.ajax({
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
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topListeningFormatTypesLoader, #topListeningFormatTypesLoader2').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/format/get'
    });
  },
  // Get album listenings.
  getListenings: () => {
    $.ajax({
      data: {
        limit: 10,
        sub_group_by: 'album'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
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
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type: 'POST',
            url: `<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>`
          });
        },
        204: () => {
          // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getFormats('<?=$lower_limit?>');
  view.getListenings();
});
