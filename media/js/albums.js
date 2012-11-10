function topAlbum10() {
  jQuery.ajax({
    type:'POST',
    url:'/api/album/get',
    data: {
      limit:8,
      lower_limit:'1970-01-01',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumList/124',
        data: {
          json_data:data
        },
        success: function(data) {
          jQuery('#topAlbum10Loader').hide();
          jQuery('#topAlbum10').html(data);
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
topAlbum10();

function topAlbum() {
  jQuery.ajax({
    type:'POST',
    url:'/api/album/get',
    data: {
      limit:'8, 200',
      lower_limit:'1970-01-01',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumBar',
        data: {
          json_data:data,
          size:32,
          rank:9
        },
        success: function(data) {
          jQuery('#topAlbumLoader').hide();
          jQuery('#topAlbum').html(data);
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
topAlbum();