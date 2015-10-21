$.extend(view, {
  getListenings: function () {
    $.ajax({
      data:{
        group_by:'year(<?=TBL_listening?>.`date`)',
        limit:100,
        order_by:'`date` ASC',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/listener/get',
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
  popularGenre: function () {
    $.ajax({
      data:{
        limit:40,
        lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/genre/get',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#popularGenreLoader').hide();
              $('#popularGenre').html(data);
            },
            type:'POST',
            url:'/ajax/popularTag'
          });
        },
        204: function () { // 204 No Content
          alert('204 No Content');
        },
        404: function () { // 404 Not found
          alert('404 Not Found');
        }
      }
    });
  },
  topAlbum: function () {
    $.ajax({
      data:{
        limit:15,
        lower_limit:'<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/album/get',
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/sideTable',
            data:{
              json_data:data,
              hide:{
                count:true,
                rank:true,
                date:true,
                calendar:true
              }
            },
            success: function (data) {
              $('#popularAlbumLoader').hide();
              $('#popularAlbum').html(data);
            }
          });
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
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        limit:18
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/likeTable',
            data:{
              json_data:data,
              hide:{
                rank:true
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
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        limit:18
      },
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/likeTable',
            data:{
              json_data:data,
              hide:{
                rank:true
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
  view.popularGenre();
  view.topAlbum();
  view.recentlyFaned();
  view.recentlyLoved();

  $(document).ajaxStop(function (event, request, settings ) {
    $('#recentlyLiked').append($('.recentlyLiked tr').detach().sort(function (a, b) {
      return app.compareStrings($(a).attr('data-created'), $(b).attr('data-created'));
    }));
    $('#recentlyLikedLoader').hide();
  });
});
