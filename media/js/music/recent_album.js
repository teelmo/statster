$.extend(view, {
  // Get album tags.
  getTags: function () {
    $.ajax({
      data:{
        album_id:<?=$album_id?>,
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
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:<?=($artist_name) ? 1000 : 100?>,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          var today = new Date();
          $.ajax({
            data:{
              cur_date:today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2),
              json_data:data,
              strlenght:50,
              time:Math.floor((new Date().getTime() - new Date().getTimezoneOffset() * 60000) / 1000)
            },
            success: function (data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/musicTable'
          })
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  getUsers: function () {
    $.ajax({
      data:{
        album_name:'<?=$album_name?>',
        artist_name:'<?=$artist_name?>',
        limit:14
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
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  initRecentAlbumEvents: function () {
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
          type:'DELETE',
          url:'/api/love/delete/<?=$album_id?>'
        });
      }
    });
    $('html').on('click', '#submitTags', function () {
      $.when(
        $.each($('.chosen-select').val(), function (i, el) {
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
    $('html body').on('click', 'span.delete', function () {
      $($(this).data('confirmation-container')).show();
    });
    $('html body').on('click', 'a.cancel', function () {
      $(this).closest('div').hide();
    });
    $('html body').on('click', 'a.confirm', function () {
      var row_id = $(this).data('row-id');
      if ($('#' + row_id).hasClass('just_added')) {
        $('tr').removeClass('just_added_rest');
      }
      $.ajax({
        statusCode:{
          200: function () { // 200 OK
            $('#' + row_id).fadeOut('slow');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
          },
          401: function (data) { // 403 Forbidden
            alert('401 Unauthorized');
          },
          404: function () { // 404 Not found
            alert('404 Not Found');
          }
        },
        type:'DELETE',
        url:'/api/listening/delete/' + $(this).data('listening-id')
      });
    });
  }
});

$(document).ready(function () {
  view.getLove(<?=$this->session->userdata('user_id')?>);
  view.getLoves();
  view.getTags();
  view.getRecentListenings();
  view.getUsers();
  view.initRecentAlbumEvents();
});