var cumulative_done = false;
$.extend(view, {
  // Get artist fan.
  getFan: function (user_id) {
    if (user_id === undefined) {
      $('#fanLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#fanLoader').hide(); 
      },
      data:{
        user_id:user_id
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $('#fan').addClass('fan_del');
        },
        204: function () { // 204 No Content
          $('#fan').addClass('fan_add');
        },
        400: function () { // 400 Bad request
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/fan/get/<?=$artist_id?>'
    });
  },
  // Get artist fans.
  getFans: function () {
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
              $('#artistFanLoader').hide();
              $('#artistFan').html(data);
            },
            type:'POST',
            url:'/ajax/likeList'
          });
        },
        204: function () { // 204 No Content
          $('#artistFanLoader').hide();
          $('#artistFan').html('');
        },
        400: function () { // 400 Bad request
          $('#artistFanLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/fan/get/<?=$artist_id?>'
    });
  },
  // Get artist tags.
  getTags: function () {
    $.ajax({
      data:{
        artist_id:parseInt(<?=$artist_id?>),
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              delete:false,
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
      url:'/api/tag/get/artist'
    });
  },
  getListeningCumulation: function (type) {
    cumulative_done = true;
    $.ajax({
      data:{
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
        group_by:group_by,
        limit:200,
        order_by:order_by,
        select:select,
        sub_group_by:'',
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
        artist_name:'<?=$artist_name?>'
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
      url:'/api/shout/get/artist'
    });
  },
  // Get artist listeners.
  getUsers: function () {
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        sub_group_by:'',
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
  // Get artist listenings.
  getListenings: function () {
    $.ajax({
      data:{
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
              strlenght:30,
              size:32
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
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
        artist_name:'<?=$artist_name?>',
        limit:5,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          console.log(data);
          $.ajax({
            data:{
              hide:{
                format_icon:true
              },
              json_data:data
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
  getAlbumShouts: function () {
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
              $('#albumShoutLoader').hide();
              $('#albumShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        },
        204: function () {
          $('#albumShoutLoader').hide();
          $('#albumShout').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  updateArtistBio: function () {
    $.ajax({
      data:{
        artist_id:parseInt(<?=$artist_id?>),
        artist_name:'<?=$artist_name?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/artist/update/biography'
    });
  },
  initArtistEvents: function () {
    $(document).one('ajaxStop', function (event, request, settings) {
      if (cumulative_done === false) {
        view.getListeningCumulation();
      }
    });
    $('html').on('click', '#fan', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('fan_add')) {
        $.ajax({
          data:{},
          statusCode:{
            201: function (data) { // 201 Created
              $('#fan').removeClass('fan_add').addClass('fan_del').find('.like_msg').html('You\'re a fan!').show();
              setTimeout(function () {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getFans();
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
          url:'/api/fan/add/' + parseInt(<?=$artist_id?>)
        });
      }
      if ($(this).hasClass('fan_del')) {
        $.ajax({
          data:{},
          statusCode:{
            204: function () { // 204 No Content
              $('#fan').removeClass('fan_del').addClass('fan_add').find('.like_msg').html('Unfaned.').show();
              setTimeout(function () {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getFans();
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
          url:'/api/fan/delete/' + parseInt(<?=$artist_id?>)
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.each($('.chosen-select').val(), function (i, el) {
        var tag = el.split(':');
        $.ajax({
          async:false,
          data:{
            artist_id:parseInt(<?=$artist_id?>),
            tag_id:tag[1],
            type:'artist'
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
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>');
  view.getFan(parseInt(<?=$this->session->userdata('user_id')?>));
  view.getFans();
  view.getTags();
  view.getListeningHistory('%Y');
  view.getShouts();
  view.getUsers();
  view.getListenings();
  view.getFormats();
  view.getAlbumShouts();
  view.initArtistEvents();

  var update_bio = <?=($update_bio === true) ? 1 : 0?>;
  if (update_bio === 1) {
    view.updateArtistBio();
  }
});
