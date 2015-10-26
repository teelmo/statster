$.extend(view, {
  getListeningHistory: function (type) {
    app.initChart();
    $.ajax({
      data:{
        group_by:type + '(<?=TBL_listening?>.`date`)',
        limit:100,
        order_by:type + '(<?=TBL_listening?>.`date`) ASC',
        select:type + '(<?=TBL_listening?>.`date`) as `bar_date`',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:(type == 'weekday') ? type + '(<?=TBL_listening?>.`date`) IS NOT NULL' : type + '(<?=TBL_listening?>.`date`) != \'00\''
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
              type:type
            },
            success: function (data) {
              $('#historyLoader').hide();
              $('#history').html(data).hide();
              var categories = [];
              var data = [];
              $.each($('#history .time'), function (i, el) {
                categories.push($(this).html());
              });
              $.each($('#history .count'), function (i, el) {
                data.push(parseInt($(this).html()));
              });
              app.chart.xAxis[0].setCategories(categories, false);
              app.chart.series[0].setData(data, true);
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
  view.getListeningHistory('year');
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
