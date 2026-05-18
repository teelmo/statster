$.extend(view, {
  shoutEvents: () => {
    $('html body').on('click', 'span.delete', function () {
      $($(this).data('confirmation-container')).show();
    });
    $('html body').on('click', 'a.cancel', function () {
      $(this).closest('div').hide();
    });
    $('html body').on('click', 'a.confirm', function () {
      var row_id = $(this).data('row-id');
      $.ajax({
        statusCode: {
          200: () => {
            // 200 OK
            $(`#${row_id}`).fadeOut('slow');
            var shout_total = parseInt($('#shoutTotal .number').text(), 10);
            shout_total--;
            if (shout_total > 0) {
              $('#shoutTotal .number').text(shout_total);
            } else {
              $('#shoutTotal').fadeOut(500);
            }
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
          }
        },
        type: 'POST',
        url: `/api/shout/delete/${$(this).data('shout-type')}/${$(this).data('shout-id')}`
      });
    });
    $('#shoutSubmit').click(() => {
      var text_value = $('#shoutText').val().trim();
      if (text_value === '') {
        return false;
      }
      $('#shoutLoader2').show();
      $('#shoutText').val('');
      $.ajax({
        data: {
          content_id: $('#contentID').val(),
          text: text_value,
          type: $('#contentType').val()
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            $('#shoutLoader2').hide();
            view.getShouts();
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            $('#shoutLoader2').hide();
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            $('#shoutLoader2').hide();
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
            $('#shoutLoader2').hide();
          }
        },
        type: 'POST',
        url: '/api/shout/add'
      });
    });
  }
});

$(document).ready(() => {
  view.shoutEvents();
});
