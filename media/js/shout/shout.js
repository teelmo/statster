$.extend(view, {
  getAlbumShouts: size => {
    $.ajax({
      data: {
        limit: 33,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#albumShout').html(data);
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
  getArtistShouts: size => {
    $.ajax({
      data: {
        limit: 33,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#artistShout').html(data);
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
  getUserShouts: size => {
    $.ajax({
      data: {
        limit: 33,
        type: 'user',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              size: size
            },
            success: data => {
              $('#userShout').html(data);
            },
            type: 'POST',
            url: '/ajax/shoutTable'
          });
        }
      },
      type: 'GET',
      url: '/api/shout/get/user'
    });
  },
  getShoutUsers: () => {
    $.ajax({
      data: {
        limit: 20
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
  initShoutEvents: () => {
    $(document).one('ajaxStop', (_event, _request, _settings) => {
      $('#shout').append(
        $('.shouts tr')
          .detach()
          .sort((a, b) => app.compareStrings($(a).data('created'), $(b).data('created')))
      );
      $('#shoutLoader').hide();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  var size = 32;
  view.getAlbumShouts(size);
  view.getArtistShouts(size);
  view.getUserShouts(size);
  view.getShoutUsers(size);
  view.initShoutEvents();
});
