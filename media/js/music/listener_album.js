$.extend(view, {
  // Get album tags.
  getTags: function () {
    $.ajax({
      data:{
        album_id:parseInt(<?=$album_id?>),
        limit:9
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              delete:true,
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
      url:'/api/tag/get/album'
    });
  },
  // Get album love.
  getLove: function (user_id) { 
    if (user_id === undefined) {
      $('#loveLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#loveLoader').hide();
      },
      data:{
        user_id:user_id
      },
      dataType:'json',
      statusCode:{
        200: function () { // 200 OK
          $('#love').addClass('love_del');
        },
        204: function () { // 204 No Content
          $('#love').addClass('love_add');
        },
        400: function () {
          alert('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/love/get/<?=$album_id?>'
    });
  },
  // Get album loves.
  getLoves: function () {
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
              $('#albumLoveLoader').hide();
              $('#albumLove').html(data);
            },
            type:'POST',
            url:'/ajax/likeList'
          });
        },
        204: function () { // 204 No Content
          $('#albumLoveLoader').hide();
          $('#albumLove').html('');
        },
        400: function () { // 400 Bad request
          $('#albumLoveLoader').hide();
          alert('<?=ERR_BAD_REQUEST?>')
        }
      },
      type:'GET',
      url:'/api/love/get/<?=$album_id?>'
    });
  },
  topListeners: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:100,
        sub_group_by:'album'
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
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:14,
        sub_group_by:'album'
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
  initListenerAlbumEvents: function () {
    $('html').on('click', '#love', function () {
      $('.like_msg').html('');
      if ($(this).hasClass('love_add')) {
        $.ajax({
          data:{},
          statusCode:{
            201: function (data) { // 201 Created
              $('#love').removeClass('love_add').addClass('love_del').find('.like_msg').html('You\'re in love!').show();
              setTimeout(function() {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
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
          url:'/api/love/add/<?=$album_id?>'
        });
      }
      if ($(this).hasClass('love_del')) {
        $.ajax({
          data:{},
          statusCode:{
            204: function () { // 204 No Content
              $('#love').removeClass('love_del').addClass('love_add').find('.like_msg').html('Unloved.').show();
              setTimeout(function() {
                $('.like_msg').fadeOut(1000);
              }, <?=MSG_FADEOUT?>);
              view.getLoves();
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
          url:'/api/love/delete/<?=$album_id?>'
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.when(
        $.each($('.chosen-select').val(), function (i, el) {
          var tag = el.split(':');
          $.ajax({
            data:{
              album_id:<?=$album_id?>,
              tag_id:tag[1],
              type:'album'
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
  view.getLove(parseInt(<?=$this->session->userdata('user_id')?>));
  view.getLoves();
  view.getTags();
  view.topListeners();
  view.getListenings();
  view.initListenerAlbumEvents();
});
