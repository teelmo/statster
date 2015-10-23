$.extend(view, {
  getSimilar: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/lastfm/getSimilar',
      data:{
        artist_name:'<?php echo $artist_name?>',
        limit:10
      },
      statusCode: {
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/artistList/124',
            data:{
              json_data:data,
              hide:{
                count:true
              }
            },
            success: function (data) {
              $('#similarArtistLoader').hide();
              $('#similarArtist').html(data);
            }
          });
        }
      }
    });
  },
  getEvents: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/lastfm/getEvents',
      data:{
        artist_name:'<?php echo $artist_name?>',
        limit:15
      },
      statusCode: {
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/eventTable',
            data:{
              json_data:data
            },
            success: function (data) {
              $('#artistEventLoader').hide();
              $('#artistEvent').html(data);
            }
          });
        }
      }
    });
  },
  getBio: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/lastfm/getBio',
      data:{
        artist_name:'<?php echo $artist_name?>',
        limit:4
      },
      statusCode: {
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/artistBio',
            data:{
              json_data:data,
              hide : {
                count:true
              }
            },
            success: function (data) {
              $('#artistBioLoader').hide();
              $('#artistBio').html(data);
            },
            complete: function () {
              $('#biographyMore').click(function () {
                $('#biographyMore').hide();
                $('.summary').hide();
                $('#biographyLess').show();
                $('.content').show();
              });
              $('#biographyLess').click(function () {
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
});

$(document).ready(function () {
  view.getSimilar();
  // view.getEvents();
  view.getBio();
});

