$.extend(view, {
  // Get top albums.
  getTopAlbums: function (tag_id, tag_type, element, lower_limit) {
    $.ajax({
      data:{
        limit:9,
        lower_limit:lower_limit,
        tag_id:tag_id,
        tag_type:tag_type,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#topAlbum' + element + 'Loader').hide();
              $('#topAlbum' + element).html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  // Get top artists.
  getTopArtists: function (tag_id, tag_type, element, lower_limit) {
    $.ajax({
      data:{
        group_by:'`artist_id`',
        limit:9,
        lower_limit:lower_limit,
        order_by:'`count` DESC, <?=TBL_artist?>.`artist_name` ASC',
        tag_id:tag_id,
        tag_type:tag_type,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'artist'
            },
            success: function (data) {
              $('#topArtist' + element + 'Loader').hide();
              $('#topArtist' + element).html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  topGenre: function (lower_limit) {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:20,
        lower_limit:lower_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $('.genre_heading').html('<a href="/genre/' + encodeURIComponent(data[0].name.toLowerCase()) + '">' + data[0].name + '</a>');
          view.getTopAlbums(data[0].tag_id, 'genre', 'Genre', lower_limit);
          view.getTopArtists(data[0].tag_id, 'genre', 'Genre', lower_limit);
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader, #topGenreLoader2').hide();
              $('#topGenre').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topGenreLoader, #topGenreLoader2, #topAlbumGenreLoader, #topArtistGenreLoader').hide();
          $('#topGenre, #topAlbumGenre').html('<?=ERR_NO_RESULTS?>');
          $('.genre_heading').html('Genres');
          $('#topArtistGenre').html('');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  topKeyword: function (lower_limit) {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:20,
        lower_limit:lower_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
           $('.keyword_heading').html('<a href="/keyword/' + encodeURIComponent(data[0].name.toLowerCase()) + '">' + data[0].name + '</a>');
          view.getTopAlbums(data[0].tag_id, 'keyword', 'Keyword', lower_limit);
          view.getTopArtists(data[0].tag_id, 'keyword', 'Keyword', lower_limit);
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader, #topKeywordLoader2').hide();
              $('#topKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topKeywordLoader, #topKeywordLoader2, #topAlbumKeywordLoader, #topArtistKeywordLoader').hide();
          $('#topKeyword, #topAlbumKeyword').html('<?=ERR_NO_RESULTS?>');
          $('.keyword_heading').html('Keywords');
          $('#topArtistKeyword').html('');
        }
      },
      type:'GET',
      url:'/api/keyword/get'
    });
  },
  topNationality: function (lower_limit) {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:20,
        lower_limit:lower_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
           $('.nationality_heading').html('<a href="/nationality/' + encodeURIComponent(data[0].name.toLowerCase()) + '">' + data[0].name + '</a>');
          view.getTopAlbums(data[0].tag_id, 'nationality', 'Nationality', lower_limit);
          view.getTopArtists(data[0].tag_id, 'nationality', 'Nationality', lower_limit);
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topNationalityLoader, #topNationalityLoader2').hide();
              $('#topNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topNationalityLoader, #topNationalityLoader2, #topAlbumNationalityLoader, #topArtistNationalityLoader').hide();
          $('#topNationality, #topAlbumNationality').html('<?=ERR_NO_RESULTS?>');
          $('.nationality_heading').html('Nationalities');
          $('#topArtistNationality').html('');
        }
      },
      type:'GET',
      url:'/api/nationality/get/listenings'
    });
  },
  topYear: function (lower_limit) {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    }
    else {
      var date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data:{
        limit:20,
        lower_limit:lower_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $('.year_heading').html('<a href="/year/' + encodeURIComponent(data[0].name.toLowerCase()) + '">' + data[0].name + '</a>');
          view.getTopAlbums(data[0].tag_id, 'year', 'Year', lower_limit);
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topYearLoader, #topYearLoader2').hide();
              $('#topYear').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function () { // 204 No Content
          $('#topYearLoader, #topYearLoader2, #topAlbumYearLoader').hide();
          $('#topYear, #topAlbumYear').html('<?=ERR_NO_RESULTS?>');
          $('.year_heading').html('Years');
        }
      },
      type:'GET',
      url:'/api/year/get'
    });
  }
});

$(document).ready(function() {
  view.topGenre('<?=$top_genre_tags?>');
  view.topKeyword('<?=$top_keyword_tags?>');
  view.topNationality('<?=$top_nationality_tags?>');
  view.topYear('<?=$top_year_tags?>');
});

