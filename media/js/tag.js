$.extend(view, {
  getListeningHistory: function (type) {
    view.initChart();
    if (type == '%w') {
      var where = 'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'' + type + '\') IS NOT NULL';
    }
    else if (type == '%Y%m') {
      var where = 'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'%m\') != \'00\'';
    }
    else {
      var where = 'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'' + type + '\') != \'00\'';
    }
    $.ajax({
      data:{
        group_by:'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'' + type + '\')',
        limit:200,
        lower_limit:'1970-00-00',
        order_by:'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'' + type + '\') ASC',
        select:'DATE_FORMAT(<?php echo TBL_listening?>.`date`, \'' + type + '\') as `bar_date`',
        tag_id:'<?php echo $tag_id?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>',
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
            },
            type:'POST',
            url:'/ajax/musicBar'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      },
      type:'GET',
      url:'/api/tag/get/<?php echo strtolower($tag_type)?>'
    });
  },
  // Get top albums.
  getTopAlbums: function () {
    $.ajax({
      data:{
        limit:10,
        lower_limit:'1970-00-00',
        tag_id:'<?php echo $tag_id?>',
        tag_type:'<?php echo $tag_type?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?php echo ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  // Get top artists.
  getTopArtists: function () {
    $.ajax({
      data:{
        group_by:'`artist_id`',
        limit:10,
        lower_limit:'1970-00-00',
        order_by:'`count` DESC, <?php echo TBL_artist?>.`artist_name` ASC',
        tag_id:'<?php echo $tag_id?>',
        tag_type:'<?php echo $tag_type?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
            },
            success: function (data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/artistList/124'
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?php echo ERR_NO_RESULTS?>');
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
          $('#topListener').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?php echo ERR_BAD_REQUEST?>');
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
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>',
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
          $('#recentlyListened').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function (data) {
          alert('400 Bad Request')
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  updateGenreBio: function () {
    $.ajax({
      data:{
        tag_id:'<?php echo $tag_id?>',
        tag_name:'<?php echo $tag_name?>'
      },
      dataType:'json',
      type:'GET',
      url:'/api/genre/update/biography'
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
  view.getListeningHistory('%Y');
  view.getTopAlbums();
  view.getTopArtists();
  switch ('<?php echo $tag_type?>') {
    case 'genre':
      var from = '(SELECT <?php echo TBL_genres?>.`genre_id`, <?php echo TBL_genres?>.`album_id` FROM <?php echo TBL_genres?> GROUP BY <?php echo TBL_genres?>.`genre_id`, <?php echo TBL_genres?>.`album_id`) as <?php echo TBL_genres?>';
      var where = '<?php echo TBL_genres?>.`album_id` = <?php echo TBL_album?>.`id` AND <?php echo TBL_genres?>.`genre_id` = <?php echo $tag_id?>';
      break;
    case 'keyword':
      var from = '(SELECT <?php echo TBL_keywords?>.`keyword_id`, <?php echo TBL_keywords?>.`album_id` FROM <?php echo TBL_keywords?> GROUP BY <?php echo TBL_keywords?>.`keyword_id`, <?php echo TBL_keywords?>.`album_id`) as <?php echo TBL_keywords?>';
      var where = '<?php echo TBL_keywords?>.`album_id` = <?php echo TBL_album?>.`id` AND <?php echo TBL_keywords?>.`keyword_id` = <?php echo $tag_id?>';
      break;
    case 'nationality':
      var from = '(SELECT <?php echo TBL_nationalities?>.`nationality_id`, <?php echo TBL_nationalities?>.`album_id` FROM <?php echo TBL_nationalities?> GROUP BY <?php echo TBL_nationalities?>.`nationality_id`, <?php echo TBL_nationalities?>.`album_id`) as <?php echo TBL_nationalities?>';
      var where = '<?php echo TBL_nationalities?>.`album_id` = <?php echo TBL_album?>.`id` AND <?php echo TBL_nationalities?>.`nationality_id` = <?php echo $tag_id?>';
      break;
    case 'year':
      var from = ''; 
      var where = '<?php echo TBL_album?>.`year` = <?php echo $tag_id?>';
      break;
    default:
      var from = '';
      var where = '';
      break;
  }
  view.getUsers(from, where);
  view.getListenings(from, where);
  view.initTagEvents();

  var update_bio = <?php echo ($update_bio === true) ? 1 : 0?>;
  if (update_bio === 1) {
    view.updateGenreBio();
  }
});