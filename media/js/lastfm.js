function getSimilar() {
  jQuery.ajax({
    type:'POST',
    url:'/api/lastfm/getSimilar',
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
getSimilar();

function getEvents() {
  jQuery.ajax({
    type:'POST',
    url:'/api/lastfm/getEvents',
    data: {
      limit:15,
      artist_name:'<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/eventTable',
        data: {
          json_data:data,
        },
        success: function(data) {
          jQuery('#artistEventLoader').hide();
          jQuery('#artistEvent').html(data);
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
getEvents();

function getBio() {
  jQuery.ajax({
    type:'POST',
    url:'/api/lastfm/getBio',
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
getBio();