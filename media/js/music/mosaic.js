$.extend(view, {
  getRecentListenings: isFirst => {
    if (isFirst !== true) {
      $('#recentMosaicLoader2').show();
    }
    $.ajax({
      data: {
        limit: 102,
        sub_group_by: 'album',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          const today = new Date();
          $.ajax({
            data: {
              json_data: data,
              type: 'recent'
            },
            success: data => {
              $('#recentMosaicLoader, #recentMosaicLoader2').hide();
              $('#recentMosaic').html(data);
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              $('#recentlyUpdated').html(`updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`);
              $('#recentlyUpdated').attr('value', today.getTime());
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          $('#recentMosaicLoader').hide();
          $('#recentMosaic').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  initRecentEvents: () => {
    $('#refreshRecentAlbums').click(() => {
      view.getRecentListenings();
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getRecentListenings(true);
  view.initRecentEvents();
});
