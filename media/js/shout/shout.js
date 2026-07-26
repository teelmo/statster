Object.assign(view, {
  getAlbumShouts: size =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 33,
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
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/album'
      });
    }),
  getArtistShouts: size =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 33,
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
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/artist'
      });
    }),
  getUserShouts: size =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 33,
          type: 'user',
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
          },
          204: () => {
            // 204 No Content
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/user'
      });
    }),
  getShoutUsers: () => {
    ajax({
      data: {
        limit: 20
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
              term: 'shouts'
            },
            success: data => {
              document.querySelector('#shoutersLoader').classList.add('hidden');
              document.querySelector('#shouters').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/users'
    });
  },
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished, regardless of whether an
  // individual success callback threw - $.active bookkeeping happens before
  // user callbacks run. Promise.all doesn't have that resilience by default
  // (one rejection fails the whole group), so each promise gets a .catch()
  // here to match the original's fault tolerance.
  initShoutEvents: size => {
    Promise.all([view.getAlbumShouts(size).catch(() => {}), view.getArtistShouts(size).catch(() => {}), view.getUserShouts(size).catch(() => {})]).then(() => {
      var rows = Array.from(document.querySelectorAll('.shouts tr'));
      rows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
      var shout = document.querySelector('#shout');
      rows.forEach(row => {
        shout.appendChild(row);
      });
      document.querySelector('#shoutLoader').classList.add('hidden');
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
var size = 32;
view.initShoutEvents(size);
view.getShoutUsers(size);
