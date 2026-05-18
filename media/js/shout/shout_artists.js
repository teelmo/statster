$.extend(view, {
  getArtistShouts: () => {
    $.ajax({
      data: {
        limit: 100,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: 32
            },
            success: data => {
              $('#shoutLoader').hide();
              $('#shout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/artist'
    });
  },
  getShoutUsers: () => {
    $.ajax({
      data: {
        limit: 20,
        type: 'artist'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
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
              $('#shoutersLoader').hide();
              $('#shouters').html(data);
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

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getArtistShouts();
  view.getShoutUsers();
  view.initShoutEvents();
});
