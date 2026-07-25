var cumulative_done = false;
Object.assign(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    ajax({
      data: {
        tag_id: '<?=$tag_id?>',
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          view.initGraph(data);
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('.line').forEach(el => {
            el.style.display = 'none';
          });
        },
        400: () => {
          // 400 Bad request
          document.querySelectorAll('.line').forEach(el => {
            el.style.display = 'none';
          });
        }
      },
      type: 'GET',
      url: '/api/tag/get/<?=strtolower($tag_type)?>/cumulative'
    });
  },
  getListeningHistory: type => new Promise(resolve => {
    view.initChart();
    var group_by;
    var order_by;
    var select;
    var where;
    if (type === '%w') {
      group_by = 'WEEKDAY(<?=TBL_listening?>.`date`)';
      order_by = 'WEEKDAY(<?=TBL_listening?>.`date`) ASC';
      select = 'WEEKDAY(<?=TBL_listening?>.`date`) as `bar_date`';
      where = "WEEKDAY(<?=TBL_listening?>.`date`) IS NOT NULL AND DATE_FORMAT(<?=TBL_listening?>.`date`, '%d') != '00'";
    } else if (type === '%Y%m') {
      group_by = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}')`;
      order_by = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}') ASC`;
      select = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}') as \`bar_date\``;
      where = "DATE_FORMAT(<?=TBL_listening?>.`date`, '%m') != '00'";
    } else {
      group_by = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}')`;
      order_by = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}') ASC`;
      select = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}') as \`bar_date\``;
      where = `DATE_FORMAT(<?=TBL_listening?>.\`date\`, '${type}') != '00'`;
    }
    ajax({
      data: {
        group_by: group_by,
        limit: 200,
        lower_limit: '1970-00-00',
        order_by: order_by,
        select: select,
        tag_id: '<?=$tag_id?>',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: where
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: type
            },
            success: data => {
              document.querySelector('#historyLoader').style.display = 'none';
              var history = document.querySelector('#history');
              ajaxSetHtml(history, data);
              history.style.display = 'none';
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
              resolve();
            },
            type: 'POST',
            url: '/ajax/musicBar'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#historyLoader').style.display = 'none';
          document.querySelector('#history').innerHTML = `<?=ERR_NO_RESULTS?>`;
          document.querySelector('.music_bar').style.display = 'none';
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#historyLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
          document.querySelector('.music_bar').style.display = 'none';
          resolve();
        }
      },
      type: 'GET',
      url: '/api/tag/get/<?=strtolower($tag_type)?>'
    }).catch(() => resolve());
  }),
  // Get top albums.
  getTopAlbums: interval => new Promise(resolve => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 13,
        lower_limit: lower_limit,
        tag_id: '<?=$tag_id?>',
        tag_type: '<?=$tag_type?>',
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
              resolve();
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
          document.querySelector('#topAlbum').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    }).catch(() => resolve());
  }),
  // Get top artists.
  getTopArtists: interval => new Promise(resolve => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        group_by: '`artist_id`',
        limit: 13,
        lower_limit: lower_limit,
        order_by: '`count` DESC, <?=TBL_artist?>.`artist_name` ASC',
        tag_id: '<?=$tag_id?>',
        tag_type: '<?=$tag_type?>',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              document.querySelectorAll('#topArtistLoader, #topArtistLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topArtist').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topArtistLoader, #topArtistLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topArtist').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    }).catch(() => resolve());
  }),
  // Get tag listeners.
  getUsers: (from, where) => new Promise(resolve => {
    ajax({
      data: {
        from: from,
        limit: 10,
        sub_group_by: 'album',
        where: where
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
              size: 32
            },
            success: data => {
              document.querySelector('#topListenerLoader').style.display = 'none';
              document.querySelector('#topListener').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListenerLoader').style.display = 'none';
          document.querySelector('#topListener').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListenerLoader').style.display = 'none';
          document.querySelector('#topListener').innerHTML = `<?=ERR_BAD_REQUEST?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    }).catch(() => resolve());
  }),
  // Get tag listenings.
  getListenings: (from, where) => new Promise(resolve => {
    ajax({
      data: {
        from: from,
        limit: 10,
        sub_group_by: 'album',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: where
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                artist: true,
                count: true,
                rank: true,
                spotify: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').style.display = 'none';
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#recentlyListenedLoader').style.display = 'none';
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_BAD_REQUEST?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    }).catch(() => resolve());
  }),
  updateBio: () => {
    ajax({
      data: {
        tag_id: '<?=$tag_id?>',
        tag_name: '<?=$tag_name?>'
      },
      dataType: 'json',
      type: 'GET',
      url: '/api/<?=strtolower($tag_type)?>/update/biography'
    });
  },
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished, regardless of whether an
  // individual success callback threw - $.active bookkeeping happens before
  // user callbacks run. Promise.all doesn't have that resilience by default
  // (one rejection fails the whole group), so each promise gets a .catch()
  // here to match the original's fault tolerance.
  initTagEvents: (from, where) => {
    Promise.all([
      view.getListeningHistory('%Y').catch(() => {}),
      view.getTopAlbums('<?=$top_album_tag?>').catch(() => {}),
      view.getTopArtists('<?=$top_artist_tag?>').catch(() => {}),
      view.getUsers(from, where).catch(() => {}),
      view.getListenings(from, where).catch(() => {})
    ]).then(() => {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    document.querySelector('#biographyMore').addEventListener('click', event => {
      document.querySelector('#biographyMore').style.display = 'none';
      document.querySelectorAll('.summary').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelector('#biographyLess').style.display = '';
      document.querySelectorAll('.content').forEach(el => {
        el.style.display = '';
      });
      event.preventDefault();
    });
    document.querySelector('#biographyLess').addEventListener('click', event => {
      document.querySelector('#biographyLess').style.display = 'none';
      document.querySelectorAll('.content').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelector('#biographyMore').style.display = '';
      document.querySelectorAll('.summary').forEach(el => {
        el.style.display = '';
      });
      event.preventDefault();
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>`);
var from;
var where;
switch ('<?=$tag_type?>') {
  case 'genre': {
    from = '(SELECT <?=TBL_genres?>.`genre_id`, <?=TBL_genres?>.`album_id` FROM <?=TBL_genres?> GROUP BY <?=TBL_genres?>.`genre_id`, <?=TBL_genres?>.`album_id`) as <?=TBL_genres?>';
    where = '<?=TBL_genres?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_genres?>.`genre_id` = <?=$tag_id?>';
    break;
  }
  case 'keyword':
    from = '(SELECT <?=TBL_keywords?>.`keyword_id`, <?=TBL_keywords?>.`album_id` FROM <?=TBL_keywords?> GROUP BY <?=TBL_keywords?>.`keyword_id`, <?=TBL_keywords?>.`album_id`) as <?=TBL_keywords?>';
    where = '<?=TBL_keywords?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_keywords?>.`keyword_id` = <?=$tag_id?>';
    break;
  case 'nationality':
    from = '(SELECT <?=TBL_nationalities?>.`nationality_id`, <?=TBL_nationalities?>.`album_id` FROM <?=TBL_nationalities?> GROUP BY <?=TBL_nationalities?>.`nationality_id`, <?=TBL_nationalities?>.`album_id`) as <?=TBL_nationalities?>';
    where = '<?=TBL_nationalities?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_nationalities?>.`nationality_id` = <?=$tag_id?>';
    break;
  case 'year':
    from = '';
    where = '<?=TBL_album?>.`year` = <?=$tag_id?>';
    break;
  default:
    from = '';
    where = '';
    break;
}
view.initTagEvents(from, where);

var update_bio = parseInt(`<?=($update_bio === true) ? 1 : 0?>`, 10);
if (update_bio === 1) {
  view.updateBio();
}
