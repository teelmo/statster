var cumulative_done = false;
Object.assign(view, {
  // Get album love.
  getLove: user_id => new Promise(resolve => {
    if (user_id === undefined) {
      document.querySelector('#loveLoader').style.display = 'none';
      resolve();
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#loveLoader').style.display = 'none';
        resolve();
      },
      data: {
        user_id: user_id
      },
      dataType: 'json',
      statusCode: {
        200: () => {
          // 200 OK
          document.querySelector('#love').classList.add('love_del');
        },
        204: () => {
          // 204 No Content
          document.querySelector('#love').classList.add('love_add');
        },
        400: () => {
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    }).catch(() => resolve());
  }),
  // Get album loves.
  getLoves: () => new Promise(resolve => {
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
              document.querySelector('#albumLoveLoader').style.display = 'none';
              document.querySelector('#albumLove').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#albumLoveLoader').style.display = 'none';
          document.querySelector('#albumLove').innerHTML = '';
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#albumLoveLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
          resolve();
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    }).catch(() => resolve());
  }),
  // Get album tags.
  getTags: () => new Promise(resolve => {
    ajax({
      data: {
        album_id: parseInt(`<?=$album_id?>`, 10),
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              delete: true,
              logged_in: '<?=$logged_in?>'
            },
            success: data => {
              document.querySelector('#tagsLoader').style.display = 'none';
              document.querySelector('#tags').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/tagList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#tagsLoader').style.display = 'none';
          document.querySelector('#tags').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#tagsLoader').style.display = 'none';
          document.querySelector('#tags').innerHTML = `<?=ERR_BAD_REQUEST?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/tag/get/album'
    }).catch(() => resolve());
  }),
  getListeningCumulation: () => {
    cumulative_done = true;
    ajax({
      data: {
        album_name: '<?=$album_name?>',
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
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
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
          document.querySelector('#historyLoader').style.display = 'none';
          document.querySelector('#history').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#historyLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
          resolve();
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    }).catch(() => resolve());
  }),
  getShouts: () => new Promise(resolve => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        sub_group_by: 'album'
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
              document.querySelector('#shoutLoader').style.display = 'none';
              document.querySelector('#shout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#shoutLoader').style.display = 'none';
          document.querySelector('#shout').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#shoutLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
          resolve();
        }
      },
      type: 'GET',
      url: '/api/shout/get/album'
    }).catch(() => resolve());
  }),
  // Get album listeners.
  getUsers: () => new Promise(resolve => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 6,
        sub_group_by: 'album'
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
  // Get album listenings.
  getListenings: () => new Promise(resolve => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 6,
        sub_group_by: 'album',
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
              size: 32
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/userTable'
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
  getFormats: () => new Promise(resolve => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 5,
        sub_group_by: 'album',
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
              document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
              document.querySelector('#topListeningFormatTypes').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListeningFormatTypesLoader').style.display = 'none';
          document.querySelector('#topListeningFormatTypes').innerHTML = `<?=ERR_BAD_REQUEST?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/format/get'
    }).catch(() => resolve());
  }),
  getArtistShouts: () => new Promise(resolve => {
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
              document.querySelector('#artistShoutLoader').style.display = 'none';
              document.querySelector('#artistShout').innerHTML = data;
              resolve();
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          document.querySelector('#artistShoutLoader').style.display = 'none';
          document.querySelector('#artistShout').innerHTML = `<?=ERR_NO_RESULTS?>`;
          resolve();
        }
      },
      type: 'GET',
      url: '/api/shout/get/artist'
    }).catch(() => resolve());
  }),
  updateAlbumBio: () => {
    ajax({
      data: {
        album_id: parseInt(`<?=$album_id?>`, 10),
        album_name: '<?=$album_name?>',
        artist_id: parseInt(`<?=$artist_id?>`, 10),
        artist_name: '<?=$artist_name?>'
      },
      dataType: 'json',
      type: 'GET',
      url: '/api/album/update/biography'
    });
  },
  // Note: jQuery's $(document).one('ajaxStop', fn) fired once ANY in-flight
  // request anywhere on the page finished, regardless of whether an
  // individual success callback threw - $.active bookkeeping happens before
  // user callbacks run. Promise.all doesn't have that resilience by default
  // (one rejection fails the whole group), so each promise gets a .catch()
  // here to match the original's fault tolerance.
  initAlbumEvents: () => {
    Promise.all([
      view.getLove(parseInt(`<?=$this->session->userdata('user_id')?>`, 10)).catch(() => {}),
      view.getLoves().catch(() => {}),
      view.getTags().catch(() => {}),
      view.getListeningHistory('%Y').catch(() => {}),
      view.getShouts().catch(() => {}),
      view.getUsers().catch(() => {}),
      view.getListenings().catch(() => {}),
      view.getFormats().catch(() => {}),
      view.getArtistShouts().catch(() => {})
    ]).then(() => {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#love');
      if (!target) {
        return;
      }
      document.querySelectorAll('.like_msg').forEach(el => {
        el.innerHTML = '';
      });
      if (target.classList.contains('love_add')) {
        ajax({
          data: {},
          statusCode: {
            201: () => {
              // 201 Created
              var love = document.querySelector('#love');
              love.classList.remove('love_add');
              love.classList.add('love_del');
              var msg = love.querySelector('.like_msg');
              msg.innerHTML = "You're in love!";
              msg.style.display = '';
              setTimeout(() => {
                // Note: jQuery's fadeOut() animated this over 1s; plain hide
                // drops the animation but keeps the same end state.
                document.querySelectorAll('.like_msg').forEach(el => {
                  el.style.display = 'none';
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getLoves();
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
          url: `/api/love/add/${parseInt(`<?=$album_id?>`, 10)}`
        });
      }
      if (target.classList.contains('love_del')) {
        ajax({
          data: {},
          statusCode: {
            204: () => {
              // 204 No Content
              var love = document.querySelector('#love');
              love.classList.remove('love_del');
              love.classList.add('love_add');
              var msg = love.querySelector('.like_msg');
              msg.innerHTML = 'Unloved.';
              msg.style.display = '';
              setTimeout(() => {
                // Note: jQuery's fadeOut() animated this over 1s; plain hide
                // drops the animation but keeps the same end state.
                document.querySelectorAll('.like_msg').forEach(el => {
                  el.style.display = 'none';
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getLoves();
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
          url: `/api/love/delete/${parseInt(`<?=$album_id?>`, 10)}`
        });
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#submitTags');
      if (!target) {
        return;
      }
      $('.chosen-select').val().forEach(el => {
        var tag = el.split(':');
        ajax({
          async: false,
          data: {
            album_id: parseInt(`<?=$album_id?>`, 10),
            tag_id: tag[1],
            type: 'album'
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
      $('.chosen-select option').removeAttr('selected');
      $('#tagAdd select').trigger('chosen:updated');
      view.getTags();
      document.querySelector('#tagAdd').style.display = 'none';
    });
    document.querySelector('html').addEventListener('mouseover', event => {
      var target = event.target.closest('.tag');
      if (!target) {
        return;
      }
      var remove = target.querySelector('.remove');
      if (remove) {
        remove.classList.remove('hidden');
      }
    });
    document.querySelector('html').addEventListener('mouseout', event => {
      var target = event.target.closest('.tag');
      if (!target) {
        return;
      }
      var remove = target.querySelector('.remove');
      if (remove) {
        remove.classList.add('hidden');
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('.remove');
      if (!target) {
        return;
      }
      var type = target.dataset.tagType;
      ajax({
        data: {
          album_id: parseInt(`<?=$album_id?>`, 10),
          tag_id: parseInt(target.dataset.tagId, 10)
        },
        statusCode: {
          200: () => {
            view.getTags();
          }
        },
        url: `/api/${type}/delete`,
        type: 'POST'
      });
    });
    document.querySelectorAll('.quick_add_listening .subnav li').forEach(li => {
      li.addEventListener('click', function (event) {
        var format_value = this.dataset.value;
        var album_id = parseInt(`<?=$album_id?>`, 10);
        var artist_ids = parseInt(`<?=$artist_id?>`, 10);
        document.querySelectorAll('.quick_add_listening .subnav').forEach(el => {
          el.style.display = 'none';
        });
        ajax({
          data: {
            album_id: album_id,
            artist_ids: artist_ids,
            created: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' '),
            date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10).replace('T', ' '),
            format: format_value,
            submitType: document.querySelector('input[name="submitType"]').value,
            text: false
          },
          dataType: 'json',
          statusCode: {
            201: () => {
              // 201 Created
              var love = document.querySelector('#love');
              var msg = love.querySelector('.like_msg');
              msg.innerHTML = 'New listening!';
              msg.style.display = '';
              setTimeout(() => {
                // Note: jQuery's fadeOut() animated this over 1s; plain hide
                // drops the animation but keeps the same end state.
                document.querySelectorAll('.like_msg').forEach(el => {
                  el.style.display = 'none';
                });
              }, `<?=MSG_FADEOUT?>`);
            },
            400: () => {
              // 400 Bad Request
              alert('400 Bad Request');
              document.querySelector('#recentlyListenedLoader2').style.display = 'none';
            },
            401: () => {
              // 401 Unauthorized
              alert('401 Unauthorized');
              document.querySelector('#recentlyListenedLoader2').style.display = 'none';
            },
            404: () => {
              // 404 Not found
              alert('404 Not Found');
              document.querySelector('#recentlyListenedLoader2').style.display = 'none';
            }
          },
          type: 'POST',
          url: '/api/listening/add'
        });
        event.preventDefault();
        event.stopPropagation();
      });
    });
  }
});

app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
view.initAlbumEvents();

var update_bio = parseInt(`<?=($update_bio === true) ? 1 : 0?>`, 10);
if (update_bio === 1) {
  view.updateAlbumBio();
}

document.querySelectorAll('.quick_add_listening').forEach(el => {
  el.addEventListener('click', function () {
    var subNav = this.parentElement.querySelector('ul.subnav');
    if (!subNav) {
      return;
    }
    // Note: jQuery's slideUp()/slideDown() animated this; plain display
    // toggle drops the animation but keeps the same show/hide behavior.
    if (subNav.offsetParent !== null) {
      this.classList.remove('active');
      subNav.style.display = 'none';
    } else {
      this.classList.add('active');
      subNav.style.display = '';
    }
  });
  el.addEventListener('mouseenter', function () {
    this.classList.add('subhover');
  });
  el.addEventListener('mouseleave', function () {
    this.classList.remove('subhover');
  });
});
