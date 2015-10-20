$.extend(view, {
  compareStrings: function (a, b) {
    if (a > b) return -1;
    else if (a < b) return 1;
    return 0;
  },
  // Get listening by year.
  getListenings: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listener/get',
      data:{
        limit:100,
        group_by:'year(<?=TBL_listening?>.`date`)',
        order_by:'`date` ASC',
        username:'<?php echo !empty($username) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/barChart',
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#userListeningsLoader').hide();
              $('#userListenings').html(data).bind('highchartTable.beforeRender', function(event, highChartConfig) {
                highChartConfig.tooltip = {
                  <?=TBL_highchart_tooltip?>
                }
                highChartConfig.yAxis = {
                  <?=TBL_highchart_yaxis?>
                }
              }).highchartTable().hide();
            }
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      }
    });
  },
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    if (isFirst != true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data:{
        limit:10,
        username:'<?php echo !empty($username) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',url:'/ajax/chartTable',
            data:{
              json_data:data,
              hide:{
                del:true
              }
            },
            success: function (data) {
              $('#recentlyListenedLoader2').hide();
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            }
          })
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      }
    });
  },
  // Get top artists.
  getTopArtists: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
      data:{
        limit:5,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?php echo !empty($username) ? $username: ''?>'
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
  // Get top albums.
  getTopAlbums: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/album/get',
      data:{
        limit:5,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?php echo !empty($username) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/albumList/124',
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            complete: function () {
              setTimeout(view.getTopAlbums, 60 * 10 * 1000);
            }
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      }
    });
  },
  recentlyFaned: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/fan/get',
      data:{
        limit:13,
        username:'<?php echo !empty($username) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/likeTable',
            data:{
              json_data:data,
              hide:{
                rank:true,
                user:true
              }
            },
            success: function (data) {
              $('#recentlyFanedLoader').hide();
              $('#recentlyFaned').html(data);
            }
          });
        }
      }
    });
  },
  recentlyLoved: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/love/get',
      data:{
        limit:13,
        username:'<?php echo !empty($username) ? $username: ''?>'
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/likeTable',
            data:{
              json_data:data,
              hide:{
                rank:true,
                user:true
              }
            },
            success: function (data) {
              $('#recentlyLovedLoader').hide();
              $('#recentlyLoved').html(data);
            }
          });
        }
      }
    });
  }
});

$(document).ready(function () {
  view.getListenings();
  view.getRecentListenings();
  view.getTopAlbums();
  view.getTopArtists();
  view.recentlyFaned();
  view.recentlyLoved();

  $(document).ajaxStop(function (event, request, settings ) {
    $('#recentlyLiked').append($('.recentlyLiked tr').detach().sort(function (a, b) {
      return view.compareStrings($(a).attr('data-created'), $(b).attr('data-created'));
    }));
    $('#recentlyLikedLoader').hide();
  });
});