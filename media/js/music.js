jQuery.ajax({
  type: 'GET', url: '/api/popularGenre',
  data: {
    limit : 15,
    lower_limit : '<?=date("Y-m-d", time() - (365 * 24 * 60 * 60))?>',
    upper_limit : '<?=date("Y-m-d")?>',
  },
  success: function(data) {
    jQuery.ajax({
      type: 'POST', url: '/ajax/popularGenre',
      data: {
        json_data : data,
      },
      success: function(data) {
        jQuery('#popularGenre').html(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      }
    });
  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
  }
});

jQuery.ajax({
  type: 'GET', url: '/api/topAlbum',
  data: {
    limit : 15,
    lower_limit : '<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
    upper_limit : '<?=date("Y-m-d")?>',
  },
  success: function(data) {
    jQuery.ajax({
      type: 'POST', url: '/ajax/popularAlbum',
      data: {
        json_data : data,
      },
      success: function(data) {
        jQuery('#popularAlbum').html(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      }
    });
  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
  }
});