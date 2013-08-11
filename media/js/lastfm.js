$(document).ready(function() {
  getSimilar();
  getEvents();
  getBio();
});

function getSimilar() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/lastfm/getSimilar',
    data: {
      limit:4,
      artist_name:'<?php echo $artist_name?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/artistList/124',
          data: {
            json_data:data,
            hide: {
              count:true
            }
          },
          success: function(data) {
            $('#similarArtistLoader').hide();
            $('#similarArtist').html(data);
          }
        });
      }
    }
  });
}

function getEvents() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/lastfm/getEvents',
    data: {
      limit:15,
      artist_name:'<?php echo $artist_name?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/eventTable',
          data: {
            json_data:data
          },
          success: function(data) {
            $('#artistEventLoader').hide();
            $('#artistEvent').html(data);
          }
        });
      }
    }
  });
}

function getBio() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/lastfm/getBio',
    data: {
      limit:4,
      artist_name:'<?php echo $artist_name?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/artistBio',
          data: {
            json_data:data,
            hide : {
              count:true
            }
          },
          success: function(data) {
            $('#artistBioLoader').hide();
            $('#artistBio').html(data);
          },
          complete: function() {
            $('#biographyMore').click(function() {
              $('#biographyMore').hide();
              $('.summary').hide();
              $('#biographyLess').show();
              $('.content').show();
            });
            $('#biographyLess').click(function() {
              $('#biographyLess').hide();
              $('.content').hide();
              $('#biographyMore').show();
              $('.summary').show();
            });
          }
        });
      }
    }
  });
}
