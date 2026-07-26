Object.assign(view, {
  getRecentListenings: isFirst => {
    if (isFirst !== true) {
      const loader = document.querySelector('#recentMosaicLoader2');
      loader.style.display = '';
      loader.classList.remove('hidden');
    }
    ajax({
      data: {
        limit: 102,
        sub_group_by: 'album',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          const today = new Date();
          ajax({
            data: {
              json_data: data,
              type: 'recent'
            },
            success: data => {
              document.querySelectorAll('#recentMosaicLoader, #recentMosaicLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#recentMosaic').innerHTML = data;
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              document.querySelector('#recentlyUpdated').innerHTML = `updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`;
              document.querySelector('#recentlyUpdated').setAttribute('value', today.getTime());
            },
            type: 'POST',
            url: '/ajax/mosaic'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentMosaicLoader').style.display = 'none';
          document.querySelector('#recentMosaic').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  initRecentEvents: () => {
    document.querySelector('#refreshRecentAlbums').addEventListener('click', () => {
      view.getRecentListenings();
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getRecentListenings(true);
view.initRecentEvents();
