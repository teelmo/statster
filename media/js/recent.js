function recentlyListened() {
  jQuery.ajax({
    type: 'POST', url: '/api/recentlyListened', 
    data: {
      limit : 100,
      username : '<?php echo (!empty($username)) ? $username : FALSE;?>',
      artist : '<?php echo (!empty($artist)) ? $artist : FALSE;?>',
      album : '<?php echo (!empty($album)) ? $album : FALSE;?>',
      date : '<?php echo (!empty($date)) ? $date : FALSE;?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/recentlyListened',
        data: {
          json_data : data,
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