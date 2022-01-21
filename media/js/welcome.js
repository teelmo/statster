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
  topArtist: function () {
    $.ajax({
      data:{
        limit:5,
        lower_limit:'1970-00-00'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data,
            },
            success: function(data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/artist/get'
    });
  },
  initWelcomeEvents: function () {
    $('#toggleRegisterForm').click(function () {
      $('#registerForm').slideToggle();
    });
  }
});

$(document).ready(function() {
  view.recentlyListened();
  view.topArtist();
  view.initWelcomeEvents();
});
