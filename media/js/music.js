$(document).ready(function() {
  popularGenre();
  topAlbum();
});

function popularGenre() {
  $.ajax({
    type:'GET',
    url:'/api/genre/get',
    data: {
      limit:15,
      lower_limit:'<?=date("Y-m-d", time() - (365 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/popularTag',
        data: {
          json_data:data
        },
        success: function(data) {
          $('#popularGenreLoader').hide();
          $('#popularGenre').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}

function topAlbum() {
  $.ajax({
    type:'GET',
    url:'/api/album/get',
    data: {
      limit:15,
      lower_limit:'<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/albumTable',
        data: {
          json_data:data,
          hide: {
            count:true,
            rank:true,
            date:true,
            calendar:true
          }
        },
        success: function(data) {
          $('#popularAlbumLoader').hide();
          $('#popularAlbum').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
