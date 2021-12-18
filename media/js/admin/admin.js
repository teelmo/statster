$.extend(view, {
  // Init Edit album events.
  initAdminEvents: function () {
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
  }
});

$(document).ready(function () {
  view.initAdminEvents();
  $('#deleteArtist').chosen({search_contains:true});
  $('#deleteAlbum').chosen({search_contains:true});
});