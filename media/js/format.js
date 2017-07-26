$.extend(view, {
  getFormats: function () {
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
            },
            success: function (data) {
              $('#topListeningFormatTypesLoader').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  },
  initFormatEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getFormats();
  view.initFormatEvents();
});
