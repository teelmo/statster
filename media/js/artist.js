function getFan(user_id) {
  if (user_id === undefined) {
    jQuery('#fanLoader').hide();
    return;
  }
  jQuery.ajax({
    type:'GET',
    url:'/api/fan/get',
    data: {
      artist_id:<?=$artist_id?>,
      user_id:user_id
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery('#fan').addClass('fanDel');
      },
      204: function() { // 204 No Content
        jQuery('#fan').addClass('fanAdd');
      },
      400: function(data) {alert('400 Bad Request')}
    },
    complete: function() {
      jQuery('#fanLoader').hide();
      jQuery('.fanAdd').click(function() {
        jQuery.ajax({
          type:'POST',
          url:'/api/fan/add',
          data: {
            artist_id:<?=$artist_id?>
          },
          statusCode: {
            201: function(data) { // 201 Created
              jQuery('#fan').removeClass('fanAdd').addClass('fanDel').prepend('<span class="fanMsg">You\'re a fan!</span>');
              setTimeout(function() {
                jQuery('.fanMsg').fadeOut();
              }, 3000);
              getFans();
            },
            400: function(data) {alert('400 Bad Request')},
            401: function(data) {alert('401 Unauthorized')},
            404: function(data) {alert('404 Not Found')}
          }
        });
      });
      jQuery('.fanDel').click(function() {
        jQuery.ajax({
          type:'DELETE',
          url:'/api/fan/delete',
          data: {
            artist_id:<?=$artist_id?>
          },
          statusCode: {
            204: function() { // 204 No Content
              jQuery('#fan').removeClass('fanDel').addClass('fanAdd').prepend('<span class="fanMsg">You\'re no longer a fan!</span>');
              setTimeout(function() {
                jQuery('.fanMsg').fadeOut();
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
getFan(<?=$this->session->userdata('user_id')?>);

function getFans() {
  jQuery.ajax({
    type:'GET',
    url:'/api/fan/get',
    data: {
      artist_id:<?=$artist_id?>
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
          type:'POST',
          url:'/ajax/artistFan',
          data: {
            json_data:data
          },
          success: function(data) {
            jQuery('#artistFanLoader').hide();
            jQuery('#artistFan').html(data);
          }
        });
      },
      204: function() { // 204 No Content
        jQuery('#artistFanLoader').hide();
        jQuery('#artistFan').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getFans();

function getUsers() {
  jQuery.ajax({
    type:'GET',
    url:'/api/user/get',
    data: {
      limit:6,
      artist_name:'<?=$artist_name?>',
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
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    }
  });
}
getListenings();