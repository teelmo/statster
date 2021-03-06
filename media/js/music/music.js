$.extend(view, {
  getListeningCumulation: function (type) {
    $.ajax({
      data:{
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
      url:'/api/listener/get/cumulative'
    });
  },
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var group_by = 'WEEKDAY(<?=TBL_listening?>.`date`)';
      var order_by = 'WEEKDAY(<?=TBL_listening?>.`date`) ASC';
      var select = 'WEEKDAY(<?=TBL_listening?>.`date`) as `bar_date`';
      var where = 'WEEKDAY(<?=TBL_listening?>.`date`) IS NOT NULL AND DATE_FORMAT(<?=TBL_listening?>.`date`, \'%d\') != \'00\'';
    }
    else if (type == '%Y%m') {
      var group_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')';
      var order_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC';
      var select = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`';
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var group_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')';
      var order_by = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC';
      var select = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`';
      var where = 'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      data:{
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
  getPopularGenres: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
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
            url:'/ajax/tagTable'
          });
        },
        204: function () { // 204 No Content
          alert('204 No Content');
        },
        404: function () { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  getPopularAlbums: function (interval) {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var today = new Date();
      today.setDate(today.getDate() - parseInt(interval));
      lower_limit = today.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:15,
        lower_limit:lower_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              hide:{
                calendar:true,
                count:true,
                date:true,
                rank:true
              }
            },
            success: function (data) {
              $('#popularAlbumLoader, #popularAlbumLoader2').hide();
              $('#popularAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  getSecondChance: function () {
    $.ajax({
      data:{
        having:'`count` = 1',
        limit:100,
        lower_limit:'1970-00-00',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'<?=TBL_listening?>.`date` < \'<?=CUR_YEAR - 1?>-12-31\''
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            complete: function () {
              setTimeout(view.secondChance, 60 * 10 * 1000);
            },
            data:{
              json_data:data,
              hide:{
                calendar:true,
                count:true,
                date:true,
                rank:true
              },
              limit:4
            },
            success: function (data) {
              $('#secondChanceLoader').hide();
              $('#secondChance').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function () { // 204 No Content
          $('#secondChanceLoader').hide();
          $('#secondChance').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/secondChance'
    });
  },
  getRecentlyFaned: function () {
    $.ajax({
      data:{
        limit:10,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true
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
  getRecentlyLoved: function () {
    $.ajax({
      data:{
        limit:10,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              hide:{
                rank:true
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
  initMusicEvents: function () {
    $(document).ajaxStop(function (event, request, settings ) {
      $('#recentlyLiked').append($('.recently_liked tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#recentlyLikedLoader').hide();
    });
    $('#refreshSecondChanceAlbums').click(function () {
      view.secondChance();
    });
  }
});

$(document).ready(function () {
  view.getListeningHistory('%Y');
  view.getListeningCumulation();
  view.getPopularGenres();
  view.getPopularAlbums('<?=$popular_album_music?>');
  view.getSecondChance();
  view.getRecentlyFaned();
  view.getRecentlyLoved();
  view.initMusicEvents();
});
