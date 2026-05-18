$.extend(view, {
  recentlyLoved: () => {
    $.ajax({
      data: {
        limit: 100,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: {
                rank: true
              },
              json_data: data
            },
            success: data => {
              $('#recentlyLovedLoader').hide();
              $('#recentlyLoved').html(data);
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/love/get'
    });
  },
  topLoved: () => {
    $.ajax({
      data: {
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data,
              limit: 9,
              rank: 1,
              size: 32
            },
            success: data => {
              $('#topLovedLoader').hide();
              $('#topLoved').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        }
      },
      type: 'GET',
      url: '/api/love/get/top'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.recentlyLoved();
  view.topLoved();
});
