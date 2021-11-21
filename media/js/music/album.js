var cumulative_done = false;
$.extend(view, {
  // Get album love.
  getLove: function (user_id) { 
    if (user_id === undefined) {
      $('#loveLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#loveLoader').hide();
      },
      data:{
        user_id:user_id
      },
      dataType:'json',
      statusCode:{
        200: function () { // 200 OK
          $('#love').addClass('love_del');
        },
        204: function () { // 204 No Content
          $('#love').addClass('love_add');
        },
        400: function () {
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/love/get/' + parseInt(<?=$album_id?>)
    });
  },
  // Get album loves.
  getLoves: function () {
    $.ajax({
      data:{},
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{},
              json_data:data
            },
            success: function (data) {
              $('#albumLoveLoader').hide();
              $('#albumLove').html(data);
            },
            type:'POST',
            url:'/ajax/likeList'
          });
        },
        204: function () { // 204 No Content
          $('#albumLoveLoader').hide();
          $('#albumLove').html('');
        },
        400: function () { // 400 Bad request
          $('#albumLoveLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/love/get/' + parseInt(<?=$album_id?>)
    });
  },
  // Get album tags.
  getTags: function () {
    $.ajax({
      data:{
        album_id:parseInt(<?=$album_id?>),
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              delete:true,
              logged_in:<?=$logged_in?>
            },
            success: function (data) {
              $('#tagsLoader').hide();
              $('#tags').html(data);
            },
            type:'POST',
            url:'/ajax/tagList'
          });
        },
        204: function () { // 204 No Content
          $('#tagsLoader').hide();
          $('#tags').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#tagsLoader').hide();
          $('#tags').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/tag/get/album'
    });
  },
  getListeningCumulation: function () {
    cumulative_done = true;
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          view.initGraph(data); 
        },
        204: function () { // 204 No Content
          $('.line').hide();
        },
        400: function () { // 400 Bad request
          $('.line').hide();
        }
      },
      type:'GET',
      url:'/api/listening/get/cumulative'
    });
  },
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'WEEKDAY(<?=TBL_listening?>.`date`) IS NOT NULL AND DATE_FORMAT(<?=TBL_listening?>.`date`, \'%d\') != \'00\'';
      var group_by = 'WEEKDAY(<?=TBL_listening?>.`date`)';
      var order_by = 'WEEKDAY(<?=TBL_listening?>.`date`) ASC';
      var select = 'WEEKDAY(<?=TBL_listening?>.`date`) as `bar_date`';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
      var group_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')';
      var order_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC';
      var select = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`';
    }
    else {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
      var group_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')';
      var order_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC';
      var select = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`';
    }
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        album_name:'<?=$album_name?>',
        group_by:group_by,
        limit:200,
        order_by:order_by,
        select:select,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:where
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:type
            },
            success: function (data) {
              $('#historyLoader').hide();
              $('#history').html(data).hide();
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type:'POST',
            url:'/ajax/musicBar'
          });
        },
        204: function () { // 204 No Content
          $('#historyLoader').hide();
          $('#history').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#historyLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  getShouts: function () {
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        album_name:'<?=$album_name?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          if (data[0].count == 1) {
            $('#shoutTotal').html('<span class="number">' + data[0].count + '</span> shout').fadeIn(500);
          }
          else {
            $('#shoutTotal').html('<span class="number">' + data[0].count + '</span> shouts').fadeIn(500);
          }
          $.ajax({
            data:{
              hide:{
                user:true
              },
              json_data:data,
              size:64,
              type:'user'
            },
            success: function (data) {
              $('#shoutLoader').hide();
              $('#shout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        },
        204: function () { // 204 No Content
          $('#shoutLoader').hide();
          $('#shout').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#shoutLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  // Get album listeners.
  getUsers: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:6
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  // Get album listenings.
  getListenings: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:6,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true,
                spotify:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  getFormats: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:5,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topListeningFormatTypesLoader').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  },
  getArtistShouts: function () {
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        limit:5
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                user:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#artistShoutLoader').hide();
              $('#artistShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        },
        204: function () {
          $('#artistShoutLoader').hide();
          $('#artistShout').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/shout/get/artist'
    });
  },
  updateAlbumBio: function () {
    $.ajax({
      data:{
        album_id:parseInt(<?=$album_id?>),
        album_name:'<?=$album_name?>',
        artist_id:parseInt(<?=$artist_id?>),
        artist_name:'<?=$artist_name?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/album/update/biography'
    });
  },
  initAlbumEvents: function () {
    $(document).ajaxStop(function (event, request, settings ) {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('html').on('click', '#love', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('love_add')) {
        $.ajax({
          data:{},
          statusCode:{
            201: function (data) { // 201 Created
              $('#love').removeClass('love_add').addClass('love_del').find('.like_msg').html('You\'re in love!').show();
              setTimeout(function() {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'POST',
          url:'/api/love/add/' + parseInt(<?=$album_id?>)
        });
      }
      if ($(this).hasClass('love_del')) {
        $.ajax({
          data:{},
          statusCode:{
            204: function () { // 204 No Content
              $('#love').removeClass('love_del').addClass('love_add').find('.like_msg').html('Unloved.').show();
              setTimeout(function() {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'POST',
          url:'/api/love/delete/' + parseInt(<?=$album_id?>)
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.each($('.chosen-select').val(), function (i, el) {
        var tag = el.split(':');
        $.ajax({
          async:false,
          data:{
            album_id:parseInt(<?=$album_id?>),
            tag_id:tag[1],
            type:'album'
          },
          statusCode:{
            201: function (data) { // 201 Created
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'POST',
          url:'/api/tag/add/' + tag[0]
        });
      });
      $('.chosen-select option').removeAttr('selected');
      $('#tagAdd select').trigger('chosen:updated');
      view.getTags();
      $('#tagAdd').hide();
    });
    $('html').on('mouseover', '.tag', function () {
      $(this).find('.remove').removeClass('hidden');
    });
    $('html').on('mouseout', '.tag', function () {
      $(this).find('.remove').addClass('hidden');
    });
    $('html').on('click', '.remove', function () {
      var type = $(this).data('tag-type');
      $.ajax({
        data:{
          album_id:parseInt(<?=$album_id?>),
          tag_id:parseInt($(this).data('tag-id'))
        },
        statusCode:{
          200: function (data) {
            view.getTags();
          }
        },
        url:'/api/' + type + '/delete',
        type:'POST'
      });
    });
  }
});

$(document).ready(function () {
  view.getLove(parseInt(<?=$this->session->userdata('user_id')?>));
  view.getLoves();
  view.getTags();
  view.getListeningHistory('%Y');
  view.getShouts();
  view.getUsers();
  view.getListenings();
  view.getFormats();
  view.getArtistShouts();
  view.initAlbumEvents();

  var update_bio = <?=($update_bio === true) ? 1 : 0?>;
  if (update_bio === 1) {
    view.updateAlbumBio();
  }
});
