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
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/albumTable',
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
        },
        complete: function() {
          setTimeout(recentlyListened, 60*10*1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
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
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/artistBar',
        data: {
          json_data:data,
        },
        success: function(data) {
          $('#topArtistLoader').hide();
          $('#topArtist').html(data);
        },
        complete: function() {
          setTimeout(topArtist, 60*10*1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
