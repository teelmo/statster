$.extend(view, {
  getTopArtist: (lower_limit, upper_limit = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data: {
        limit: 102,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              $('#artistMosaicLoader, #artistMosaicLoader2').hide();
              $('#artistMosaic').html(data);
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          $('#artistMosaicLoader, #artistMosaicLoader2').hide();
          $('#artistMosaic').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/artist/get'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getTopArtist('<?=$lower_limit?>');
});
