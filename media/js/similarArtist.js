function similarArtist() {
  jQuery.ajax({
    type:'POST',
    url:'/api/similarArtist',
    data: {
      limit:4,
      artist_name:'<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/artistList/124',
        data: {
          json_data:data,
          hide: {
            count:true
          },
        },
        success: function(data) {
          jQuery('#similarArtistLoader').hide();
          jQuery('#similarArtist').html(data);
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
similarArtist();