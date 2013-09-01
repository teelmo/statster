$(document).ready(function() {
  recentlyListened();
  topArtist();
});

function recentlyListened() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/listening/get',
    data: {
      limit:5,
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/sideTable',
          data: {
            json_data:data,
            size:32,
            hide : {
              artist:true,
              count:true,
              rank:true,
              calendar:true
            },
          },
          success: function(data) {
            $('#recentlyListenedLoader').hide();
            $('#recentlyListened').html(data);
          }
        });
      }
    }
  });
}

function topArtist() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/artist/get',
    data: {
      lower_limit:'1970-01-01',
      limit:15,
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/barTable',
          data: {
            json_data:data,
          },
          success: function(data) {
            $('#topArtistLoader').hide();
            $('#topArtist').html(data);
          }
        });
      }
    }
  });
}
