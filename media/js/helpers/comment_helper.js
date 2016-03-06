$.extend(view, {
  commentEvents: function () {
    $('html body').on('click', 'span.delete', function () {
      $($(this).data('confirmation-container')).show();
    });
    $('html body').on('click', 'a.cancel', function () {
      $(this).closest('div').hide();
    });
    $('html body').on('click', 'a.confirm', function () {
      var row_id = $(this).data('row-id');
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
        url:'/api/comment/delete/' + $(this).data('comment-type') + '/' + $(this).data('comment-id')
      });
    });
  }
});

$(document).ready(function() {
  view.commentEvents();
});