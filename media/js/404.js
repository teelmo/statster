$.extend(view, {
  recentlyListened: function () {
    $.ajax({
      data:{
        limit:5,
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data,
              size:32,
              hide:{
                artist:true,
                calendar:true,
                count:true,
                rank:true,
                spotify:true
              },
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  init404Events: function () {

  }
});

$(document).ready(function() {
  view.recentlyListened();
  view.init404Events();
});
