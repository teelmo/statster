$.extend(view, {
  artistAlbum: function () {
    $.ajax({
      data:{
        artist_name:'<?php echo $artist_name?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              hide:{
                artist:true
              }
            },
            success: function(data) {
              $('#artistAlbumLoader').hide();
              $('#artistAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/albumList'
          });
        },
        204: function () {
          $('#artistAlbumLoader').hide();
          $('#artistAlbum').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/artistAlbum'
    });
  },
  artistAlbumEvents: function () {
    $('#biographyMore').click(function (event) {
      $('#biographyMore').hide();
      $('.summary').hide();
      $('#biographyLess').show();
      $('.content').fadeIn();
      event.preventDefault();
    });
    $('#biographyLess').click(function (event) {
      $('#biographyLess').hide();
      $('.content').hide();
      $('#biographyMore').show();
      $('.summary').show();
      event.preventDefault();
    });
  }
});

$(document).ready(function() {
  view.artistAlbum();
  view.artistAlbumEvents();
});