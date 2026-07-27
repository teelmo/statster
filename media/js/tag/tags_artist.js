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
              document.querySelector('#tagsLoader').classList.add('hidden');
              document.querySelector('#tags').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/tagList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#tagsLoader').classList.add('hidden');
          document.querySelector('#tags').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#tagsLoader').classList.add('hidden');
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
      document.querySelector('#fanLoader').classList.add('hidden');
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#fanLoader').classList.add('hidden');
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
          alert('<?=ERR_BAD_REQUEST?>');
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
              document.querySelector('#artistFanLoader').classList.add('hidden');
              document.querySelector('#artistFan').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#artistFanLoader').classList.add('hidden');
          document.querySelector('#artistFan').innerHTML = '';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#artistFanLoader').classList.add('hidden');
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type: 'GET',
      url: '/api/fan/get/<?=$artist_id?>'
    });
  },
  // Get artist tags.
  getTopTags: () => {
    ajax({
      data: {
        artist_id: parseInt(`<?=$artist_id?>`, 10),
        limit: 100,
        sort: true
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                add: true
              },
              json_data: data,
              logged_in: '<?=$logged_in?>'
            },
            success: data => {
              document.querySelector('#topTagsLoader').classList.add('hidden');
              document.querySelector('#topTags').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topTagsLoader').classList.add('hidden');
          document.querySelector('#topTags').innerHTML = '<?=ERR_NO_RESULTS?>';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topTagsLoader').classList.add('hidden');
          document.querySelector('#topTags').innerHTML = '<?=ERR_BAD_REQUEST?>';
        }
      },
      type: 'GET',
      url: '/api/tag/get/artist'
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
              document.querySelector('#topListenerLoader').classList.add('hidden');
              document.querySelector('#topListener').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListenerLoader').classList.add('hidden');
          document.querySelector('#topListener').innerHTML = '<?=ERR_NO_RESULTS?>';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListenerLoader').classList.add('hidden');
          document.querySelector('#topListener').innerHTML = '<?=ERR_BAD_REQUEST?>';
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
              document.querySelector('#recentlyListenedLoader').classList.add('hidden');
              document.querySelector('#recentlyListened').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/sideTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = '<?=ERR_NO_RESULTS?>';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = '<?=ERR_BAD_REQUEST?>';
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  initMetaArtistEvents: () => {
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
          url: '/api/fan/delete/<?=$artist_id?>'
        });
      }
    });
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('#submitTags');
      if (!target) {
        return;
      }
      var tagPromises = Array.from(document.querySelector('#tagAdd select').selectedOptions)
        .map(o => o.value)
        .map(el => {
          var tag = el.split(':');
          return ajax({
            data: {
              artist_id: `<?=$artist_id?>`,
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
      document.querySelector('#tagAdd').classList.remove('active');
      document.querySelector('#tagAdd').classList.add('hidden');
      Promise.all(tagPromises).then(() => {
        view.getTags();
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>`);
view.getFan(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
view.getFans();
view.getTags();
view.getTopTags();
view.getUsers();
view.getListenings();
view.initMetaArtistEvents();
