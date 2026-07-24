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
      var artist_id = $('#deleteArtist').val();
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
      $('#deleteArtist option').removeAttr('selected');
      $('#deleteArtist').val('');
      $('#deleteArtist').trigger('chosen:updated');
      event.preventDefault();
    });
    document.querySelector('#deleteAlbumSubmit').addEventListener('click', event => {
      var album_id = $('#deleteAlbum').val();
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
      $('#deleteAlbum option').removeAttr('selected');
      $('#deleteAlbum').val('');
      $('#deleteAlbum').trigger('chosen:updated');
      event.preventDefault();
    });
    document.querySelector('#transferAlbumDataSubmit').addEventListener('click', event => {
      var album_id_from = $('#transferAlbumDataFrom').val();
      var album_id_to = $('#transferAlbumDataTo').val();
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
      $('#transferAlbumDataFrom option').removeAttr('selected');
      $('#transferAlbumDataTo option').removeAttr('selected');
      $('#transferAlbumDataFrom').val('');
      $('#transferAlbumDataTo').val('');
      $('#transferAlbumDataFrom').trigger('chosen:updated');
      $('#transferAlbumDataTo').trigger('chosen:updated');
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
$('#deleteArtist').chosen({ search_contains: true });
$('#deleteArtist').prioritizedChosenSearch();
$('#deleteAlbum').chosen({ search_contains: true });
$('#deleteAlbum').prioritizedChosenSearch();
$('#transferAlbumDataFrom').chosen({ search_contains: true });
$('#transferAlbumDataFrom').prioritizedChosenSearch();
$('#transferAlbumDataTo').chosen({ search_contains: true });
$('#transferAlbumDataFrom').prioritizedChosenSearch();
