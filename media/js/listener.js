function topListeners() {
  jQuery.ajax({
    type:'GET',
    url:'/api/user/get',
    data: {
      limit:100,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumTable',
        data: {
          json_data:data,
          hide: {
            date:true,
            calendar:true
          },
          size:32
        },
        success: function(data) {
          jQuery('#topListenerLoader').hide();
          jQuery('#topListener').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
topListeners();