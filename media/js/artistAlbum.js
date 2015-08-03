$.extend(view, {
  artistAlbum: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artistAlbum',
      data:{
        artist_name:'<?php echo $artist_name?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/albumList',
            data:{
              json_data:data,
              rank:9,
              size:32
            },
            success: function(data) {
              $('#artistAlbumLoader').hide();
              $('#artistAlbum').html(data);
            }
          });
        }
      }
    });
  }
});

$(document).ready(function() {
  view.artistAlbum();
});