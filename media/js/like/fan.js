$.extend(view, {
  recentlyFaned: () => {
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
              $('#recentlyFanedLoader').hide();
              $('#recentlyFaned').html(data);
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get'
    });
  },
  topFaned: () => {
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
              $('#topFanedLoader').hide();
              $('#topFaned').html(data);
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get/top'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.recentlyFaned();
  view.topFaned();
});
