var view = {
  // Get album love.
  getLove: function (user_id) { 
    if (user_id === undefined) {
      $('#loveLoader').hide();
      return;
    }
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/love/get/<?=$album_id?>',
      data: {
        user_id:user_id
      },
      statusCode: {
        200: function (data) { // 200 OK
          $('#love').addClass('loveDel');
        },
        204: function () { // 204 No Content
          $('#love').addClass('loveAdd');
        },
        400: function (data) {
          alert('400 Bad Request')
        }
      },
      complete: function () {
        $('#loveLoader').hide();
        $('#love').click(function() {
          $('.loveMsg').remove();
          if ($(this).hasClass('loveAdd')) {
            $.ajax({
              type:'POST',
              url:'/api/love/add/<?=$album_id?>',
              data: {},
              statusCode: {
                201: function(data) { // 201 Created
                  $('#love').removeClass('loveAdd').addClass('loveDel').prepend('<span class="loveMsg">You\'re in love!</span>');
                  setTimeout(function() {
                    $('.loveMsg').fadeOut('slow');
                  }, <?=MSG_FADEOUT?>);
                  getLoves();
                },
                400: function(data) {alert('400 Bad Request')},
                401: function(data) {alert('401 Unauthorized')},
                404: function(data) {alert('404 Not Found')}
              }
            });
          }
          if ($(this).hasClass('loveDel')) {
            $.ajax({
              type:'DELETE',
              url:'/api/love/delete/<?=$album_id?>',
              data:{},
              statusCode: {
                204: function () { // 204 No Content
                  $('#love').removeClass('loveDel').addClass('loveAdd').prepend('<span class="loveMsg">You\'re no longer in love!</span>');
                  setTimeout(function() {
                    $('.loveMsg').fadeOut('slow');
                  }, <?=MSG_FADEOUT?>);
                  getLoves();
                },
                400: function (data) {alert('400 Bad Request')},
                401: function (data) {alert('401 Unauthorized')},
                404: function (data) {alert('404 Not Found')}
              }
            });
          }
        });
      }
    });
  },
  // Get album loves.
  getLoves: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/love/get/<?=$album_id?>',
      data: {},
      statusCode: {
        200: function(data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/likeList',
            data: {
              json_data:data,
              hide: {}
            },
            success: function(data) {
              $('#albumLoveLoader').hide();
              $('#albumLove').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          $('#albumLoveLoader').hide();
          $('#albumLove').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      }
    });
  },
  // Get album listeners.
  getUsers: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/user/get',
      data: {
        limit:6,
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
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_BAD_REQUEST?>');
        }
      }
    });
  },
  // Get album listenings.
  getListenings: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data: {
        limit:6,
        artist_name:'<?php echo $artist_name?>',
        album_name:'<?php echo $album_name?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode: {
        200: function (data) { // 200 OK
          $.ajax({
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
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      }
    });
  }
}

$(document).ready(function () {
  view.getLove(<?=$this->session->userdata('user_id')?>);
  view.getLoves();
  view.getUsers();
  view.getListenings();
  
  $('#moretags').click(function() {
    $('#tagAdd').toggle();
    if ($(this).text() == '+') {
      $(this).html('<a href="javascript:;">-</a>');
    }
    else {
      $(this).html('<a href="javascript:;">+</a>');
    }
  });
  $('#tagAddSelect').chosen();
  $('#tagAdd').hide();
});
