Object.assign(view, {
  artistAlbum: order_by => {
    ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        order_by: order_by,
        username: `<?=!empty($_GET['u']) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              hide: {
                artist: true
              }
            },
            success: data => {
              document.querySelector('#artistAlbumLoader').style.display = 'none';
              document.querySelector('#discographyLoader').style.display = 'none';
              document.querySelector('#artistAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          document.querySelector('#artistAlbumLoader').style.display = 'none';
          document.querySelector('#artistAlbum').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/artistAlbum'
    });
  },
  associatedArtist: () => {
    ajax({
      data: {
        artist_id: `<?=(isset($artists)) ? implode(',', array_map(function($artist) { return $artist['artist_id'];}, $artists)) : $artist_id?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              hide: {
                count: true
              }
            },
            success: data => {
              document.querySelector('#associatedArtistLoader').style.display = 'none';
              document.querySelector('#associatedArtist').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/artistList'
          });
        },
        204: () => {
          document.querySelector('#associatedArtistLoader').style.display = 'none';
          document.querySelector('#associatedArtist').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/associatedArtist'
    });
  },
  artistAlbumEvents: () => {
    document.querySelector('#biographyMore').addEventListener('click', event => {
      document.querySelector('#biographyMore').style.display = 'none';
      document.querySelectorAll('.summary').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelector('#biographyLess').style.display = '';
      // Note: jQuery's fadeIn() animated this; plain display toggle drops
      // the animation but keeps the same end state.
      document.querySelectorAll('.content').forEach(el => {
        el.style.display = '';
      });
      event.preventDefault();
    });
    document.querySelector('#biographyLess').addEventListener('click', event => {
      document.querySelector('#biographyLess').style.display = 'none';
      document.querySelectorAll('.content').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelector('#biographyMore').style.display = '';
      document.querySelectorAll('.summary').forEach(el => {
        el.style.display = '';
      });
      event.preventDefault();
    });
  }
});

view.artistAlbum('<?=$artist_album?>');
view.associatedArtist();
view.artistAlbumEvents();
