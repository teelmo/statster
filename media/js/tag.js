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
      type:'GET',
      dataType:'json',
      url:'/api/tag/get/<?=strtolower($tag_type)?>',
      data:{
        group_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\')',
        limit:100,
        lower_limit:'1970-00-00',
        order_by:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?=TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
        tag_id:'<?=$tag_id?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:where
      },
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
  // Get top albums.
  getTopAlbums: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/tag/get',
      data:{
        limit:10,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
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
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      }
    });
  },
  // Get top artists.
  getTopArtists: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/tag/get',
      data:{
        group_by:'`artist_id`',
        limit:10,
        order_by:'`count` DESC, <?=TBL_artist?>.`artist_name` ASC',
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
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
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      }
    });
  },
  // Get tag listeners.
  getUsers: function (from, where) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listener/get',
      data:{
        from:from,
        limit:10,
        where:where
      },
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32
            },
            type:'POST',
            url:'/ajax/userTable',
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            }
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
      }
    });
  },
  // Get tag listenings.
  getListenings: function (from, where) {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data:{
        from:from,
        limit:10,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:where
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/sideTable',
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {
          alert('400 Bad Request')
        }
      }
    });
  },
  initTagEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getListeningHistory('%Y');
  view.getTopAlbums();
  view.getTopArtists();
  switch ('<?=$tag_type?>') {
    case 'genre':
      var from = '(SELECT <?=TBL_genres?>.`genre_id`, <?=TBL_genres?>.`album_id` FROM <?=TBL_genres?> GROUP BY <?=TBL_genres?>.`genre_id`, <?=TBL_genres?>.`album_id`) as <?=TBL_genres?>';
      var where = '<?=TBL_genres?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_genres?>.`genre_id` = <?=$tag_id?>';
      break;
    case 'keyword':
      var from = '(SELECT <?=TBL_keywords?>.`keyword_id`, <?=TBL_keywords?>.`album_id` FROM <?=TBL_keywords?> GROUP BY <?=TBL_keywords?>.`keyword_id`, <?=TBL_keywords?>.`album_id`) as <?=TBL_keywords?>';
      var where = '<?=TBL_keywords?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_keywords?>.`keyword_id` = <?=$tag_id?>';
      break;
    case 'nationality':
      var from = '(SELECT <?=TBL_nationalities?>.`nationality_id`, <?=TBL_nationalities?>.`album_id` FROM <?=TBL_nationalities?> GROUP BY <?=TBL_nationalities?>.`nationality_id`, <?=TBL_nationalities?>.`album_id`) as <?=TBL_nationalities?>';
      var where = '<?=TBL_nationalities?>.`album_id` = <?=TBL_album?>.`id` AND <?=TBL_nationalities?>.`nationality_id` = <?=$tag_id?>';
      break;
    case 'year':
      var from = ''; 
      var where = '<?=TBL_album?>.`year` = <?=$tag_id?>';
      break;
    default:
      var from = '';
      var where = '';
      break;
  }
  view.getUsers(from, where);
  view.getListenings(from, where);
});