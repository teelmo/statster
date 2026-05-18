var cumulative_done = false;
$.extend(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    $.ajax({
      data: {
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
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
      url: '/api/listening/get/cumulative'
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
        order_by: order_by,
        select: select,
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
          $('#historyLoader, .music_bar').hide();
          $('#history').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#historyLoader, .music_bar').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  getPopularGenres: () => {
    $.ajax({
      data: {
        limit: 20,
        lower_limit: `<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>`,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#popularGenreLoader').hide();
              $('#popularGenre').html(data);
            },
            type: 'POST',
            url: '/ajax/tagTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#popularGenreLoader').hide();
          $('#popularGenre').html(`<?=ERR_NO_RESULTS?>`);
        },
        404: () => {
          // 404 Not found
          alert('404 Not Found');
        }
      },
      type: 'GET',
      url: '/api/genre/get'
    });
  },
  getPopularAlbums: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(today.getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 20,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      success: data => {
        $.ajax({
          data: {
            json_data: data,
            hide: {
              calendar: true,
              count: true,
              date: true,
              rank: true
            }
          },
          success: data => {
            $('#popularAlbumLoader, #popularAlbumLoader2').hide();
            $('#popularAlbum').html(data);
          },
          type: 'POST',
          url: '/ajax/sideTable'
        });
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  getSecondChance: () => {
    $.ajax({
      data: {
        having: '`count` < 3',
        limit: 4,
        lower_limit: '1970-00-00',
        order_by: 'RAND()',
        upper_limit: '<?=CUR_YEAR - 1?>-12-31',
        username: `<?=(!empty($_SESSION['username'])) ? $_SESSION['username'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            complete: () => {
              setTimeout(view.getSecondChance, 60 * 10 * 1000);
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
              $('#secondChanceLoader, #secondChanceLoader2').hide();
              $('#secondChance').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#secondChanceLoader, #secondChanceLoader2').hide();
          $('#secondChance').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/secondChance'
    });
  },
  getFromOthers: () => {
    $.ajax({
      data: {
        having: '`count` > 20',
        limit: 4,
        lower_limit: '1970-00-00',
        order_by: 'RAND()',
        where: `<?=TBL_user?>.\`id\` <> <?=(!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            complete: () => {
              setTimeout(view.getFromOthers, 60 * 10 * 1000);
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
              $('#fromOthersLoader, #fromOthersLoader2').hide();
              $('#fromOthers').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#fromOthersLoader, #fromOthersLoader2').hide();
          $('#fromOthers').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/fromOthers'
    });
  },
  getRecentlyFaned: () => {
    $.ajax({
      data: {
        limit: 8,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: {
                rank: true
              },
              json_data: data
            },
            success: data => {
              $('#recentlyFaned').html(data);
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get'
    });
  },
  getRecentlyLoved: () => {
    $.ajax({
      data: {
        limit: 8,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: {
                rank: true
              },
              json_data: data
            },
            success: data => {
              $('#recentlyLoved').html(data);
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/love/get'
    });
  },
  initMusicEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      $('#recentlyLiked').append(
        $('.recently_liked tr')
          .detach()
          .sort((a, b) => app.compareStrings($(a).data('created'), $(b).data('created')))
      );
      $('#recentlyLikedLoader').hide();
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('#refreshSecondChanceAlbums').click(() => {
      $('#secondChanceLoader2').show();
      view.getSecondChance();
    });
    $('#refreshFromOthersAlbums').click(() => {
      $('#fromOthersLoader2').show();
      view.getFromOthers();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getListeningHistory('%Y');
  view.getPopularGenres();
  view.getPopularAlbums('<?=$popular_album_music?>');
  view.getSecondChance();
  view.getFromOthers();
  view.getRecentlyFaned();
  view.getRecentlyLoved();
  view.initMusicEvents();
});
