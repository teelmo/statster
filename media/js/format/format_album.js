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
      url: '/api/tag/get/album'
    });
  },
  // Get album love.
  getLove: user_id => {
    if (user_id === undefined) {
      document.querySelector('#loveLoader').style.display = 'none';
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#loveLoader').style.display = 'none';
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
              document.querySelector('#albumLoveLoader').style.display = 'none';
              document.querySelector('#albumLove').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#albumLoveLoader').style.display = 'none';
          document.querySelector('#albumLove').innerHTML = '';
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#albumLoveLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    });
  },
  getFormats: () => {
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
  // Get album listeners.
  getUsers: () => {
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
  // Get album listenings.
  getListenings: () => {
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
                artist: true,
                count: true,
                rank: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              document.querySelector('#recentlyListenedLoader').style.display = 'none';
              document.querySelector('#recentlyListened').innerHTML = data;
            },
            type: 'POST',
            url: `<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>`
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
  initMetaAlbumEvents: () => {
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
          url: '/api/love/delete/<?=$album_id?>'
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
      document.querySelector('#tagAdd select').searchableSelectReset();
      view.getTags();
      document.querySelector('#tagAdd').classList.add('hidden');
    });
  }
});

app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
view.getLove(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
view.getLoves();
view.getTags();
view.getFormats();
view.getUsers();
view.getListenings();
view.initMetaAlbumEvents();
