var cumulative_done = false;
Object.assign(view, {
  getListeningCumulation: () => {
    cumulative_done = true;
    ajax({
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
          document.querySelectorAll('.line').forEach(el => {
            el.classList.add('hidden');
          });
        },
        400: () => {
          // 400 Bad request
          document.querySelectorAll('.line').forEach(el => {
            el.classList.add('hidden');
          });
        }
      },
      type: 'GET',
      url: '/api/listening/get/cumulative'
    });
  },
  getListeningHistory: type =>
    new Promise(resolve => {
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
          // 200 truncated the cumulative monthly-bucket view for long histories (>16y of
          // months); 1200 covers 100 years, comfortably above any real account.
          limit: 1200,
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
            ajax({
              data: {
                json_data: data,
                type: type
              },
              success: data => {
                document.querySelector('#historyLoader').classList.add('hidden');
                var history = document.querySelector('#history');
                ajaxSetHtml(history, data);
                history.classList.add('hidden');
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
            document.querySelectorAll('#historyLoader, .music_bar').forEach(el => {
              el.classList.add('hidden');
            });
            document.querySelector('#history').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelectorAll('#historyLoader, .music_bar').forEach(el => {
              el.classList.add('hidden');
            });
            alert(`<?=ERR_BAD_REQUEST?>`);
            resolve();
          }
        },
        type: 'GET',
        url: '/api/listener/get'
      }).catch(() => resolve());
    }),
  getPopularGenres: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 20,
          lower_limit: `<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>`,
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
                document.querySelector('#popularGenreLoader').classList.add('hidden');
                document.querySelector('#popularGenre').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/tagTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#popularGenreLoader').classList.add('hidden');
            document.querySelector('#popularGenre').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
            resolve();
          }
        },
        type: 'GET',
        url: '/api/genre/get'
      }).catch(() => resolve());
    }),
  getPopularAlbums: interval =>
    new Promise(resolve => {
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
          limit: 20,
          lower_limit: lower_limit,
          username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
        },
        dataType: 'json',
        success: data => {
          ajax({
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
              document.querySelectorAll('#popularAlbumLoader, #popularAlbumLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#popularAlbum').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        type: 'GET',
        url: '/api/album/get'
      }).catch(() => resolve());
    }),
  getSecondChance: () => {
    ajax({
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
          ajax({
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
              document.querySelectorAll('#secondChanceLoader, #secondChanceLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#secondChance').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#secondChanceLoader, #secondChanceLoader2').forEach(el => {
            el.classList.add('hidden');
          });
          document.querySelector('#secondChance').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/secondChance'
    });
  },
  getFromOthers: () => {
    ajax({
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
          ajax({
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
              document.querySelectorAll('#fromOthersLoader, #fromOthersLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#fromOthers').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#fromOthersLoader, #fromOthersLoader2').forEach(el => {
            el.classList.add('hidden');
          });
          document.querySelector('#fromOthers').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/fromOthers'
    });
  },
  getRecentlyFaned: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 8,
          username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                hide: {
                  rank: true
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
  getRecentlyLoved: () =>
    new Promise(resolve => {
      ajax({
        data: {
          limit: 8,
          username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                hide: {
                  rank: true
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
  initMusicEvents: () => {
    Promise.all([view.getListeningHistory('%Y').catch(() => {}), view.getPopularGenres().catch(() => {}), view.getPopularAlbums('<?=$popular_album_music?>').catch(() => {}), view.getRecentlyFaned().catch(() => {}), view.getRecentlyLoved().catch(() => {})]).then(() => {
      var rows = Array.from(document.querySelectorAll('.recently_liked tr'));
      rows.sort((a, b) => app.compareStrings(a.dataset.created, b.dataset.created));
      var recentlyLiked = document.querySelector('#recentlyLiked');
      rows.forEach(row => {
        recentlyLiked.appendChild(row);
      });
      document.querySelector('#recentlyLikedLoader').classList.add('hidden');
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    document.querySelector('#refreshSecondChanceAlbums').addEventListener('click', () => {
      var loader = document.querySelector('#secondChanceLoader2');
      loader.style.display = '';
      loader.classList.remove('hidden');
      view.getSecondChance();
    });
    document.querySelector('#refreshFromOthersAlbums').addEventListener('click', () => {
      var loader = document.querySelector('#fromOthersLoader2');
      loader.style.display = '';
      loader.classList.remove('hidden');
      view.getFromOthers();
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getSecondChance();
view.getFromOthers();
view.initMusicEvents();
