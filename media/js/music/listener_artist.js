$.extend(view, {
  // Get artist tags.
  getTags: function () {
    $.ajax({
      data:{
        artist_id:<?=$artist_id?>,
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              delete:false,
              json_data:data,
              logged_in:<?=$logged_in?>
            },
            success: function (data) {
              $('#tagsLoader').hide();
              $('#tags').html(data);
            },
            type:'POST',
            url:'/ajax/tagList'
          });
        },
        204: function () { // 204 No Content
          $('#tagsLoader').hide();
          $('#tags').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#tagsLoader').hide();
          $('#tags').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/tag/get/artist'
    });
  },
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
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/fan/get/<?=$artist_id?>'
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
          alert('<?=ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/fan/get/<?=$artist_id?>'
    });
  },
  topListeners: function () {
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        limit:100
      },
      dataType:'json',
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32
            },
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  getListenings: function () {
    $.ajax({
      data:{
        artist_name:'<?=$artist_name?>',
        limit:14
      },
      dataType:'json',
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true
              },
              json_data:data,
              size:32
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>'
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
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  initListenerArtistEvents: function () {
    $('html').on('click', '#fan', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('fan_add')) {
        $.ajax({
          data:{},
          statusCode:{
            201: function (data) { // 201 Created
              $('#fan').removeClass('fan_add').addClass('fan_del').find('.like_msg').html('You\'re a fan!').show();
              setTimeout(function () {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getFans();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'POST',
          url:'/api/fan/add/<?=$artist_id?>'
        });
      }
      if ($(this).hasClass('fan_del')) {
        $.ajax({
          data:{},
          statusCode:{
            204: function () { // 204 No Content
              $('#fan').removeClass('fan_del').addClass('fan_add').find('.like_msg').html('Unfaned.').show();
              setTimeout(function () {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getFans();
            },
            400: function () { // 400 Bad request
              alert('<?=ERR_BAD_REQUEST?>');
            },
            401: function () {
              alert('401 Unauthorized');
            },
            404: function () {
              alert('404 Not Found');
            }
          },
          type:'DELETE',
          url:'/api/fan/delete/<?=$artist_id?>'
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.when(
        $.each($('.chosen-select').val(), function (i, el) {
          var tag = el.split(':');
          $.ajax({
            data:{
              artist_id:<?=$artist_id?>,
              tag_id:tag[1],
              type:'artist'
            },
            statusCode:{
              201: function (data) { // 201 Created
              },
              400: function () { // 400 Bad request
                alert('<?=ERR_BAD_REQUEST?>');
              },
              401: function () {
                alert('401 Unauthorized');
              },
              404: function () {
                alert('404 Not Found');
              }
            },
            type:'POST',
            url:'/api/tag/add/' + tag[0]
          });
        })
      ).done(function () {
        $('.chosen-select option').removeAttr('selected');
        $('#tagAdd select').trigger('chosen:updated');
        view.getTags();
      });
      $('#tagAdd').hide();
    });
  }
});

$(document).ready(function() {
  view.getFan(<?=$this->session->userdata('user_id')?>);
  view.getFans();
  view.getTags();
  view.topListeners();
  view.getListenings();
  view.initListenerArtistEvents();
});
