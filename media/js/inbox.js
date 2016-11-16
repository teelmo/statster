$.extend(view, {
  getBulletins: function () {
    if (user_id === undefined) {
      $('#bulletinLoader').hide();
      return;
    }
    $.ajax({
      complete: function () {
        $('#bulletinLoader').hide();
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
  initInboxEvents: function () {
    
  }
});

$(document).ready(function() {
  view.initInboxEvents();
});

