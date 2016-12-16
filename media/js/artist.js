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
  // Get album tags.
  getTags: function () {
    $.ajax({
      data:{
        artist_id:<?=$artist_id?>,
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
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
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') IS NOT NULL';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        group_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')',
        limit:200,
        order_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
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
  getAlbumShouts: function () {
    $.ajax({
      data:{
        limit:5,
        artist_name:'<?=$artist_name?>'
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
          $('#albumShout').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  updateArtistBio: function () {
    $.ajax({
      data:{
        artist_id:<?=$artist_id?>,
        artist_name:'<?=$artist_name?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/artist/update/biography'
    });
  },
  initArtistEvents: function () {
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
          url:'/api/fan/add/<?=$artist_id?>'
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
          type:'DELETE',
          url:'/api/fan/delete/<?=$artist_id?>'
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.when(
        $.each($('.chosen-select').val(), function (i, el) {
          var tag = el.split(':');
          $.ajax({
            data:{
              artist_id:<?=$artist_id?>,
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
        })
      ).done(function () {
        $('.chosen-select option').removeAttr('selected');
        $('#tagAdd select').trigger('chosen:updated');
        view.getTags();
      });
      $('#tagAdd').hide();
    });
  }
});

$(document).ready(function () {
  view.getFan(<?=$this->session->userdata('user_id')?>);
  view.getFans();
  view.getTags();
  view.getListeningHistory('%Y');
  view.getShouts();
  view.getUsers();
  view.getListenings();
  view.getAlbumShouts();
  view.initArtistEvents();

  var update_bio = <?=($update_bio === true) ? 1 : 0?>;
  if (update_bio === 1) {
    view.updateArtistBio();
  }
});
