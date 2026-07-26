Object.assign(view, {
  getSearchResults: () => {
    ajax({
      data: {
        q: '<?=$q?>'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#searchResultLoader').classList.add('hidden');
              document.querySelector('#searchResult').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/searchList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#searchResultLoader').classList.add('hidden');
          document.querySelector('#searchResult').innerHTML = '';
        },
        400: () => {
          document.querySelector('#searchResultLoader').classList.add('hidden');
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/search/Get/100/'
    });
  },
  initSearchEvents: () => {}
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getSearchResults();
view.initSearchEvents();
