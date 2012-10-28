function albumLove() {
  jQuery.ajax({
    type: 'POST', url: '/api/albumLove', 
    data: {
      album_id : '<?=$album_id?>',
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