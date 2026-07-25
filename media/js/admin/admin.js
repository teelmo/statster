Object.assign(view, {
  // Init Edit album events.
  initAdminEvents: () => {
    document.querySelector('#addArtistSubmit').addEventListener('click', event => {
      var name = document.querySelector('#addArtistText').value;
      if (name === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          artist_name: name
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            alert(`Artist ${name} added!`);
            document.querySelector('#addArtistText').value = '';
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            document.querySelector('#addArtistText').value = '';
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            document.querySelector('#addArtistText').value = '';
          }
        },
        type: 'POST',
        url: '/api/artist/add'
      });
      event.preventDefault();
    });
    document.querySelector('#addGenreSubmit').addEventListener('click', event => {
      var name = document.querySelector('#addGenreText').value;
      if (name === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          name: name
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            alert(`Genre ${name} added!`);
            document.querySelector('#addGenreText').value = '';
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            document.querySelector('#addGenreText').value = '';
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            document.querySelector('#addGenreText').value = '';
          }
        },
        type: 'POST',
        url: '/api/genre/add'
      });
      event.preventDefault();
    });
    document.querySelector('#addKeywordSubmit').addEventListener('click', event => {
      var name = document.querySelector('#addKeywordText').value;
      if (name === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          name: name
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            alert(`Keyword ${name} added!`);
            document.querySelector('#addKeywordText').value = '';
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            document.querySelector('#addGenreText').value = '';
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            document.querySelector('#addGenreText').value = '';
          }
        },
        type: 'POST',
        url: '/api/keyword/add'
      });
      event.preventDefault();
    });
    document.querySelector('#deleteArtistSubmit').addEventListener('click', event => {
      var deleteArtist = document.querySelector('#deleteArtist');
      var artist_id = deleteArtist.value;
      if (artist_id === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          artist_id: artist_id
        },
        dataType: 'json',
        statusCode: {
          200: () => {
            // 200 OK
            alert(`Artist with ID ${artist_id} deleted!`);
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: () => {
            // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type: 'POST',
        url: '/api/artist/delete'
      });
      deleteArtist.searchableSelectReset();
      event.preventDefault();
    });
    document.querySelector('#deleteAlbumSubmit').addEventListener('click', event => {
      var deleteAlbum = document.querySelector('#deleteAlbum');
      var album_id = deleteAlbum.value;
      if (album_id === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          album_id: album_id
        },
        dataType: 'json',
        statusCode: {
          200: () => {
            // 200 OK
            alert(`Album with ID ${album_id} deleted!`);
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: () => {
            // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type: 'POST',
        url: '/api/album/delete'
      });
      deleteAlbum.searchableSelectReset();
      event.preventDefault();
    });
    document.querySelector('#transferAlbumDataSubmit').addEventListener('click', event => {
      var transferAlbumDataFrom = document.querySelector('#transferAlbumDataFrom');
      var transferAlbumDataTo = document.querySelector('#transferAlbumDataTo');
      var album_id_from = transferAlbumDataFrom.value;
      var album_id_to = transferAlbumDataTo.value;
      if (album_id_from === '' || album_id_to === '') {
        event.preventDefault();
        return;
      }
      ajax({
        data: {
          album_id_from: album_id_from,
          album_id_to: album_id_to
        },
        dataType: 'json',
        statusCode: {
          200: () => {
            // 200 OK
            alert(`Album with ID ${album_id_from} data transferred to album with ID ${album_id_to}!`);
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: () => {
            // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type: 'POST',
        url: '/api/album/transfer'
      });
      transferAlbumDataFrom.searchableSelectReset();
      transferAlbumDataTo.searchableSelectReset();
      event.preventDefault();
    });
    document.querySelector('.clear_cache').addEventListener('click', () => {
      ajax({
        dataType: 'json',
        statusCode: {
          200: () => {
            // 200 OK
            location.reload();
          }
        },
        type: 'GET',
        url: '/Ajax/cache/delete'
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initAdminEvents();
initSearchableSelect(document.querySelector('#deleteArtist'));
initSearchableSelect(document.querySelector('#deleteAlbum'));
initSearchableSelect(document.querySelector('#transferAlbumDataFrom'));
initSearchableSelect(document.querySelector('#transferAlbumDataTo'));
