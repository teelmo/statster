$.extend(view, {
  // Get artist fan.
  getFan: function (user_id) {
    if (user_id === undefined) {
      $('#fanLoader').hide();
      return;
    }
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/fan/get/<?=$artist_id?>',
      data:{
        user_id:user_id,
        hide:{
          title:true,
          username:true
        }
      },
      statusCode:{
        200: function (data) { // 200 OK
          $('#fan').addClass('fanDel');
        },
        204: function () { // 204 No Content
          $('#fan').addClass('fanAdd');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      complete: function () {
        $('#fanLoader').hide();
        $('#fan').click(function () {
          $('.fanMsg').remove();
          if ($(this).hasClass('fanAdd')) {
            $.ajax({
              type:'POST',
              url:'/api/fan/add/<?=$artist_id?>',
              data:{},
              statusCode:{
                201: function (data) { // 201 Created
                  $('#fan').removeClass('fanAdd').addClass('fanDel').prepend('<span class="fanMsg">You\'re a fan!</span>');
                  setTimeout(function () {
                    $('.fanMsg').fadeOut('slow');
                  }, <?=MSG_FADEOUT?>);
                  view.getFans();
                },
                400: function (data) {
                  alert('400 Bad Request')
                },
                401: function (data) {
                  alert('401 Unauthorized')
                },
                404: function (data) {
                  alert('404 Not Found')
                }
              }
            });
          }
          if ($(this).hasClass('fanDel')) {
            $.ajax({
              type:'DELETE',
              url:'/api/fan/delete/<?=$artist_id?>',
              data:{},
              statusCode:{
                204: function () { // 204 No Content
                  $('#fan').removeClass('fanDel').addClass('fanAdd').prepend('<span class="fanMsg">You\'re no longer a fan!</span>');
                  setTimeout(function () {
                    $('.fanMsg').fadeOut('slow');
                  }, <?=MSG_FADEOUT?>);
                  view.getFans();
                },
                400: function (data) {
                  alert('400 Bad Request')
                },
                401: function (data) {
                  alert('401 Unauthorized')
                },
                404: function (data) {
                  alert('404 Not Found')
                }
              }
            });
          }
        });
      }
    });
  },
  // Get artist fans.
  getFans: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/fan/get/<?=$artist_id?>',
      data:{},
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/likeList',
            data:{
              json_data:data,
              hide:{}
            },
            success: function (data) {
              $('#artistFanLoader').hide();
              $('#artistFan').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          $('#artistFanLoader').hide();
          $('#artistFan').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {
          alert('400 Bad Request')
        }
      }
    });
  },
  // Get artist listeners.
  getUsers: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/user/get',
      data:{
        limit:6,
        artist_name:'<?=$artist_name?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/userTable',
            data:{
              json_data:data,
              size:32,
              hide:{
                date:true,
                calendar:true
              }
            },
            success: function (data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      }
    });
  },
  // Get artist listenings.
  getListenings: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/listening/get',
      data:{
        limit:6,
        artist_name:'<?php echo $artist_name?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            type:'POST',
            url:'/ajax/sideTable',
            data:{
              json_data:data,
              size:32,
              hide:{
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
        400: function (data) {
          alert('400 Bad Request')
        }
      }
    });
  },
  initArtistEvents: function () {
    $('#moretags').click(function () {
      $('#tagAdd').toggle();
      $('#tagAddSelect').chosen();
      if ($(this).text() == '+') {
        $(this).html('<a href="javascript:;">-</a>');
        $('.search-field input[type="text"]').focus();
      }
      else {
        $(this).html('<a href="javascript:;">+</a>');
      }
    });
  }
});

$(document).ready(function () {
  view.getFan(<?=$this->session->userdata('user_id')?>);
  view.getFans();
  view.getUsers();
  view.getListenings();
  view.initArtistEvents();
  
  $('#tagAdd').hide();
});
