Object.assign(view, {
  // Get users.
  getUsers: () => {
    ajax({
      data: {},
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'user'
            },
            success: data => {
              document.querySelector('#userMosaicLoader').style.display = 'none';
              document.querySelector('#userMosaic').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#userMosaicLoader').style.display = 'none';
          document.querySelector('#userMosaic').innerHTML = `<?=ERR_NO_RESULTS?>`;
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
    ajax({
      data: {
        limit: 10,
        sub_group_by: 'album',
        lower_limit: lower_limit
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
              document.querySelectorAll('#topListenerLoader, #topListenerLoader2').forEach(el => {
                el.style.display = 'none';
              });
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
  getAlbumShouts: size => new Promise(resolve => {
    ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              document.querySelector('#albumShout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/album'
    }).catch(() => resolve());
  }),
  getArtistShouts: size => new Promise(resolve => {
    ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              document.querySelector('#artistShout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/artist'
    }).catch(() => resolve());
  }),
  getUserShouts: size => new Promise(resolve => {
    ajax({
      data: {
        limit: 3,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              document.querySelector('#userShout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/user'
    }).catch(() => resolve());
  }),
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished; this waits specifically for the
  // three shout fetches that feed the merge below, which is what the
  // original intent actually was.
  initUserEvents: size => {
    Promise.all([
      view.getAlbumShouts(size),
      view.getArtistShouts(size),
      view.getUserShouts(size)
    ]).then(() => {
      var rows = Array.from(document.querySelectorAll('.shouts tr'));
      rows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
      var musicShout = document.querySelector('#musicShout');
      rows.forEach(row => {
        musicShout.appendChild(row);
      });
      document.querySelector('#musicShoutLoader').style.display = 'none';
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getUsers();
var size = 32;
view.initUserEvents(size);
view.getTopListeners('<?=$top_listener_user?>');
