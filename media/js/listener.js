$.extend(view, {
  topListeners: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listener/get',
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:100
      },
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/userTable',
            data:{
              json_data:data,
              size:32,
              hide:{
                calendar:true,
                date:true
              }
            },
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            }
          });
        }
      }
    });
  },
  getListenings: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:14
      },
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>',
            data:{
              json_data:data,
              size:32,
              hide:{
                artist:true,
                count:true,
                rank:true
              }
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            }
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
      }
    });
  }
});

$(document).ready(function() {
  view.topListeners();
  view.getListenings();
});
