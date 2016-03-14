$.extend(view, {
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
  popularGenre: function () {
    $.ajax({
      data:{
        limit:38,
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
  topAlbum: function () {
    $.ajax({
      data:{
        limit:15,
        lower_limit:'<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
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
              $('#popularAlbumLoader').hide();
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
  secondChance: function () {
    $.ajax({
      data:{
        having:'`count` = 1 AND <?=TBL_listening?>.`date` BETWEEN \'<?=CUR_YEAR - 4?>-00-00\' AND \'<?=CUR_YEAR - 1?>-12-31\'',
        limit:100,
        lower_limit:'1970-00-00',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
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
                artist:true,
                calendar:true,
                count:true,
                date:true,
                rank:true,
                spotify:true
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
  recentlyFaned: function () {
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
  recentlyLoved: function () {
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
  view.popularGenre();
  view.topAlbum();
  view.secondChance();
  view.recentlyFaned();
  view.recentlyLoved();
  view.initMusicEvents();


});
