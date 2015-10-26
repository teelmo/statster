$.extend(view, {
  recentlyListened: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data:{
        limit:5,
      },
      statusCode:{
        200: function(data) {
          $.ajax({
            type:'POST',
            url:'/ajax/sideTable',
            data:{
              json_data:data,
              size:32,
              hide:{
                artist:true,
                calendar:true,
                count:true,
                rank:true
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
  },
  topArtist: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/artist/get',
      data:{
        limit:15,
        lower_limit:'1970-01-01'
      },
      statusCode:{
        200: function(data) {
          $.ajax({
            type:'POST',url:'/ajax/columnTable',
            data:{
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
});

$(document).ready(function() {
  view.recentlyListened();
  view.topArtist();
});
