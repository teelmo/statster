jQuery('.moretags').click(function() {
  jQuery('#tagAdd').toggle();
  if ($(this).text() == '+') {
    $(this).html('<a href="javascript:;">-</a> ');
  }
  else {
    $(this).html('<a href="javascript:;">+</a>');
  }
});
jQuery('#tagAddSelect').chosen();
jQuery('#tagAdd').hide();

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
      jQuery('#love').click(function() {
        jQuery('.loveMsg').remove();
        if (jQuery(this).hasClass('loveAdd')) {
          jQuery.ajax({
            type:'POST',
            url:'/api/love/add/<?=$album_id?>',
            data: {},
            statusCode: {
              201: function(data) { // 201 Created
                jQuery('#love').removeClass('loveAdd').addClass('loveDel').prepend('<span class="loveMsg">You\'re in love!</span>');
                setTimeout(function() {
                  jQuery('.loveMsg').fadeOut('slow');
                }, <?=MSG_FADEOUT?>);
                getLoves();
              },
              400: function(data) {alert('400 Bad Request')},
              401: function(data) {alert('401 Unauthorized')},
              404: function(data) {alert('404 Not Found')}
            }
          });
        }
        if (jQuery(this).hasClass('loveDel')) {
          jQuery.ajax({
            type:'DELETE',
            url:'/api/love/delete/<?=$album_id?>',
            data: {},
            statusCode: {
              204: function() { // 204 No Content
                jQuery('#love').removeClass('loveDel').addClass('loveAdd').prepend('<span class="loveMsg">You\'re no longer in love!</span>');
                setTimeout(function() {
                  jQuery('.loveMsg').fadeOut('slow');
                }, <?=MSG_FADEOUT?>);
                getLoves();
              },
              400: function(data) {alert('400 Bad Request')},
              401: function(data) {alert('401 Unauthorized')},
              404: function(data) {alert('404 Not Found')}
            }
          });
        }
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
      400: function() { // 400 Bad request
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
      }
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
      artist_name:'<?php echo $artist_name?>',
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
      400: function() { // 400 Bad request
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
      }
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
          url:'/ajax/userTable',
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