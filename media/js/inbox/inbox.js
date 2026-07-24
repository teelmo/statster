Object.assign(view, {
  getBulletins: () => {
    if (user_id === undefined) {
      document.querySelector('#bulletinLoader').style.display = 'none';
      return;
    }
    ajax({
      complete: () => {
        document.querySelector('#bulletinLoader').style.display = 'none';
      },
      data: {
        user_id: user_id
      },
      dataType: 'json',
      statusCode: {
        200: () => {
          // 200 OK
          document.querySelector('#love').classList.add('love_del');
        },
        204: () => {
          // 204 No Content
          document.querySelector('#love').classList.add('love_add');
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

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initInboxEvents();
