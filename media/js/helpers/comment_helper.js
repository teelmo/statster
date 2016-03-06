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
    $('#commentSubmit').click(function () {
      var text_value = $('#commentText').val();
      if (text_value === '') {
        return false;
      }
      $('#commentLoader2').show();
      $('#commentText').val('');
      $.ajax({
        data:{
          content_id:$('#contentID').val(),
          text:text_value,
          type:$('#contentType').val()
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            $('#commentLoader2').hide();
            view.getComments();
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#commentLoader2').hide();
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#commentLoader2').hide();
          },
          404: function () { // 404 Not found
            alert('404 Not Found');
            $('#commentLoader2').hide();
          }
        },
        type:'POST',
        url:'/api/comment/add'
      });
    });
  }
});

$(document).ready(function() {
  view.commentEvents();
});