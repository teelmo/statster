var cumulative_done = false;
Object.assign(view, {
  // Get artist fan.
  getFan: user_id =>
    new Promise(resolve => {
      if (user_id === undefined) {
        document.querySelector('#fanLoader').classList.add('hidden');
        resolve();
        return;
      }
      ajax({
        complete: () => {
          document.querySelector('#fanLoader').classList.add('hidden');
          resolve();
        },
        data: {
          user_id: user_id
        },
        dataType: 'json',
        statusCode: {
          200: () => {
            // 200 OK
            document.querySelector('#fan').classList.add('fan_del');
          },
          204: () => {
            // 204 No Content
            document.querySelector('#fan').classList.add('fan_add');
          },
          400: () => {
            // 400 Bad request
            alert(`<?=ERR_BAD_REQUEST?>`);
          }
        },
        type: 'GET',
        url: '/api/fan/get/<?=$artist_id?>'
      }).catch(() => resolve());
    }),
  // Get artist fans.
  getFans: () =>
    new Promise(resolve => {
      ajax({
        data: {},
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                hide: {},
                json_data: data
              },
              success: data => {
                document.querySelector('#artistFanLoader').classList.add('hidden');
                document.querySelector('#artistFan').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/likeList'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#artistFanLoader').classList.add('hidden');
            document.querySelector('#artistFan').innerHTML = '';
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#artistFanLoader').classList.add('hidden');
            alert(`<?=ERR_BAD_REQUEST?>`);
            resolve();
          }
        },
        type: 'GET',
        url: '/api/fan/get/<?=$artist_id?>'
      }).catch(() => resolve());
    }),
  // Get artist tags.
  getTags: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_id: parseInt(`<?=$artist_id?>`, 10),
          limit: 9
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                delete: false,
                json_data: data,
                logged_in: `<?=$logged_in?>`
              },
              success: data => {
                document.querySelector('#tagsLoader').classList.add('hidden');
                document.querySelector('#tags').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/tagList'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#tagsLoader').classList.add('hidden');
            document.querySelector('#tags').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#tagsLoader').classList.add('hidden');
            document.querySelector('#tags').innerHTML = `<?=ERR_BAD_REQUEST?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/tag/get/artist'
      }).catch(() => resolve());
    }),
  getListeningCumulation: () => {
    cumulative_done = true;
    ajax({
      data: {
        artist_name: '<?=$artist_name?>',
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
          artist_name: '<?=$artist_name?>',
          group_by: group_by,
          // 200 truncated the cumulative monthly-bucket view for long histories (>16y of
          // months); 1200 covers 100 years, comfortably above any real account.
          limit: 1200,
          order_by: order_by,
          select: select,
          sub_group_by: '',
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
            document.querySelector('#historyLoader').classList.add('hidden');
            document.querySelector('#history').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#historyLoader').classList.add('hidden');
            alert(`<?=ERR_BAD_REQUEST?>`);
            resolve();
          }
        },
        type: 'GET',
        url: '/api/listener/get'
      }).catch(() => resolve());
    }),
  getShouts: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_name: '<?=$artist_name?>'
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
                document.querySelector('#shoutLoader').classList.add('hidden');
                document.querySelector('#shout').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/shoutTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#shoutLoader').classList.add('hidden');
            document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#shoutLoader').classList.add('hidden');
            alert(`<?=ERR_BAD_REQUEST?>`);
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/artist'
      }).catch(() => resolve());
    }),
  // Get artist listeners.
  getUsers: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_name: '<?=$artist_name?>',
          sub_group_by: '',
          limit: 6
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
                document.querySelector('#topListenerLoader').classList.add('hidden');
                document.querySelector('#topListener').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/userTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#topListenerLoader').classList.add('hidden');
            document.querySelector('#topListener').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#topListenerLoader').classList.add('hidden');
            document.querySelector('#topListener').innerHTML = `<?=ERR_BAD_REQUEST?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/listener/get'
      }).catch(() => resolve());
    }),
  // Get artist listenings.
  getListenings: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_name: '<?=$artist_name?>',
          limit: 6,
          username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
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
                strlenght: 30,
                size: 32
              },
              success: data => {
                document.querySelector('#recentlyListenedLoader').classList.add('hidden');
                document.querySelector('#recentlyListened').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/sideTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#recentlyListenedLoader').classList.add('hidden');
            document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#recentlyListenedLoader').classList.add('hidden');
            document.querySelector('#recentlyListened').innerHTML = `<?=ERR_BAD_REQUEST?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/listening/get'
      }).catch(() => resolve());
    }),
  getFormats: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_name: '<?=$artist_name?>',
          limit: 5,
          username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
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
                document.querySelector('#topListeningFormatTypesLoader').classList.add('hidden');
                document.querySelector('#topListeningFormatTypes').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/columnTable'
            });
          },
          204: () => {
            // 204 No Content
            document.querySelector('#topListeningFormatTypesLoader').classList.add('hidden');
            document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          },
          400: () => {
            // 400 Bad request
            document.querySelector('#topListeningFormatTypesLoader').classList.add('hidden');
            document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_BAD_REQUEST?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/format/get'
      }).catch(() => resolve());
    }),
  getAlbumShouts: () =>
    new Promise(resolve => {
      ajax({
        data: {
          artist_name: '<?=$artist_name?>',
          limit: 5
        },
        dataType: 'json',
        statusCode: {
          200: data => {
            // 200 OK
            ajax({
              data: {
                hide: {
                  user: true
                },
                json_data: data,
                size: 32
              },
              success: data => {
                document.querySelector('#albumShoutLoader').classList.add('hidden');
                document.querySelector('#albumShout').innerHTML = data;
                resolve();
              },
              type: 'POST',
              url: '/ajax/shoutTable'
            });
          },
          204: () => {
            document.querySelector('#albumShoutLoader').classList.add('hidden');
            document.querySelector('#albumShout').innerHTML = `<?=ERR_NO_RESULTS?>`;
            resolve();
          }
        },
        type: 'GET',
        url: '/api/shout/get/album'
      }).catch(() => resolve());
    }),
  updateArtistBio: () => {
    ajax({
      data: {
        artist_id: parseInt(`<?=$artist_id?>`, 10),
        artist_name: '<?=$artist_name?>'
      },
      dataType: 'json',
      type: 'GET',
      url: '/api/artist/update/biography'
    });
  },
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished, regardless of whether an
  // individual success callback threw - $.active bookkeeping happens before
  // user callbacks run. Promise.all doesn't have that resilience by default
  // (one rejection fails the whole group), so each promise gets a .catch()
  // here to match the original's fault tolerance.
  initArtistEvents: () => {
    Promise.all([
      view.getFan(parseInt(`<?=$this->session->userdata('user_id')?>`, 10)).catch(() => {}),
      view.getFans().catch(() => {}),
      view.getTags().catch(() => {}),
      view.getListeningHistory('%Y').catch(() => {}),
      view.getShouts().catch(() => {}),
      view.getUsers().catch(() => {}),
      view.getListenings().catch(() => {}),
      view.getFormats().catch(() => {}),
      view.getAlbumShouts().catch(() => {})
    ]).then(() => {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#fan');
      if (!target) {
        return;
      }
      document.querySelectorAll('.like_msg').forEach(el => {
        el.innerHTML = '';
      });
      if (target.classList.contains('fan_add')) {
        ajax({
          data: {},
          statusCode: {
            201: () => {
              // 201 Created
              var fan = document.querySelector('#fan');
              fan.classList.remove('fan_add');
              fan.classList.add('fan_del');
              var msg = fan.querySelector('.like_msg');
              msg.innerHTML = "You're a fan!";
              msg.style.display = '';
              setTimeout(() => {
                // Note: jQuery's fadeOut() animated this over 1s; plain hide
                // drops the animation but keeps the same end state.
                document.querySelectorAll('.like_msg').forEach(el => {
                  el.classList.add('hidden');
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getFans();
            },
            400: () => {
              // 400 Bad request
              alert(`<?=ERR_BAD_REQUEST?>`);
            },
            401: () => {
              alert('401 Unauthorized');
            },
            404: () => {
              alert('404 Not Found');
            }
          },
          type: 'POST',
          url: `/api/fan/add/${parseInt(`<?=$artist_id?>`, 10)}`
        });
      }
      if (target.classList.contains('fan_del')) {
        ajax({
          data: {},
          statusCode: {
            204: () => {
              // 204 No Content
              var fan = document.querySelector('#fan');
              fan.classList.remove('fan_del');
              fan.classList.add('fan_add');
              var msg = fan.querySelector('.like_msg');
              msg.innerHTML = 'Unfaned.';
              msg.style.display = '';
              setTimeout(() => {
                // Note: jQuery's fadeOut() animated this over 1s; plain hide
                // drops the animation but keeps the same end state.
                document.querySelectorAll('.like_msg').forEach(el => {
                  el.classList.add('hidden');
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getFans();
            },
            400: () => {
              // 400 Bad request
              alert(`<?=ERR_BAD_REQUEST?>`);
            },
            401: () => {
              alert('401 Unauthorized');
            },
            404: () => {
              alert('404 Not Found');
            }
          },
          type: 'POST',
          url: `/api/fan/delete/${parseInt(`<?=$artist_id?>`, 10)}`
        });
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#submitTags');
      if (!target) {
        return;
      }
      Array.from(document.querySelector('#tagAdd select').selectedOptions)
        .map(o => o.value)
        .forEach(el => {
          var tag = el.split(':');
          ajax({
            async: false,
            data: {
              artist_id: parseInt(`<?=$artist_id?>`, 10),
              tag_id: tag[1],
              type: 'artist'
            },
            statusCode: {
              201: () => {
                // 201 Created
              },
              400: () => {
                // 400 Bad request
                alert(`<?=ERR_BAD_REQUEST?>`);
              },
              401: () => {
                alert('401 Unauthorized');
              },
              404: () => {
                alert('404 Not Found');
              }
            },
            type: 'POST',
            url: `/api/tag/add/${tag[0]}`
          });
        });
      document.querySelector('#tagAdd select').searchableSelectReset();
      view.getTags();
      document.querySelector('#tagAdd').classList.add('hidden');
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>`);
view.initArtistEvents();

var update_bio = parseInt(`<?=($update_bio === true) ? 1 : 0?>`, 10);
if (update_bio === 1) {
  view.updateArtistBio();
}
