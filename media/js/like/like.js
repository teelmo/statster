Object.assign(view, {
  topLoved: () => {
    ajax({
      data: {
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'album',
              word: 'loves'
            },
            success: data => {
              document.querySelector('#topLovedLoader').style.display = 'none';
              document.querySelector('#topLoved').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        }
      },
      type: 'GET',
      url: '/api/love/get/top'
    });
  },
  topFaned: () => {
    ajax({
      data: {
        limit: 9
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'artist',
              word: 'fans'
            },
            success: data => {
              document.querySelector('#topFanedLoader').style.display = 'none';
              document.querySelector('#topFaned').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get/top'
    });
  },
  recentlyLoved: () => {
    ajax({
      data: {
        limit: 10,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              hide: {
                rank: true
              },
              json_data: data
            },
            success: data => {
              document.querySelector('#recentlyLovedLoader').style.display = 'none';
              document.querySelector('#recentlyLoved').innerHTML = data;
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
  recentlyFaned: () => {
    ajax({
      data: {
        limit: 10,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              hide: {
                rank: true
              },
              json_data: data
            },
            success: data => {
              document.querySelector('#recentlyFanedLoader').style.display = 'none';
              document.querySelector('#recentlyFaned').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/likeTable'
          });
        }
      },
      type: 'GET',
      url: '/api/fan/get'
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.topLoved();
view.topFaned();
view.recentlyLoved();
view.recentlyFaned();
