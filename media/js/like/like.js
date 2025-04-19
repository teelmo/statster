$.extend(view, {
  topLoved: function () {
    $.ajax({
      data:{
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album',
              word:'loves'
            },
            success: function (data) {
              $('#topLovedLoader').hide();
              $('#topLoved').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        }
      },
      type:'GET',
      url:'/api/love/get/top'
    });
  },
  topFaned: function () {
    $.ajax({
      data:{
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'artist',
              word:'fans'
            },
            success: function (data) {
              $('#topFanedLoader').hide();
              $('#topFaned').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        }
      },
      type:'GET',
      url:'/api/fan/get/top'
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
              $('#recentlyLovedLoader').hide();
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
              $('#recentlyFanedLoader').hide();
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
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.topLoved();
  view.topFaned();
  view.recentlyLoved();
  view.recentlyFaned();
});