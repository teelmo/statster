var cumulative_done = false;
$.extend(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    $.ajax({
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
          $('.line').hide();
        },
        400: () => {
          // 400 Bad request
          $('.line').hide();
        }
      },
      type: 'GET',
      url: '/api/tag/get/<?=strtolower($tag_type)?>/cumulative'
    });
  },
  getListeningHistory: type => {
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
    $.ajax({
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
          $.ajax({
            data: {
              json_data: data,
              type: type
            },
            success: data => {
              $('#historyLoader').hide();
              $('#history').html(data).hide();
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type: 'POST',
            url: '/ajax/musicBar'
          });
        },
        204: () => {
          // 204 No Content
          $('#historyLoader').hide();
          $('#history').html(`<?=ERR_NO_RESULTS?>`);
          $('.music_bar').hide();
        },
        400: () => {
          // 400 Bad request
          $('#historyLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
          $('.music_bar').hide();
        }
      },
      type: 'GET',
      url: '/api/tag/get/<?=strtolower($tag_type)?>'
    });
  },
  // Get top albums.
  getTopAlbums: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
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
          $.ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              $('#topAlbumLoader, #topAlbumLoader2').hide();
              $('#topAlbum').html(data);
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          $('#topAlbumLoader, #topAlbumLoader2').hide();
          $('#topAlbum').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  // Get top artists.
  getTopArtists: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
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
          $.ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              $('#topArtistLoader, #topArtistLoader2').hide();
              $('#topArtist').html(data);
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          $('#topArtistLoader, #topArtistLoader2').hide();
          $('#topArtist').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  // Get tag listeners.
  getUsers: (from, where) => {
    $.ajax({
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
          $.ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  // Get tag listenings.
  getListenings: (from, where) => {
    $.ajax({
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
          $.ajax({
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
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
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
  },
  updateBio: () => {
    $.ajax({
      data: {
        tag_id: '<?=$tag_id?>',
        tag_name: '<?=$tag_name?>'
      },
      dataType: 'json',
      type: 'GET',
      url: '/api/<?=strtolower($tag_type)?>/update/biography'
    });
  },
  initTagEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('#biographyMore').click(event => {
      $('#biographyMore').hide();
      $('.summary').hide();
      $('#biographyLess').show();
      $('.content').show();
      event.preventDefault();
    });
    $('#biographyLess').click(event => {
      $('#biographyLess').hide();
      $('.content').hide();
      $('#biographyMore').show();
      $('.summary').show();
      event.preventDefault();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>`);
  view.getListeningHistory('%Y');
  view.getTopAlbums('<?=$top_album_tag?>');
  view.getTopArtists('<?=$top_artist_tag?>');
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
  view.getUsers(from, where);
  view.getListenings(from, where);
  view.initTagEvents();

  var update_bio = parseInt(`<?=($update_bio === true) ? 1 : 0?>`, 10);
  if (update_bio === 1) {
    view.updateBio();
  }
});
