$.extend(view, {
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    if (isFirst != true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      data:{
        limit:100,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              strlenght:50
            },
            success: function (data) {
              $('#recentlyListenedLoader2').hide();
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
              var currentTime = new Date();
              var hours = currentTime.getHours();
              var minutes = currentTime.getMinutes();
              if (minutes < 10) {
                minutes = '0' + minutes;
              }
              $('#recentlyUpdated').html('updated <span class="number">' + hours + '</span>:<span class="number">' + minutes + '</span>');
              $('#recentlyUpdated').attr('value', currentTime.getTime());
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
  initRecentEvents: function () {
    $('#refreshRecentAlbums').click(function () {
      view.getRecentListenings();
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
  view.getRecentListenings();
  view.getUsers();
  view.initRecentEvents();
});