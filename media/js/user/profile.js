var cumulative_done = false;
Object.assign(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    ajax({
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
      url: '/api/listening/get/cumulative'
    });
  },
  // Get listening by year.
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
          ajax({
            data: {
              json_data: data,
              type: type
            },
            success: data => {
              document.querySelector('#historyLoader').style.display = 'none';
              var history = document.querySelector('#history');
              history.innerHTML = data;
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
          var historyLoaderInBar = document.querySelector('.music_bar #historyLoader');
          if (historyLoaderInBar) {
            historyLoaderInBar.style.display = 'none';
          }
          document.querySelector('#history').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          var historyLoaderInBar = document.querySelector('.music_bar #historyLoader');
          if (historyLoaderInBar) {
            historyLoaderInBar.style.display = 'none';
          }
          alert(`<?=ERR_BAD_REQUEST?>`);
          resolve();
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    }).catch(() => resolve());
  }),
  // Get recent listenings.
  getRecentListenings: (isFirst, _callback) => new Promise(resolve => {
    if (isFirst !== true) {
      document.querySelector('#recentlyListenedLoader2').style.display = '';
    }
    ajax({
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
              document.querySelectorAll('#recentlyListenedLoader, #recentlyListenedLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#recentlyListened').innerHTML = data;
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              document.querySelector('#recentlyUpdated').innerHTML = `updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`;
              document.querySelector('#recentlyUpdated').setAttribute('value', today.getTime());
              resolve();
            },
            type: 'POST',
            url: '/ajax/musicTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#recentlyListenedLoader, #recentlyListenedLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          alert('400 Bad Request');
          resolve();
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    }).catch(() => resolve());
  }),
  // Get top albums.
  getTopAlbums: interval => new Promise(resolve => {
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
        limit: 13,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
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
      url: '/api/album/get'
    }).catch(() => resolve());
  }),
  // Get top artists.
  getTopArtists: interval => new Promise(resolve => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const today = new Date();
      today.setDate(new Date().getDate() - parseInt(interval, 10));
      lower_limit = today.toISOString().split('T')[0];
    }
    ajax({
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
      }
    }).catch(() => resolve());
  }),
  getShouts: () => new Promise(resolve => {
    ajax({
      data: {
        username: '<?=$username?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          var shoutTotal = document.querySelector('#shoutTotal');
          if (data[0].count === 1) {
            shoutTotal.innerHTML = `<span class="number">${data[0].count}</span> shout`;
          } else {
            shoutTotal.innerHTML = `<span class="number">${data[0].count}</span> shouts`;
          }
          // Note: jQuery's fadeIn() animated this over 500ms; plain display
          // toggle drops the animation but keeps the same end state.
          shoutTotal.style.display = '';
          ajax({
            data: {
              hide: {
                user: true
              },
              json_data: data,
              size: 64,
              type: 'user'
            },
            success: data => {
              document.querySelector('#userShoutLoader').style.display = 'none';
              document.querySelector('#userShout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#userShoutLoader').style.display = 'none';
          document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/shout/get/user'
    }).catch(() => resolve());
  }),
  getAlbumShouts: () => new Promise(resolve => {
    ajax({
      data: {
        limit: 5,
        username: '<?=$username?>'
      },
      dataType: 'json',
      success: () => {},
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                delete: true,
                user: true
              },
              json_data: data,
              size: 32
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
  getArtistShouts: () => new Promise(resolve => {
    ajax({
      data: {
        limit: 5,
        username: '<?=$username?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                delete: true,
                user: true
              },
              json_data: data,
              size: 32
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
  recentlyFaned: () => new Promise(resolve => {
    ajax({
      data: {
        limit: 5,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              hide: {
                rank: true,
                user: true
              },
              json_data: data
            },
            success: data => {
              document.querySelector('#recentlyFaned').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get'
    }).catch(() => resolve());
  }),
  recentlyLoved: () => new Promise(resolve => {
    ajax({
      data: {
        limit: 5,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              hide: {
                rank: true,
                user: true
              },
              json_data: data
            },
            success: data => {
              document.querySelector('#recentlyLoved').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/love/get'
    }).catch(() => resolve());
  }),
  getTopFormats: interval => new Promise(resolve => {
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
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                format_icon: true
              },
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topFormatLoader, #topFormatLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topFormat').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topFormatLoader, #topFormatLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topFormat').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/format/get'
    }).catch(() => resolve());
  }),
  getTopGenres: interval => new Promise(resolve => {
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
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topGenreLoader, #topGenreLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topGenre').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topGenreLoader, #topGenreLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topGenre').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/genre/get'
    }).catch(() => resolve());
  }),
  getTopKeywords: interval => new Promise(resolve => {
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
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topKeyword').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topKeyword').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/keyword/get'
    }).catch(() => resolve());
  }),
  getTopNationalities: interval => new Promise(resolve => {
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
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topNationality').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topNationality').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/nationality/get/listenings'
    }).catch(() => resolve());
  }),
  getTopYears: interval => new Promise(resolve => {
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
        limit: 5,
        lower_limit: lower_limit,
        username: `<?=(!empty($username)) ? $username: ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topYearLoader, #topYearLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topYear').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topYearLoader, #topYearLoader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topYear').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/year/get'
    }).catch(() => resolve());
  }),
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished, regardless of whether an
  // individual success callback threw - $.active bookkeeping happens before
  // user callbacks run. Promise.all doesn't have that resilience by default
  // (one rejection fails the whole group), so each promise gets a .catch()
  // below to match the original's fault tolerance.
  initProfileEvents: () => {
    Promise.all([
      view.getListeningHistory('%Y').catch(() => {}),
      view.getRecentListenings().catch(() => {}),
      view.getTopAlbums('<?=$top_album_profile?>').catch(() => {}),
      view.getTopArtists('<?=$top_artist_profile?>').catch(() => {}),
      view.getShouts().catch(() => {}),
      view.getAlbumShouts().catch(() => {}),
      view.getArtistShouts().catch(() => {}),
      view.recentlyFaned().catch(() => {}),
      view.recentlyLoved().catch(() => {}),
      view.getTopFormats('<?=$top_listening_format_profile?>').catch(() => {}),
      view.getTopGenres('<?=$top_genre_profile?>').catch(() => {}),
      view.getTopKeywords('<?=$top_keyword_profile?>').catch(() => {}),
      view.getTopNationalities('<?=$top_nationality_profile?>').catch(() => {}),
      view.getTopYears('<?=$top_year_profile?>').catch(() => {})
    ]).then(() => {
      var shoutRows = Array.from(document.querySelectorAll('.shouts tr'));
      if (shoutRows.length === 0) {
        document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
      } else {
        shoutRows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
        var shout = document.querySelector('#shout');
        shoutRows.forEach(row => {
          shout.appendChild(row);
        });
      }
      document.querySelector('#shoutLoader').style.display = 'none';

      var likeRows = Array.from(document.querySelectorAll('.likes tr'));
      if (likeRows.length === 0) {
        document.querySelector('#recentlyLiked').innerHTML = `<?=ERR_NO_RESULTS?>`;
      } else {
        likeRows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
        var recentlyLiked = document.querySelector('#recentlyLiked');
        likeRows.forEach(row => {
          recentlyLiked.appendChild(row);
        });
      }
      document.querySelector('#recentlyLikedLoader').style.display = 'none';
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    document.querySelector('#refreshRecentAlbums').addEventListener('click', () => {
      view.getRecentListenings();
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initProfileEvents();
