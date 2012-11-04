function artistBio() {
  jQuery.ajax({
    type: 'POST', url: '/api/artistBio', 
    data: {
      limit : 4,
      artist_name : '<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/artistBio',
        data: {
          json_data : data,
          hide : {'count':true},
        },
        success: function(data) {
          jQuery('#artistBioLoader').hide();
          jQuery('#artistBio').html(data);
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
artistBio();