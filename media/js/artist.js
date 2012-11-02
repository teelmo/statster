function artistFan() {
  jQuery.ajax({
    type: 'POST', url: '/api/artistFan', 
    data: {
      artist_id : '<?=$artist_id?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/artistFan',
        data: {
          json_data : data
        },
        success: function(data) {
          jQuery('#artistFanLoader').hide();
          jQuery('#artistFan').html(data);
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
artistFan();

function recentlyListened() {
  jQuery.ajax({
    type: 'POST', url: '/api/recentlyListened', 
    data: {
      limit : 6,
      username : '<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
      artist : '<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumTable',
        data: {
          json_data : data,
          hide : {"artist" : true, "count" : true, "rank" : true},
          img : 'album',
          size : 32
        },
        success: function(data) {
          jQuery('#recentlyListenedLoader').hide();
          jQuery('#recentlyListened').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
recentlyListened();