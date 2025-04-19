$.extend(view, {
  recentlyLoved: function () {
    $.ajax({
      data:{
        limit:100,
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
              limit:9,
              rank:1,
              size:32
            },
            success: function (data) {
              $('#topLovedLoader').hide();
              $('#topLoved').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/love/get/top'
    });
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.recentlyLoved();
  view.topLoved();
});