function albumLove() {
  jQuery.ajax({
    type: 'POST', url: '/api/albumLove', 
    data: {
      album_id : <?=$album_id?>,
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumLove',
        data: {
          json_data : data
        },
        success: function(data) {
          jQuery('#albumLoveLoader').hide();
          jQuery('#albumLove').html(data);
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
albumLove();

function topListeners() {
  jQuery.ajax({
    type: 'POST', url: '/api/topListener', 
    data: {
      limit : 6,
      username : '<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
      artist_name : '<?php echo $artist_name?>',
      album_name : '<?php echo $album_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumTable',
        data: {
          json_data : data,
          hide : {'date' : true, 'calendar': true},
          img : 'user',
          title : 'user',
          size : 32
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

function recentlyListened() {
  jQuery.ajax({
    type: 'POST', url: '/api/recentlyListened', 
    data: {
      limit : 6,
      username : '<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>',
      artist_name : '<?php echo $artist_name?>',
      album_name : '<?php echo $album_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumTable',
        data: {
          json_data : data,
          hide : {"artist" : true, "count" : true, "rank" : true},
          title : 'music',
          img : 'user',
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