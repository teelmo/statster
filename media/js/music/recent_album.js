Object.assign(view, {
  // Get album tags.
  getTags: () => {
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
              delete: true,
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
      url: '/api/tag/get/album'
    });
  },
  // Get album love.
  getLove: user_id => {
    if (user_id === undefined) {
      document.querySelector('#loveLoader').classList.add('hidden');
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#loveLoader').classList.add('hidden');
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
    });
  },
  // Get album loves.
  getLoves: () => {
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
              document.querySelector('#albumLoveLoader').classList.add('hidden');
              document.querySelector('#albumLove').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#albumLoveLoader').classList.add('hidden');
          document.querySelector('#albumLove').innerHTML = '';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#albumLoveLoader').classList.add('hidden');
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    });
  },
  // Get recent listenings.
  getRecentListenings: (_isFirst, _callback) => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 100,
        sub_group_by: 'album',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              strlenght: 50,
              time: Math.floor((Date.now() - new Date().getTimezoneOffset() * 60000) / 1000)
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').classList.add('hidden');
              document.querySelector('#recentlyListened').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          alert('400 Bad Request');
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  getUsers: () => {
    ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 14,
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
          document.querySelector('#topListener').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListenerLoader').classList.add('hidden');
          document.querySelector('#topListener').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  initRecentAlbumEvents: () => {
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
                  el.classList.add('hidden');
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getLoves();
            },
            400: () => {
              // 400 Bad request
              alert(`<?=ERR_BAD_REQUEST?>`);
            },
            401: () => {
              // 401 Unauthorized
              alert('401 Unauthorized');
            },
            404: () => {
              // 404 Not Found
              alert('404 Not Found');
            }
          },
          type: 'POST',
          url: '/api/love/add/<?=$album_id?>'
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
                  el.classList.add('hidden');
                });
              }, `<?=MSG_FADEOUT?>`);
              view.getLoves();
            },
            400: () => {
              // 400 Bad request
              alert(`<?=ERR_BAD_REQUEST?>`);
            },
            401: () => {
              // 401 Unauthorized
              alert('401 Unauthorized');
            },
            404: () => {
              // 404 Not Found
              alert('404 Not Found');
            }
          },
          type: 'POST',
          url: '/api/love/delete/<?=$album_id?>'
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
                // 401 Unauthorized
                alert('401 Unauthorized');
              },
              404: () => {
                // 404 Not Found
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
    // Note: the original bound these same three delegated handlers on both
    // 'html' and 'body' (a two-element jQuery selection), so each fired
    // twice per matching click - preserved here via two separate roots
    // rather than collapsing to one.
    [document.querySelector('html'), document.querySelector('body')].forEach(root => {
      root.addEventListener('click', event => {
        var target = event.target.closest('span.delete');
        if (!target) {
          return;
        }
        var container = document.querySelector(target.dataset.confirmationContainer);
        if (container) {
          container.classList.remove('hidden');
        }
      });
      root.addEventListener('click', event => {
        var target = event.target.closest('a.cancel');
        if (!target) {
          return;
        }
        target.closest('div').classList.add('hidden');
      });
      root.addEventListener('click', event => {
        var target = event.target.closest('a.confirm');
        if (!target) {
          return;
        }
        var rowId = target.dataset.rowId;
        var row = document.querySelector(`#${rowId}`);
        if (row?.classList.contains('just_added')) {
          document.querySelectorAll('tr').forEach(el => {
            el.classList.remove('just_added_rest');
          });
        }
        ajax({
          statusCode: {
            200: () => {
              // 200 OK
              // Note: jQuery's fadeOut('slow') animated this; plain hide
              // drops the animation but keeps the same end state.
              if (row) {
                row.classList.add('hidden');
              }
            },
            400: () => {
              // 400 Bad Request
              alert('400 Bad Request');
            },
            401: () => {
              // 401 Unauthorized
              alert('401 Unauthorized');
            },
            404: () => {
              // 404 Not found
              alert('404 Not Found');
            }
          },
          type: 'POST',
          url: `/api/listening/delete/${target.dataset.listeningId}`
        });
      });
    });
  }
});

app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
view.getLove(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
view.getLoves();
view.getTags();
view.getRecentListenings();
view.getUsers();
view.initRecentAlbumEvents();
