function getListenings() {
  jQuery.ajax({
    type:'GET',
    url:'/api/listening/get',
    data: {
      limit:100,
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
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
            jQuery('#recentlyListenedLoader').hide();
            jQuery('#recentlyListened').html(data);
            jQuery('div.confirmation').hide();
            jQuery('span.delete').click(function() {
              jQuery(this).removeClass('delete');
              jQuery('div.confirmation[for="' + jQuery(this).attr('id') + '"]').show();
              jQuery('a.cancel').click(function() {
                jQuery('#' + jQuery(this).attr('for')).addClass('delete');
                jQuery(this).closest('div').hide();
              });
              jQuery('a.confirm').click(function() {
                jQuery.ajax({
                  type:'DELETE',
                  url:'/api/listening/delete/' + jQuery(this).attr('data-listening-id'),
                  statusCode: {
                    204: function() { // 204 No Content
                      // Remove the tr
                      console.log($(this))
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
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function() { // 400 Bad request
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
      }
    }
  });
}
getListenings();