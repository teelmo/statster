$.extend(view, {
  // Get users.
  getUsers: () => {
    $.ajax({
      data: {},
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data,
              type: 'user'
            },
            success: data => {
              $('#userMosaicLoader').hide();
              $('#userMosaic').html(data);
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          $('#userMosaicLoader').hide();
          $('#userMosaic').html(`<?=ERR_NO_RESULTS?>`);
        },
        404: () => {
          // 404 Not found
          alert('404 Not Found');
        }
      },
      type: 'GET',
      url: '/api/user/get'
    });
  },
  getTopListeners: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 10,
        sub_group_by: 'album',
        lower_limit: lower_limit
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
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
              $('#topListenerLoader, #topListenerLoader2').hide();
              $('#topListener').html(data);
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
  getAlbumShouts: size => {
    $.ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#albumShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/album'
    });
  },
  getArtistShouts: size => {
    $.ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#artistShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/artist'
    });
  },
  getUserShouts: size => {
    $.ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#userShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/user'
    });
  },
  initUserEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      $('#musicShout').append(
        $('.shouts tr')
          .detach()
          .sort((a, b) => app.compareStrings($(a).data('created'), $(b).data('created')))
      );
      $('#musicShoutLoader').hide();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>`);
  view.getUsers();
  var size = 32;
  view.getAlbumShouts(size);
  view.getArtistShouts(size);
  view.getUserShouts(size);
  view.getTopListeners('<?=$top_listener_user?>');
  view.initUserEvents();
});
