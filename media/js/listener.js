$(document).ready(function() {
  topListeners();
});

function topListeners() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/user/get',
    data: {
      limit:100,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/albumTable',
        data: {
          json_data:data,
          size:32,
          hide: {
            date:true,
            calendar:true
          }
        },
        success: function(data) {
          $('#topListenerLoader').hide();
          $('#topListener').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
