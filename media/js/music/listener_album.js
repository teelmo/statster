$.extend(view, {
  // Get album tags.
  getTags: () => {
    $.ajax({
      data: {
        album_id: parseInt(`<?=$album_id?>`, 10),
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              delete: true,
              json_data: data,
              logged_in: `<?=$logged_in?>`
            },
            success: data => {
              $('#tagsLoader').hide();
              $('#tags').html(data);
            },
            type: 'POST',
            url: '/ajax/tagList'
          });
        },
        204: () => {
          // 204 No Content
          $('#tagsLoader').hide();
          $('#tags').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#tagsLoader').hide();
          $('#tags').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/tag/get/album'
    });
  },
  // Get album love.
  getLove: user_id => {
    if (user_id === undefined) {
      $('#loveLoader').hide();
      return;
    }
    $.ajax({
      complete: () => {
        $('#loveLoader').hide();
      },
      data: {
        user_id: user_id
      },
      dataType: 'json',
      statusCode: {
        200: () => {
          // 200 OK
          $('#love').addClass('love_del');
        },
        204: () => {
          // 204 No Content
          $('#love').addClass('love_add');
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
    $.ajax({
      data: {},
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {},
              json_data: data
            },
            success: data => {
              $('#albumLoveLoader').hide();
              $('#albumLove').html(data);
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          $('#albumLoveLoader').hide();
          $('#albumLove').html('');
        },
        400: () => {
          // 400 Bad request
          $('#albumLoveLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    });
  },
  topListeners: () => {
    $.ajax({
      data: {
        album_name: '<?=$album_name?>',
        artist_name: '<?=$artist_name?>',
        limit: 100,
        sub_group_by: 'album'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  getListenings: () => {
    $.ajax({
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
          $.ajax({
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
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type: 'POST',
            url: `<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>`
          });
        },
        204: () => {
          // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  initListenerAlbumEvents: () => {
    $('html').on('click', '#love', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('love_add')) {
        $.ajax({
          data: {},
          statusCode: {
            201: () => {
              // 201 Created
              $('#love').removeClass('love_add').addClass('love_del').find('.like_msg').html("You're in love!").show();
              setTimeout(() => {
                $('.like_msg').fadeOut(1000);
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
      if ($(this).hasClass('love_del')) {
        $.ajax({
          data: {},
          statusCode: {
            204: () => {
              // 204 No Content
              $('#love').removeClass('love_del').addClass('love_add').find('.like_msg').html('Unloved.').show();
              setTimeout(() => {
                $('.like_msg').fadeOut(1000);
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
    $('html').on('click', '#submitTags', () => {
      $.when(
        $.each($('.chosen-select').val(), (_i, el) => {
          var tag = el.split(':');
          $.ajax({
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
        })
      ).done(() => {
        $('.chosen-select option').removeAttr('selected');
        $('#tagAdd select').trigger('chosen:updated');
        view.getTags();
      });
      $('#tagAdd').hide();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
  view.getLove(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
  view.getLoves();
  view.getTags();
  view.topListeners();
  view.getListenings();
  view.initListenerAlbumEvents();
});
