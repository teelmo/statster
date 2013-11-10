$(document).ready(function() {
  getListenings();
  getUsers();
});

function getListenings() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/listening/get',
    data: {
      limit:<?=($artist_name) ? 1000 : 100?>,
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        $.ajax({
          type:'POST',
          url:'/ajax/chartTable',
          data: {
            json_data:data,
            size:32,
            hide: {
              artist:true,
              count:true,
              rank:true
            }
          },
          success: function(data) {
            $('#recentlyListenedLoader').hide();
            $('#recentlyListened').html(data);
            $('div.confirmation').hide();
            $('span.delete').click(function() {
              $(this).removeClass('delete');
              $('div.confirmation[for="' + $(this).attr('id') + '"]').show();
              $('a.cancel').click(function() {
                $('#' + $(this).attr('for')).addClass('delete');
                $(this).closest('div').hide();
              });
              $('a.confirm').click(function() {
                var row_id = $(this).attr('data-row-id');
                if ($('#' + row_id).hasClass('justAdded')) {
                  $('tr').removeClass('justAddedRest');
                }
                $.ajax({
                  type:'DELETE',
                  url:'/api/listening/delete/' + $(this).attr('data-listening-id'),
                  statusCode: {
                    200: function() { // 200 OK
                      $('#' + row_id).fadeOut('slow');
                    },
                    400: function() { // 400 Bad Request
                      alert('400 Bad Request');
                    },
                    401: function() { // 403 Forbidden
                      alert('401 Unauthorized');
                    },
                    404: function() { // 404 Not found
                      alert('404 Not Found');
                    }
                  }
                });
              });
            });
          }
        });
      },
      204: function() { // 204 No Content
        $('#recentlyListenedLoader').hide();
        $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function() { // 400 Bad request
        $('#recentlyListenedLoader').hide();
        $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
      }
    }
  });
}

function getUsers() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/user/get',
    data: {
      limit:14,
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        $.ajax({
          type:'POST',
          url:'/ajax/userTable',
          data: {
            json_data:data,
            size:32,
            hide: {
              date:true,
              calendar:true
            }
          },
          success: function(data) {
            $('#topListenerLoader').hide();
            $('#topListener').html(data);
          }
        });
      },
      204: function() { // 204 No Content
        $('#topListenerLoader').hide();
        $('#topListener').html('<?=ERR_NO_RESULTS?>');
      },
      400: function() { // 400 Bad request
        $('#topListenerLoader').hide();
        $('#topListener').html('<?=ERR_BAD_REQUEST?>');
      }
    }
  });
}