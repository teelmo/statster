var cumulative_done = false;
$.extend(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    $.ajax({
      data: {
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
      url: '/api/listening/get/cumulative'
    });
  },
  // Get listening by year.
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
        username: `<?=(!empty($username)) ? $username: ''?>`,
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
          $('#historyLoader', '.music_bar').hide();
          $('#history').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#historyLoader', '.music_bar').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  // Get recent listenings.
  getRecentListenings: (isFirst, _callback) => {
    if (isFirst !== true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      data: {
        sub_group_by: 'album',
        limit: 12,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          const today = new Date();
          $.ajax({
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
              $('#recentlyListenedLoader, #recentlyListenedLoader2').hide();
              $('#recentlyListened').html(data);
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              $('#recentlyUpdated').html(`updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`);
              $('#recentlyUpdated').attr('value', today.getTime());
            },
            type: 'POST',
            url: '/ajax/musicTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#recentlyListenedLoader, #recentlyListenedLoader2').hide();
          $('#recentlyListened').html(`<?=ERR_NO_RESULTS?>`);
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
      today.setDate(today.getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 13,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
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
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '/api/artist/get',
      data: {
        limit: 13,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
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
      }
    });
  },
  getShouts: () => {
    $.ajax({
      data: {
        username: '<?=$username?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          if (data[0].count === 1) {
            $('#shoutTotal').html(`<span class="number">${data[0].count}</span> shout`).fadeIn(500);
          } else {
            $('#shoutTotal').html(`<span class="number">${data[0].count}</span> shouts`).fadeIn(500);
          }
          $.ajax({
            data: {
              hide: {
                user: true
              },
              json_data: data,
              size: 64,
              type: 'user'
            },
            success: data => {
              $('#userShoutLoader').hide();
              $('#userShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#userShoutLoader').hide();
          $('#shout').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/shout/get/user'
    });
  },
  getAlbumShouts: () => {
    $.ajax({
      data: {
        limit: 5,
        username: '<?=$username?>'
      },
      dataType: 'json',
      success: () => {},
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                delete: true,
                user: true
              },
              json_data: data,
              size: 32
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
  getArtistShouts: () => {
    $.ajax({
      data: {
        limit: 5,
        username: '<?=$username?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                delete: true,
                user: true
              },
              json_data: data,
              size: 32
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
  recentlyFaned: () => {
    $.ajax({
      data: {
        limit: 5,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: {
                rank: true,
                user: true
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
  recentlyLoved: () => {
    $.ajax({
      data: {
        limit: 5,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: {
                rank: true,
                user: true
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
  getTopFormats: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
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
              $('#topFormatLoader, #topFormatLoader2').hide();
              $('#topFormat').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topFormatLoader, #topFormatLoader2').hide();
          $('#topFormat').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/format/get'
    });
  },
  getTopGenres: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topGenreLoader, #topGenreLoader2').hide();
              $('#topGenre').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topGenreLoader, #topGenreLoader2').hide();
          $('#topGenre').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/genre/get'
    });
  },
  getTopKeywords: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topKeywordLoader, #topKeywordLoader2').hide();
              $('#topKeyword').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topKeywordLoader, #topKeywordLoader2').hide();
          $('#topKeyword').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/keyword/get'
    });
  },
  getTopNationalities: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topNationalityLoader, #topNationalityLoader2').hide();
              $('#topNationality').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topNationalityLoader, #topNationalityLoader2').hide();
          $('#topNationality').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/nationality/get/listenings'
    });
  },
  getTopYears: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topYearLoader, #topYearLoader2').hide();
              $('#topYear').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topYearLoader, #topYearLoader2').hide();
          $('#topYear').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/year/get'
    });
  },
  initProfileEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      if ($('.shouts tr').length === 0) {
        $('#shout').html(`<?=ERR_NO_RESULTS?>`);
      } else {
        $('#shout').append(
          $('.shouts tr')
            .detach()
            .sort((a, b) => app.compareStrings($(a).data('created'), $(b).data('created')))
        );
      }
      $('#shoutLoader').hide();

      if ($('.likes tr').length === 0) {
        $('#recentlyLiked').html(`<?=ERR_NO_RESULTS?>`);
      } else {
        $('#recentlyLiked').append(
          $('.likes tr')
            .detach()
            .sort((a, b) => app.compareStrings($(a).data('created'), $(b).data('created')))
        );
      }
      $('#recentlyLikedLoader').hide();
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('#refreshRecentAlbums').click(() => {
      view.getRecentListenings();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getListeningHistory('%Y');
  view.getRecentListenings();
  view.getTopAlbums('<?=$top_album_profile?>');
  view.getTopArtists('<?=$top_artist_profile?>');
  view.getShouts();
  view.getAlbumShouts();
  view.getArtistShouts();
  view.recentlyFaned();
  view.recentlyLoved();
  view.getTopFormats('<?=$top_listening_format_profile?>');
  view.getTopGenres('<?=$top_genre_profile?>');
  view.getTopKeywords('<?=$top_keyword_profile?>');
  view.getTopNationalities('<?=$top_nationality_profile?>');
  view.getTopYears('<?=$top_year_profile?>');
  view.initProfileEvents();
});
