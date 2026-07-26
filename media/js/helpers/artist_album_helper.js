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
              document.querySelector('#artistAlbumLoader').classList.add('hidden');
              document.querySelector('#discographyLoader').classList.add('hidden');
              document.querySelector('#artistAlbum').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          document.querySelector('#artistAlbumLoader').classList.add('hidden');
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
              document.querySelector('#associatedArtistLoader').classList.add('hidden');
              document.querySelector('#associatedArtist').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/artistList'
          });
        },
        204: () => {
          document.querySelector('#associatedArtistLoader').classList.add('hidden');
          document.querySelector('#associatedArtist').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/associatedArtist'
    });
  },
  artistAlbumEvents: () => {
    // #biographyMore/#biographyLess only render when the artist/album has a
    // bio_summary - absent on pages with no biography text.
    var biographyMore = document.querySelector('#biographyMore');
    if (biographyMore) {
      biographyMore.addEventListener('click', event => {
        biographyMore.classList.add('hidden');
        document.querySelectorAll('.summary').forEach(el => {
          el.classList.add('hidden');
        });
        var biographyLess = document.querySelector('#biographyLess');
        biographyLess.classList.remove('hidden');
        document.querySelectorAll('.content').forEach(el => {
          el.classList.remove('hidden');
        });
        event.preventDefault();
      });
      document.querySelector('#biographyLess').addEventListener('click', event => {
        document.querySelector('#biographyLess').classList.add('hidden');
        document.querySelectorAll('.content').forEach(el => {
          el.classList.add('hidden');
        });
        biographyMore.classList.remove('hidden');
        document.querySelectorAll('.summary').forEach(el => {
          el.classList.remove('hidden');
        });
        event.preventDefault();
      });
    }
  }
});

view.artistAlbum('<?=$artist_album?>');
view.associatedArtist();
view.artistAlbumEvents();
