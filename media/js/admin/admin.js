$.extend(view, {
  // Init Edit album events.
  initAdminEvents: function () {
    $('#addArtistSubmit').click(function () {
      var name = $('#addArtistText').val();
      if (name === '') {
        return false;
      }
      $.ajax({
        data:{
          artist_name:name
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            alert('Artist ' + name + ' added!');
            $('#addArtistText').val('');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#addArtistText').val('');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#addArtistText').val('');
          }
        },
        type:'POST',
        url:'/api/artist/add'
      });
      return false;
    });
    $('#addGenreSubmit').click(function () {
      var name = $('#addGenreText').val();
      if (name === '') {
        return false;
      }
      $.ajax({
        data:{
          name:name
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            alert('Genre ' + name + ' added!');
            $('#addGenreText').val('');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#addGenreText').val('');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#addGenreText').val('');
          }
        },
        type:'POST',
        url:'/api/genre/add'
      });
      return false;
    });
    $('#addKeywordSubmit').click(function () {
      var name = $('#addKeywordText').val();
      if (name === '') {
        return false;
      }
      $.ajax({
        data:{
          name:name
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            alert('Keyword ' + name + ' added!');
            $('#addKeywordText').val('');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#addGenreText').val('');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#addGenreText').val('');
          }
        },
        type:'POST',
        url:'/api/keyword/add'
      });
      return false;
    });
    $('#deleteArtistSubmit').click(function () {
      var artist_id = $('#deleteArtist').val();
      if (artist_id === '') {
        return false;
      }
      $.ajax({
        data:{
          artist_id:artist_id
        },
        dataType:'json',
        statusCode:{
          200: function (data) { // 200 OK
            alert('Artist with ID ' + artist_id + ' deleted!');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: function () { // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type:'POST',
        url:'/api/artist/delete'
      });
      $('#deleteArtist option:selected').remove();
      $('#deleteArtist').trigger('chosen:updated');
      return false;
    });
    $('#deleteAlbumSubmit').click(function () {
      var album_id = $('#deleteAlbum').val();
      if (album_id === '') {
        return false;
      }
      $.ajax({
        data:{
          album_id:album_id
        },
        dataType:'json',
        statusCode:{
          200: function (data) { // 200 OK
            alert('Album with ID ' + album_id + ' deleted!');
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: function () { // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type:'POST',
        url:'/api/album/delete'
      });
      $('#deleteAlbum option:selected').remove();
      $('#deleteAlbum').trigger('chosen:updated');
      return false;
    });
    $('#transferAlbumDataSubmit').click(function () {
      var album_id_from = $('#transferAlbumDataFrom').val();
      var album_id_to = $('#transferAlbumDataTo').val();
      if (album_id_from === '' || album_id_to === '') {
        return false;
      }
      $.ajax({
        data:{
          album_id_from:album_id_from,
          album_id_to:album_id_to
        },
        dataType:'json',
        statusCode:{
          200: function (data) { // 200 OK
            alert('Album with ID ' + album_id_from + ' data transferred to album with ID ' + album_id_to + '!');
          },
          400: function (data) { // 400 Bad Request
            alert('400 Bad Request');
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
          },
          403: function () { // 403 Forbidden
            alert('403 Forbidden');
          }
        },
        type:'POST',
        url:'/api/album/transfer'
      });
      $('#transferAlbumDataFrom option:selected').remove();
      $('#transferAlbumDataTo option:selected').remove();
      $('#transferAlbumDataFrom').trigger('chosen:updated');
      $('#transferAlbumDataTo').trigger('chosen:updated');
      return false;
    });
    $('.clear_cache').click(function () {
      $.ajax({
        dataType:'json',
        statusCode:{
          200: function () { // 200 OK
            location.reload();
          }
        },
        type:'GET',
        url:'/Ajax/cache/delete'
      });
    });
  }
});

$(document).ready(function () {
  document.querySelector('.background_overlay').style.backgroundImage = "url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>')";
  view.initAdminEvents();
  $('#deleteArtist').chosen({search_contains:true});
  $('#deleteArtist').prioritizedChosenSearch();
  $('#deleteAlbum').chosen({search_contains:true});
  $('#deleteAlbum').prioritizedChosenSearch();
  $('#transferAlbumDataFrom').chosen({search_contains:true});
  $('#transferAlbumDataFrom').prioritizedChosenSearch();
  $('#transferAlbumDataTo').chosen({search_contains:true});
  $('#transferAlbumDataFrom').prioritizedChosenSearch();
});