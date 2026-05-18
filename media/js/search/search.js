$.extend(view, {
  getSearchResults: () => {
    $.ajax({
      data: {
        q: '<?=$q?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#searchResultLoader').hide();
              $('#searchResult').html(data);
            },
            type: 'POST',
            url: '/ajax/searchList'
          });
        },
        204: () => {
          // 204 No Content
          $('#searchResultLoader').hide();
          $('#searchResult').html('');
        },
        400: () => {
          $('#searchResultLoader').hide();
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/search/Get/100/'
    });
  },
  initSearchEvents: () => {}
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getSearchResults();
  view.initSearchEvents();
});
