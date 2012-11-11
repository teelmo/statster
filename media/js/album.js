function getLove(user_id) { 
  if (user_id === undefined) {
    jQuery('#loveLoader').hide();
    return;
  }
  jQuery.ajax({
    type:'GET',
    url:'/api/love/get/<?=$album_id?>',
    data: {
      user_id:user_id
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery('#love').addClass('loveDel');
      },
      204: function() { // 204 No Content
        jQuery('#love').addClass('loveAdd');
      },
      400: function(data) {alert('400 Bad Request')}
    },
    complete: function() {
      jQuery('#loveLoader').hide();
      jQuery('.loveAdd').click(function() {
        jQuery.ajax({
          type:'POST',
          url:'/api/love/add',
          data: {
            album_id:<?=$album_id?>
          },
          statusCode: {
            201: function(data) { // 201 Created
              jQuery('#love').removeClass('loveAdd').addClass('loveDel').prepend('<span class="loveMsg">You\'re in love!</span>');
              setTimeout(function() {
                jQuery('.loveMsg').fadeOut();
              }, 3000);
              getLoves();
            },
            400: function(data) {alert('400 Bad Request')},
            401: function(data) {alert('401 Unauthorized')},
            404: function(data) {alert('404 Not Found')}
          }
        });
      });
      jQuery('.loveDel').click(function() {
        jQuery.ajax({
          type:'DELETE',
          url:'/api/love/delete',
          data: {
            album_id:<?=$album_id?>
          },
          statusCode: {
            204: function() { // 204 No Content
              jQuery('#love').removeClass('loveDel').addClass('loveAdd').prepend('<span class="loveMsg">You\'re no longer in love!</span>');
              setTimeout(function() {
                jQuery('.loveMsg').fadeOut();
              }, 3000);
              artistFan();
            },
            400: function(data) {alert('400 Bad Request')},
            401: function(data) {alert('401 Unauthorized')},
            404: function(data) {alert('404 Not Found')}
          }
        });
      });
    }
  });
}
getLove(<?=$this->session->userdata('user_id')?>);

function getLoves() {
  jQuery.ajax({
    type:'GET',
    url:'/api/love/get/<?=$album_id?>',
    data: {},
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
          type:'POST',
          url:'/ajax/albumLove',
          data: {
            json_data:data
          },
          success: function(data) {
            jQuery('#albumLoveLoader').hide();
            jQuery('#albumLove').html(data);
          }
        });
      },
      204: function() { // 204 No Content
        jQuery('#albumLoveLoader').hide();
        jQuery('#albumLove').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getLoves();

function getUsers() {
  jQuery.ajax({
    type:'GET',
    url:'/api/user/get',
    data: {
      limit:6,
      artist_name:'<?=$artist_name?>',
      album_name:'<?php echo $album_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
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
            jQuery('#topListenerLoader').hide();
            jQuery('#topListener').html(data);
          }
        });
      },
      204: function() { // 204 No Content
        jQuery('#topListenerLoader').hide();
        jQuery('#topListener').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getUsers();

function getListenings() {
  jQuery.ajax({
    type:'GET',
    url:'/api/listening/get',
    data: {
      limit:6,
      artist_name:'<?php echo $artist_name?>',
      album_name:'<?php echo $album_name?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
          type:'POST',
          url:'/ajax/albumTable',
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
          }
        });
      },
      204: function() { // 204 No Content
        jQuery('#topListenerLoader').hide();
        jQuery('#topListener').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getListenings();