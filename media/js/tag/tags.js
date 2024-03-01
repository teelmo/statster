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
              $('#topAlbum' + element + 'Loader, #top' + element + 'Loader3').hide();
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
          $('.genre_heading .value').html('<a href="/genre/' + data[0].name.replace(/ /g,'+') + '">' + data[0].name + '</a>').removeClass('not_available');
          $('.genre_link').html('<a href="/genre/' + data[0].name.replace(/ /g,'+') + '">More</a>').removeClass('not_available');
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
          $('#topGenreLoader, #topGenreLoader2, #topGenreLoader3, #topAlbumGenreLoader, #topArtistGenreLoader').hide();
          $('#topGenre, #topAlbumGenre').html('<?=ERR_NO_RESULTS?>');
          $('#topArtistGenre').html('');
          $('.genre_heading .value').html('Genres').removeClass('not_available');
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
          $('.keyword_heading .value').html('<a href="/keyword/' + data[0].name.replace(/ /g,'+') + '">' + data[0].name + '</a>').removeClass('not_available');
          $('.keyword_link').html('<a href="/keyword/' + data[0].name.replace(/ /g,'+') + '">More</a>').removeClass('not_available');
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
          $('#topKeywordLoader, #topKeywordLoader2, #topKeywordLoader3, #topAlbumKeywordLoader, #topArtistKeywordLoader').hide();
          $('#topKeyword, #topAlbumKeyword').html('<?=ERR_NO_RESULTS?>');
          $('#topArtistKeyword').html('');
          $('.keyword_heading .value').html('Keywords').removeClass('not_available');
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
          $('.nationality_heading .value').html('<a href="/nationality/' + data[0].name.replace(/ /g,'+') + '">' + data[0].name + '</a>').removeClass('not_available');
          $('.nationality_link').html('<a href="/nationality/' + data[0].name.replace(/ /g,'+') + '">More</a>').removeClass('not_available');
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
          $('#topNationalityLoader, #topNationalityLoader2, #topNationalityLoader3, #topAlbumNationalityLoader, #topArtistNationalityLoader').hide();
          $('#topNationality, #topAlbumNationality').html('<?=ERR_NO_RESULTS?>');
          $('#topArtistNationality').html('');
          $('.nationality_heading .value').html('Nationalities').removeClass('not_available');
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
          $('.year_heading .value').html('<a href="/year/' + data[0].name.replace(/ /g,'+') + '">' + data[0].name + '</a>').removeClass('not_available');
          $('.year_link').html('<a href="/year/' + data[0].name.replace(/ /g,'+') + '">More</a>').removeClass('not_available');
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
          $('#topYearLoader, #topYearLoader2, #topYearLoader3, #topAlbumYearLoader').hide();
          $('#topYear, #topAlbumYear').html('<?=ERR_NO_RESULTS?>');
          $('.year_heading .value').html('Years').removeClass('not_available');
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

