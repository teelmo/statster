$.extend(view, {
  topListeners: function () {
    $.ajax({
      data:{
        limit:100,
        sub_group_by:'album'
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
              size:32,
              type:'user'
            },
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  getListenings: function () {
    $.ajax({
      data:{
        limit:10
      },
      dataType:'json',
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                calendar:true,
                count:true,
                rank:true,
                spotify:true
              },
              json_data:data,
              size:32
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>'
          });
        },
        204: function() { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function() { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  }
});

$(document).ready(function() {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.topListeners();
  view.getListenings();
});
