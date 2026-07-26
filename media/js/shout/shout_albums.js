Object.assign(view, {
  getAlbumShouts: () => {
    ajax({
      data: {
        limit: 100,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              size: 32
            },
            success: data => {
              document.querySelector('#shoutLoader').classList.add('hidden');
              document.querySelector('#shout').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/album'
    });
  },
  getShoutUsers: () => {
    ajax({
      data: {
        limit: 20,
        type: 'album'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32,
              term: 'shouts'
            },
            success: data => {
              document.querySelector('#shoutersLoader').classList.add('hidden');
              document.querySelector('#shouters').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/users'
    });
  },
  initShoutEvents: () => {}
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getAlbumShouts();
view.getShoutUsers();
view.initShoutEvents();
