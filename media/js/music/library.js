$.extend(view, {
  getListeningHistory: function (type, lower_limit, upper_limit) {
    var where;
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
    where += ' AND MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>';
    $.ajax({
      data:{
        group_by:group_by,
        limit:200,
        lower_limit:lower_limit,
        order_by:order_by,
        select:select,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:where
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:type,
              upper_limit:upper_limit
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
  topAlbum: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:17,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function (data) { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topArtist: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:17,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              type:'artist'
            },
            success: function (data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function (data) { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/artist/get'
    });
  },
  topListeners: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true,
                rank:true
              },
              json_data:data,
              size:64
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
  topReleases: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_album?>.`created`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_album?>.`created`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              hide:{
                artist:true,
                calendar:true,
                date:true,
                rank:true,
                spotify:true
              },
              size:64
            },
            success: function (data) {
              $('#topReleasesLoader').hide();
              $('#topReleases').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function (data) { // 204 No Content
          $('#topReleasesLoader').hide();
          $('#topReleases').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  topFormats: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:10,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
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
  topGenre: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader').hide();
              $('#topGenre').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function() { // 204 No Content
          $('#topGenreLoader').hide();
          $('#topGenre').html('<?=ERR_NO_RESULTS?>');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  topKeyword: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader').hide();
              $('#topKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function() { // 204 No Content
          $('#topKeywordLoader').hide();
          $('#topKeyword').html('<?=ERR_NO_RESULTS?>');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/keyword/get'
    });
  },
  topNationality: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topNationalityLoader').hide();
              $('#topNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function() { // 204 No Content
          $('#topNationalityLoader').hide();
          $('#topNationality').html('<?=ERR_NO_RESULTS?>');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/nationality/get/listenings'
    });
  },
  topYear: function (lower_limit, upper_limit) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:'MONTH(<?=TBL_listening?>.`date`) LIKE <?=addslashes($month)?> AND DAY(<?=TBL_listening?>.`date`) LIKE <?=addslashes($day)?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topYearLoader').hide();
              $('#topYear').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function() { // 204 No Content
          $('#topYearLoader').hide();
          $('#topYear').html('<?=ERR_NO_RESULTS?>');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/year/get'
    });
  }
});

$(document).ready(function () {
  view.initChart();
  view.topAlbum('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topArtist('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topReleases('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topListeners('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topFormats('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topGenre('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topKeyword('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topNationality('<?=$lower_limit?>', '<?=$upper_limit?>');
  view.topYear('<?=$lower_limit?>', '<?=$upper_limit?>');
});
