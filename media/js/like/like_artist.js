Object.assign(view, {
  // Get artist tags.
  getTags: () => {
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
              document.querySelector('#tagsLoader').style.display = 'none';
              document.querySelector('#tags').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/tagList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#tagsLoader').style.display = 'none';
          document.querySelector('#tags').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#tagsLoader').style.display = 'none';
          document.querySelector('#tags').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/tag/get/artist'
    });
  },
  // Get artist fan.
  getFan: user_id => {
    if (user_id === undefined) {
      document.querySelector('#fanLoader').style.display = 'none';
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#fanLoader').style.display = 'none';
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
    });
  },
  // Get artist fans.
  getFans: () => {
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
              document.querySelector('#artistFanLoader').style.display = 'none';
              document.querySelector('#artistFan').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#artistFanLoader').style.display = 'none';
          document.querySelector('#artistFan').innerHTML = '';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#artistFanLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/fan/get/<?=$artist_id?>'
    });
  },
  recentlyFaned: () => {
    ajax({
      data: {
        limit: 100
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
              document.querySelector('#recentlyFanedLoader').style.display = 'none';
              document.querySelector('#recentlyFaned').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyFanedLoader').style.display = 'none';
          document.querySelector('#recentlyFaned').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#recentlyFanedLoader').style.display = 'none';
          document.querySelector('#recentlyFaned').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/fan/get/<?=$artist_id?>'
    });
  },
  // Get artist listeners.
  getUsers: () => {
    ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 6,
        sub_group_by: ''
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
  // Get artist listenings.
  getListenings: () => {
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
              size: 32
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').style.display = 'none';
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#recentlyListenedLoader').style.display = 'none';
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  initLikeArtistEvents: () => {
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
                  el.style.display = 'none';
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
          url: '/api/fan/add/<?=$artist_id?>'
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
                  el.style.display = 'none';
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
          url: '/api/fan/delete/<?=$artist_id?>'
        });
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#submitTags');
      if (!target) {
        return;
      }
      Array.from(document.querySelector('#tagAdd select').selectedOptions).map(o => o.value).forEach(el => {
        var tag = el.split(':');
        ajax({
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
view.getFan(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
view.getFans();
view.getTags();
view.recentlyFaned();
view.getUsers();
view.getListenings();
view.initLikeArtistEvents();
