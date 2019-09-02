$.extend(view, {
  getListeningCumulation: function () {
    $.ajax({
      data:{
        tag_id:'<?=$tag_id?>',
        username:'<?=(!empty($username)) ? $username: ''?>'
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
      url:'/api/tag/get/<?=strtolower($tag_type)?>/cumulative'
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
        group_by:group_by,
        limit:200,
        lower_limit:'1970-00-00',
        order_by:order_by,
        select:select,
        tag_id:'<?=$tag_id?>',
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
      url:'/api/tag/get/<?=strtolower($tag_type)?>'
    });
  },
  // Get top albums.
  getTopAlbums: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:13,
        lower_limit:lower_limit,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbumLoader, #topAlbumLoader2').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader, #topAlbumLoader2').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  // Get top artists.
  getTopArtists: function (interval) {
    if (interval === 'overall') {
      var lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(interval));
      var lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        group_by:'`artist_id`',
        limit:13,
        lower_limit:lower_limit,
        order_by:'`count` DESC, <?=TBL_artist?>.`artist_name` ASC',
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'artist'
            },
            success: function (data) {
              $('#topArtistLoader, #topArtistLoader2').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader, #topArtistLoader2').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  // Get tag listeners.
  getUsers: function (from, where) {
    $.ajax({
      data:{
        from:from,
        limit:10,
        where:where
      },
      dataType:'json',
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
            success: function(data) {
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
  // Get tag listenings.
  getListenings: function (from, where) {
    $.ajax({
      data:{
        from:from,
        limit:10,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:where
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
        400: function (data) {
          alert('400 Bad Request')
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  updateBio: function () {
    $.ajax({
      data:{
        tag_id:'<?=$tag_id?>',
        tag_name:'<?=$tag_name?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/<?=strtolower($tag_type)?>/update/biography'
    });
  },
  initTagEvents: function () {
    $('#biographyMore').click(function (event) {
      $('#biographyMore').hide();
      $('.summary').hide();
      $('#biographyLess').show();
      $('.content').show();
      event.preventDefault();
    });
    $('#biographyLess').click(function (event) {
      $('#biographyLess').hide();
      $('.content').hide();
      $('#biographyMore').show();
      $('.summary').show();
      event.preventDefault();
    });
  }
});

$(document).ready(function () {
  view.getListeningCumulation();
  view.getListeningHistory('%Y');
  view.getTopAlbums('<?=$top_album_tag?>');
  view.getTopArtists('<?=$top_artist_tag?>');
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
  view.initTagEvents();

  var update_bio = <?=($update_bio === true) ? 1 : 0?>;
  if (update_bio === 1) {
    view.updateBio();
  }
});