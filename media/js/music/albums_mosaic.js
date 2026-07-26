Object.assign(view, {
  getTopAlbum: (lower_limit, upper_limit = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    ajax({
      data: {
        limit: 102,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              document.querySelectorAll('#albumMosaicLoader, #albumMosaicLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#albumMosaic').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#albumMosaicLoader, #albumMosaicLoader2').forEach(el => {
            el.classList.add('hidden');
          });
          document.querySelector('#albumMosaic').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getTopAlbum('<?=$lower_limit?>');
