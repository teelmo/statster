$.extend(view, {
  getListeningHistory: function (type) {
    app.initChart();
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/tag/get/<?=strtolower($tag_type)?>',
      data:{
        group_by:type + '(<?=TBL_listening?>.`date`)',
        limit:100,
        lower_limit:'1970-00-00',
        order_by:type + '(<?=TBL_listening?>.`date`) ASC',
        select:type + '(<?=TBL_listening?>.`date`) as `bar_date`',
        tag_id:'<?=$tag_id?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
        where:(type == 'weekday') ? type + '(<?=TBL_listening?>.`date`) IS NOT NULL' : type + '(<?=TBL_listening?>.`date`) != \'00\''
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
        tag_type:'<?=$tag_type?>'
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
        limit:10,
        tag_id:'<?=$tag_id?>',
        tag_type:'<?=$tag_type?>',
        group_by:'`artist_id`',
        order_by:'`count` DESC, <?=TBL_artist?>.`artist_name` ASC'
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
  initTagEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getListeningHistory('month');
  view.getTopAlbums();
  view.getTopArtists();
});