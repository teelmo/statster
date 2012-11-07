function artistBio() {
  jQuery.ajax({
    type:'POST',
    url:'/api/artistBio',
    data: {
      limit:4,
      artist_name:'<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/artistBio',
        data: {
          json_data:data,
          hide : {
            count:true
          },
        },
        success: function(data) {
          jQuery('#artistBioLoader').hide();
          jQuery('#artistBio').html(data);
        },
        complete: function() {
          jQuery('#biographyMore').click(function() {
            jQuery('#biographyMore').hide();
            jQuery('.summary').hide();
            jQuery('#biographyLess').show();
            jQuery('.content').show();
          });
          jQuery('#biographyLess').click(function() {
            jQuery('#biographyLess').hide();
            jQuery('.content').hide();
            jQuery('#biographyMore').show();
            jQuery('.summary').show();
          });
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