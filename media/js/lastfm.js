$.extend(view, {
  getSimilar: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/lastfm/fetchSimilar',
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
  }
});

$(document).ready(function () {
  view.getSimilar();
  // view.getEvents();
});

