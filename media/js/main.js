Object.assign(view, {
  // Get recent listenings.
  getRecentListenings: callback => {
    ajax({
      complete: () => {
        if (callback !== undefined) {
          callback();
        }
      },
      data: {
        sub_group_by: 'album',
        limit: 12,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          const today = new Date();
          ajax({
            data: {
              cur_date: `${today.getFullYear()}-${(`0${today.getMonth() + 1}`).slice(-2)}-${(`0${today.getDate()}`).slice(-2)}`,
              hide: {
                del: true
              },
              json_data: data,
              strlenght: 50,
              time: Math.floor((today.getTime() - today.getTimezoneOffset() * 60000) / 1000)
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader2').style.display = 'none';
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              document.querySelector('#recentlyUpdated').innerHTML = `updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`;
              document.querySelector('#recentlyUpdated').setAttribute('value', today.getTime());
            },
            type: 'POST',
            url: '/ajax/musicTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').style.display = 'none';
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          alert('400 Bad Request');
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  // Get top albums.
  getTopAlbums: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 13,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              document.querySelectorAll('#topAlbumLoader, #topAlbumLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topAlbumLoader, #topAlbumLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topAlbum').innerHTML = '<?=ERR_NO_RESULTS?>';
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  // Get top artists.
  getTopArtists: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(today.getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 9,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topArtistLoader, #topArtistLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topArtist').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topArtistLoader, #topArtistLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topArtist').innerHTML = '<?=ERR_NO_RESULTS?>';
        }
      },
      type: 'GET',
      url: '/api/artist/get'
    });
  },
  // Get recommented top albums.
  getRecommentedTopAlbum: () => {
    ajax({
      data: {
        limit: 15,
        lower_limit: `<?=date('Y-m-d', time() - (90 * 24 * 60 * 60))?>`,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            complete: () => {
              // setdefault_interval(view.recommentedTopAlbum, 60 * 10 * 1000);
            },
            data: {
              json_data: data,
              hide: {
                calendar: true,
                count: true,
                date: true,
                rank: true
              },
              limit: 4
            },
            success: data => {
              document.querySelectorAll('#recommentedTopAlbumLoader, #recommentedTopAlbumLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#recommentedTopAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#recommentedTopAlbumLoader, #recommentedTopAlbumLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#recommentedTopAlbum').innerHTML = '<?=ERR_NO_RESULTS?>';
        }
      },
      type: 'GET',
      url: '/api/recommentedTopAlbum'
    });
  },
  // Get recommented new albums.
  getRecommentedNewAlbum: () => {
    ajax({
      data: {
        limit: 15,
        order_by: 'album.year DESC, album.created DESC',
        lower_limit: `<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>`,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            complete: () => {
              // setdefault_interval(view.recommentedNewAlbum, 60 * 10 * 1000);
            },
            data: {
              json_data: data,
              hide: {
                calendar: true,
                count: true,
                date: true,
                rank: true
              },
              limit: 4
            },
            success: data => {
              document.querySelectorAll('#recommentedNewAlbumLoader, #recommentedNewAlbumLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#recommentedNewAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#recommentedNewAlbumLoader, #recommentedNewAlbumLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#recommentedNewAlbum').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/recommentedNewAlbum'
    });
  },
  initMainEvents: () => {
    // Recently listened hover keypress.
    var recentlyListenedHover = () => {
      var currentTime = new Date();
      if (currentTime.getTime() - document.querySelector('#recentlyUpdated').getAttribute('value') > 60 * 2 * 1000 && ajax.pending < 1) {
        view.getRecentListenings();
      }
    };
    document.querySelector('#recentlyListened').addEventListener('mouseenter', recentlyListenedHover);
    document.querySelector('#recentlyListened').addEventListener('mouseleave', recentlyListenedHover);
    document.querySelector('#refreshRecentAlbums').addEventListener('click', () => {
      document.querySelector('#recentlyListenedLoader2').style.display = '';
      view.getRecentListenings();
    });
    document.querySelector('#refreshHotAlbums').addEventListener('click', () => {
      document.querySelector('#recommentedTopAlbumLoader2').style.display = '';
      view.getRecommentedTopAlbum();
    });
    document.querySelector('#refreshNewAlbums').addEventListener('click', () => {
      document.querySelector('#recommentedNewAlbumLoader2').style.display = '';
      view.getRecommentedNewAlbum();
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getRecentListenings(() => {
  view.getTopAlbums('<?=$top_album_main?>');
});
view.getTopArtists('<?=$top_artist_main?>');
view.getRecommentedTopAlbum();
view.getRecommentedNewAlbum();
view.initMainEvents();
