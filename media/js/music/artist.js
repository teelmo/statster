var cumulative_done = false;
$.extend(view, {
  // Get artist fan.
  getFan: user_id => {
    if (user_id === undefined) {
      $('#fanLoader').hide();
      return;
    }
    $.ajax({
      complete: () => {
        $('#fanLoader').hide();
      },
      data: {
        user_id: user_id
      },
      dataType: 'json',
      statusCode: {
        200: () => {
          // 200 OK
          $('#fan').addClass('fan_del');
        },
        204: () => {
          // 204 No Content
          $('#fan').addClass('fan_add');
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
              $('#artistFanLoader').hide();
              $('#artistFan').html(data);
            },
            type: 'POST',
            url: '/ajax/likeList'
          });
        },
        204: () => {
          // 204 No Content
          $('#artistFanLoader').hide();
          $('#artistFan').html('');
        },
        400: () => {
          // 400 Bad request
          $('#artistFanLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/fan/get/<?=$artist_id?>'
    });
  },
  // Get artist tags.
  getTags: () => {
    $.ajax({
      data: {
        artist_id: parseInt(`<?=$artist_id?>`, 10),
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              delete: false,
              json_data: data,
              logged_in: `<?=$logged_in?>` === '1'
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
      url: '/api/tag/get/artist'
    });
  },
  getListeningCumulation: () => {
    cumulative_done = true;
    $.ajax({
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
        artist_name: '<?=$artist_name?>',
        group_by: group_by,
        limit: 200,
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
          $('#historyLoader').hide();
          $('#history').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#historyLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  getShouts: () => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>'
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
              $('#shoutLoader').hide();
              $('#shout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#shoutLoader').hide();
          $('#shout').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#shoutLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/shout/get/artist'
    });
  },
  // Get artist listeners.
  getUsers: () => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        sub_group_by: '',
        limit: 6
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
        },
        204: () => {
          // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  // Get artist listenings.
  getListenings: () => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 6,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
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
                rank: true,
                spotify: true
              },
              json_data: data,
              strlenght: 30,
              size: 32
            },
            success: data => {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type: 'POST',
            url: '/ajax/sideTable'
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
  getFormats: () => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 5,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
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
              $('#topListeningFormatTypesLoader').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/format/get'
    });
  },
  getAlbumShouts: () => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 5
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                user: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              $('#albumShoutLoader').hide();
              $('#albumShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        },
        204: () => {
          $('#albumShoutLoader').hide();
          $('#albumShout').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/shout/get/album'
    });
  },
  updateArtistBio: () => {
    $.ajax({
      data: {
        artist_id: parseInt(`<?=$artist_id?>`, 10),
        artist_name: '<?=$artist_name?>'
      },
      dataType: 'json',
      type: 'GET',
      url: '/api/artist/update/biography'
    });
  },
  initArtistEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('html').on('click', '#fan', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('fan_add')) {
        $.ajax({
          data: {},
          statusCode: {
            201: () => {
              // 201 Created
              $('#fan').removeClass('fan_add').addClass('fan_del').find('.like_msg').html("You're a fan!").show();
              setTimeout(() => {
                $('.like_msg').fadeOut(1000);
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
      if ($(this).hasClass('fan_del')) {
        $.ajax({
          data: {},
          statusCode: {
            204: () => {
              // 204 No Content
              $('#fan').removeClass('fan_del').addClass('fan_add').find('.like_msg').html('Unfaned.').show();
              setTimeout(() => {
                $('.like_msg').fadeOut(1000);
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
    $('html').on('click', '#submitTags', () => {
      $.each($('.chosen-select').val(), (_i, el) => {
        var tag = el.split(':');
        $.ajax({
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
      $('.chosen-select option').removeAttr('selected');
      $('#tagAdd select').trigger('chosen:updated');
      view.getTags();
      $('#tagAdd').hide();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>`);
  view.getFan(parseInt(`<?=$this->session->userdata('user_id')?>`, 10));
  view.getFans();
  view.getTags();
  view.getListeningHistory('%Y');
  view.getShouts();
  view.getUsers();
  view.getListenings();
  view.getFormats();
  view.getAlbumShouts();
  view.initArtistEvents();

  var update_bio = parseInt(`<?=($update_bio === true) ? 1 : 0?>`, 10);
  if (update_bio === 1) {
    view.updateArtistBio();
  }
});
