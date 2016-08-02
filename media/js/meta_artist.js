$.extend(view, {
  // Get artist fan.
  getFan: function (user_id) {
    if (user_id === undefined) {
      $('#fanLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#fanLoader').hide(); 
      },
      data:{
        user_id:user_id
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $('#fan').addClass('fan_del');
        },
        204: function () { // 204 No Content
          $('#fan').addClass('fan_add');
        },
        400: function () { // 400 Bad request
          alert('<?php echo ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/fan/get/<?php echo $artist_id?>'
    });
  },
  // Get artist fans.
  getFans: function () {
    $.ajax({
      data:{},
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{},
              json_data:data
            },
            success: function (data) {
              $('#artistFanLoader').hide();
              $('#artistFan').html(data);
            },
            type:'POST',
            url:'/ajax/likeList'
          });
        },
        204: function () { // 204 No Content
          $('#artistFanLoader').hide();
          $('#artistFan').html('');
        },
        400: function () { // 400 Bad request
          $('#artistFanLoader').hide();
          alert('<?php echo ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/fan/get/<?php echo $artist_id?>'
    });
  },
  // Get album tags.
  getTags: function () {
    $.ajax({
      data:{
        artist_id:'<?php echo $artist_id?>',
        limit:100,
        sort:true
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                add:true
              },
              json_data:data,
              logged_in:'<?php echo $logged_in?>'
            },
            success: function (data) {
              $('#tagsLoader').hide();
              $('#tags').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#tagsLoader').hide();
          $('#tags').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#tagsLoader').hide();
          $('#tags').html('<?php echo ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/tag/get/artist'
    });
  },
  // Get artist listeners.
  getUsers: function () {
    $.ajax({
      data:{
        artist_name:'<?php echo $artist_name?>',
        limit:6
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?php echo ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  // Get artist listenings.
  getListenings: function () {
    $.ajax({
      data:{
        artist_name:'<?php echo $artist_name?>',
        limit:6,
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true,
                spotify:true
              },
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?php echo ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?php echo ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  }
});

$(document).ready(function () {
  view.getFan(<?php echo $this->session->userdata('user_id')?>);
  view.getFans();
  view.getTags();
  view.getUsers();
  view.getListenings();
});