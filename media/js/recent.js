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
            jQuery('span.delete').click(function() {
              jQuery(this).removeClass('delete');
              jQuery(this).after('<div class="confirmation"><span class="small">Are you sure: <a href="javascript:;" class="confirm">Ok</a> / <a href="javascript:;" class="cancel">Cancel</div></a></span>');
              jQuery('a.cancel').click(function() {
                jQuery(this).closest('div').remove();
              });
              jQuery('a.confirm').click(function() {
                jQuery(this).closest('div').remove();
              });
            });
          }
        });
      },
      204: function() { // 204 No Content
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getListenings();