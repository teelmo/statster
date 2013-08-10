$(document).ready(function() {
  topAlbum10();
  topAlbum();
});

function topAlbum10() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/album/get',
    data: {
      limit:8,
      lower_limit:'1970-01-01',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/albumList/124',
        data: {
          json_data:data
        },
        success: function(data) {
          $('#topAlbum10Loader').hide();
          $('#topAlbum10').html(data);
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

function topAlbum() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/album/get',
    data: {
      limit:'8, 200',
      lower_limit:'1970-01-01',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/barTable',
        data: {
          json_data:data,
          size:32,
          rank:9
        },
        success: function(data) {
          $('#topAlbumLoader').hide();
          $('#topAlbum').html(data);
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
