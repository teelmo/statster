$.extend(view, {
  getBulletins: () => {
    if (user_id === undefined) {
      $('#bulletinLoader').hide();
      return;
    }
    $.ajax({
      complete: () => {
        $('#bulletinLoader').hide();
      },
      data: {
        user_id: user_id
      },
      dataType: 'json',
      statusCode: {
        200: () => {
          // 200 OK
          $('#love').addClass('love_del');
        },
        204: () => {
          // 204 No Content
          $('#love').addClass('love_add');
        },
        400: () => {
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/love/get/<?=$album_id?>'
    });
  },
  initInboxEvents: () => {}
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.initInboxEvents();
});
