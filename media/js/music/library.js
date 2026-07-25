Object.assign(view, {
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
    where += ' AND MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>';
    ajax({
      data: {
        group_by: group_by,
        // 200 truncated the cumulative monthly-bucket view for long histories (>16y of
        // months); 1200 covers 100 years, comfortably above any real account.
        limit: 1200,
        lower_limit: lower_limit,
        order_by: order_by,
        select: select,
        sub_group_by: 'album',
        upper_limit: upper_limit,
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
              type: type,
              upper_limit: upper_limit
            },
            success: data => {
              document.querySelector('#historyLoader').style.display = 'none';
              var history = document.querySelector('#history');
              ajaxSetHtml(history, data);
              history.style.display = 'none';
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
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
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#historyLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
          document.querySelector('.music_bar').style.display = 'none';
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  topAlbum: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 17,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              document.querySelector('#topAlbumLoader').style.display = 'none';
              document.querySelector('#topAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topAlbumLoader').style.display = 'none';
          document.querySelector('#topAlbum').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  topArtist: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 17,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              document.querySelector('#topArtistLoader').style.display = 'none';
              document.querySelector('#topArtist').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topArtistLoader').style.display = 'none';
          document.querySelector('#topArtist').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/artist/get'
    });
  },
  topListeners: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        sub_group_by: 'album',
        upper_limit: upper_limit,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
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
              document.querySelector('#topListenerLoader').style.display = 'none';
              document.querySelector('#topListener').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListenerLoader').style.display = 'none';
          document.querySelector('#topListener').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListenerLoader').style.display = 'none';
          document.querySelector('#topListener').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  topReleases: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_album?>.`created`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_album?>.`created`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_album?>.`created`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
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
              document.querySelector('#topReleasesLoader').style.display = 'none';
              document.querySelector('#topReleases').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topReleasesLoader').style.display = 'none';
          document.querySelector('#topReleases').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  topFormats: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 10,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
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
              document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
              document.querySelector('#topListeningFormatTypes').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/format/get'
    });
  },
  topGenre: (lower_limit, upper_limit) => {
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#topGenreLoader').style.display = 'none';
              document.querySelector('#topGenre').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topGenreLoader').style.display = 'none';
          document.querySelector('#topGenre').innerHTML = `<?=ERR_NO_RESULTS?>`;
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
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#topKeywordLoader').style.display = 'none';
              document.querySelector('#topKeyword').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topKeywordLoader').style.display = 'none';
          document.querySelector('#topKeyword').innerHTML = `<?=ERR_NO_RESULTS?>`;
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
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#topNationalityLoader').style.display = 'none';
              document.querySelector('#topNationality').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topNationalityLoader').style.display = 'none';
          document.querySelector('#topNationality').innerHTML = `<?=ERR_NO_RESULTS?>`;
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
    ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: 'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?> AND WEEKDAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($weekday)?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#topYearLoader').style.display = 'none';
              document.querySelector('#topYear').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topYearLoader').style.display = 'none';
          document.querySelector('#topYear').innerHTML = `<?=ERR_NO_RESULTS?>`;
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

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initChart();
view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topArtist('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topReleases('<?=$lower_limit?>', '<?=$upper_limit?>');
view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>');
view.topListeners('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topFormats('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topGenre('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topKeyword('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topNationality('<?=$lower_limit?>', '<?=$upper_limit?>');
view.topYear('<?=$lower_limit?>', '<?=$upper_limit?>');
