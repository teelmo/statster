$.extend(view, {
  // Get top albums.
  getTopAlbums: function (tag_id, tag_type, element) {
    $.ajax({
      data:{
        limit:9,
        lower_limit:'1970-00-00',
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
  getTopArtists: function (tag_id, tag_type, element) {
    $.ajax({
      data:{
        group_by:'`artist_id`',
        limit:9,
        lower_limit:'1970-00-00',
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
  topGenre: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader').hide();
              $('#topGenre').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  topKeyword: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader').hide();
              $('#topKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function() { // 204 No Content
          alert('204 No Content');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/keyword/get'
    });
  },
  topNationality: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topNationalityLoader').hide();
              $('#topNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function() { // 204 No Content
          alert('204 No Content');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/nationality/get/listenings'
    });
  },
  topYear: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topYearLoader').hide();
              $('#topYear').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });  
        },
        204: function() { // 204 No Content
          alert('204 No Content');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/year/get'
    });
  }
});

$(document).ready(function() {
  view.getTopAlbums('<?=$genre['tag_id']?>', 'genre', 'Genre');
  view.getTopArtists('<?=$genre['tag_id']?>', 'genre', 'Genre');
  view.getTopAlbums('<?=$keyword['tag_id']?>', 'keyword', 'Keyword');
  view.getTopArtists('<?=$keyword['tag_id']?>', 'keyword', 'Keyword');
  view.getTopAlbums('<?=$nationality['tag_id']?>', 'nationality', 'Nationality');
  view.getTopArtists('<?=$nationality['tag_id']?>', 'nationality', 'Nationality');
  view.getTopAlbums('<?=$year['year']?>', 'year', 'Year');
  view.topGenre();
  view.topKeyword();
  view.topNationality();
  view.topYear();
});

