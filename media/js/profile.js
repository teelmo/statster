$.extend(view, {
  // Get listening by year.
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') IS NOT NULL AND DATE_FORMAT(<?=TBL_listening?>.`date`, \'%d\') != \'00\'';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      data:{
        group_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')',
        limit:200,
        order_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
        username:'<?=(!empty($username)) ? $username: ''?>',
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
          $('.music_bar').hide();
        },
        400: function () { // 400 Bad request
          $('#historyLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>');
          $('.music_bar').hide();
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    if (isFirst != true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      data:{
        limit:12,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                del:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyListenedLoader2').hide();
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
              var currentTime = new Date();
              var hours = currentTime.getHours();
              var minutes = currentTime.getMinutes();
              if (minutes < 10) {
                minutes = '0' + minutes;
              }
              $('#recentlyUpdated').html('updated <span class="number">' + hours + '</span>:<span class="number">' + minutes + '</span>');
              $('#recentlyUpdated').attr('value', currentTime.getTime());
            },
            type:'POST',
            url:'/ajax/musicTable'
          })
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  // Get top albums.
  getTopAlbums: function () {
    $.ajax({
      data:{
        limit:10,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
            },
            complete: function () {
              setTimeout(view.getTopAlbums, 60 * 10 * 1000);
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124',
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  // Get top artists.
  getTopArtists: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
      data:{
        limit:10,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/artistList/124',
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            complete: function () {
              setTimeout(view.getTopArtists, 60 * 10 * 1000);
            }
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      }
    });
  },
  getShouts: function () {
    $.ajax({
      data:{
        username:'<?=$username?>'
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
              $('#userShoutLoader').hide();
              $('#userShout').html(data);
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
      url:'/api/shout/get/user'
    });
  },
  getAlbumShouts: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=$username?>'
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
              $('#albumShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  getArtistShouts: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=$username?>'
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
              $('#artistShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/artist'
    });
  },
  recentlyFaned: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true,
                user:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyFaned').html(data);
            },
            type:'POST',
            url:'/ajax/likeTable'
          });
        }
      },
      type:'GET',
      url:'/api/fan/get'
    });
  },
  recentlyLoved: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=(!empty($username)) ? $username: ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true,
                user:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyLoved').html(data);
            },
            type:'POST',
            url:'/ajax/likeTable'
          });
        }
      },
      type:'GET',
      url:'/api/love/get'
    });
  },
  getFormats: function () {
    $.ajax({
      data:{
        limit:5,
        username:'<?=(!empty($username)) ? $username: ''?>'
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
  initProfileEvents: function () {
    $(document).ajaxStop(function (event, request, settings) {
      $('#musicShout').append($('.shouts tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#musicShoutLoader').hide();

      $('#recentlyLiked').append($('.likes tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#recentlyLikedLoader').hide();
    });
    $('#refreshRecentAlbums').click(function () {
      view.getRecentListenings();
    });
  }
});

$(document).ready(function () {
  view.getListeningHistory('%Y');
  view.getRecentListenings();
  view.getTopAlbums();
  view.getTopArtists();
  view.getShouts();
  view.getAlbumShouts();
  view.getArtistShouts();
  view.recentlyFaned();
  view.recentlyLoved();
  view.getFormats();
  view.initProfileEvents();
});