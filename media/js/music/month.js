$.extend(view, {
  getListeningHistory: (type, lower_limit, upper_limit) => {
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
        lower_limit: lower_limit,
        order_by: order_by,
        select: select,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        sub_group_by: 'album',
        where: where
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              type: type,
              upper_limit: upper_limit
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
      url: '/api/listener/get'
    });
  },
  topAlbum: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 17,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  topArtist: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 17,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/artist/get'
    });
  },
  topListeners: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        sub_group_by: 'album',
        upper_limit: upper_limit
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                calendar: true,
                date: true,
                rank: true
              },
              json_data: data,
              size: 64
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
  topReleases: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: '<?=TBL_album?>.`year` = <?=$year?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              hide: {
                artist: true,
                calendar: true,
                date: true,
                rank: true,
                spotify: true
              },
              size: 64
            },
            success: data => {
              $('#topReleasesLoader').hide();
              $('#topReleases').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topReleasesLoader').hide();
          $('#topReleases').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  topFormats: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 10,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                format_icon: true
              },
              json_data: data
            },
            success: data => {
              $('#topListeningFormatTypesLoader').hide();
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
  topGenre: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
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
              $('#topGenreLoader').hide();
              $('#topGenre').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topGenreLoader').hide();
          $('#topGenre').html(`<?=ERR_NO_RESULTS?>`);
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
  topKeyword: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
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
              $('#topKeywordLoader').hide();
              $('#topKeyword').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topKeywordLoader').hide();
          $('#topKeyword').html(`<?=ERR_NO_RESULTS?>`);
        },
        404: () => {
          // 404 Not found
          alert('404 Not Found');
        }
      },
      type: 'GET',
      url: '/api/keyword/get'
    });
  },
  topNationality: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
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
              $('#topNationalityLoader').hide();
              $('#topNationality').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topNationalityLoader').hide();
          $('#topNationality').html(`<?=ERR_NO_RESULTS?>`);
        },
        404: () => {
          // 404 Not found
          alert('404 Not Found');
        }
      },
      type: 'GET',
      url: '/api/nationality/get/listenings'
    });
  },
  topYear: (lower_limit, upper_limit) => {
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
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
              $('#topYearLoader').hide();
              $('#topYear').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topYearLoader').hide();
          $('#topYear').html(`<?=ERR_NO_RESULTS?>`);
        },
        404: () => {
          // 404 Not found
          alert('404 Not Found');
        }
      },
      type: 'GET',
      url: '/api/year/get'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.initChart();
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topArtist('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topReleases('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.getListeningHistory('%d', '<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topListeners('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topFormats('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topGenre('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topKeyword('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topNationality('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topYear('<?=$lower_limit?>', '<?=$upper_limit?>');
});
