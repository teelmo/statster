function artistAlbum() {
  jQuery.ajax({
    type:'POST',
    url:'/api/artistAlbum',
    data: {
      artist_name:'<?php echo $artist_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumList',
        data: {
          json_data:data,
          size:32,
          rank:9
        },
        success: function(data) {
          jQuery('#artistAlbumLoader').hide();
          jQuery('#artistAlbum').html(data);
        },
        complete: function() {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
artistAlbum();