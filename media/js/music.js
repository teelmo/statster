function popularGenre() {
  jQuery.ajax({
    type: 'POST', url: '/api/topGenre',
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
          jQuery('#popularGenreLoader').hide();
          jQuery('#popularGenre').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
popularGenre();

function topAlbum() {
  jQuery.ajax({
    type: 'POST', url: '/api/topAlbum',
    data: {
      limit : 15,
      lower_limit : '<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
      upper_limit : '<?=date("Y-m-d")?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumTable',
        data: {
          json_data : data,
          hide : {"count" : true, "rank": true},
        },
        success: function(data) {
          jQuery('#popularAlbumLoader').hide();
          jQuery('#popularAlbum').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
topAlbum();