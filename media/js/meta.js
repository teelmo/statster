$.extend(view, {
  // Get top albums.
  getTopAlbums: function (tag_id, tag_type, element) {
    $.ajax({
      data:{
        limit:9,
        lower_limit:'1970-00-00',
        tag_id:tag_id,
        tag_type:tag_type,
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              limit:9,
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
          $('#topAlbum').html('<?php echo ERR_NO_RESULTS?>');
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
        order_by:'`count` DESC, <?php echo TBL_artist?>.`artist_name` ASC',
        tag_id:tag_id,
        tag_type:tag_type,
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              limit:9,
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
          $('#topArtist').html('<?php echo ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/tag/get'
    });
  },
  popularGenre: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?php echo date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularGenreLoader').hide();
              $('#popularGenre').html(data);
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
  popularKeyword: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?php echo date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularKeywordLoader').hide();
              $('#popularKeyword').html(data);
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
  popularNationality: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?php echo date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularNationalityLoader').hide();
              $('#popularNationality').html(data);
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
      url:'/api/nationality/get'
    });
  },
  popularYear: function () {
    $.ajax({
      data:{
        limit:20,
        lower_limit:'<?php echo date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?php echo (!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularYearLoader').hide();
              $('#popularYear').html(data);
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
  view.getTopAlbums('<?php echo $genre['tag_id']?>', 'genre', 'Genre');
  view.getTopArtists('<?php echo $genre['tag_id']?>', 'genre', 'Genre');
  view.getTopAlbums('<?php echo $keyword['tag_id']?>', 'keyword', 'Keyword');
  view.getTopArtists('<?php echo $keyword['tag_id']?>', 'keyword', 'Keyword');
  view.getTopAlbums('<?php echo $nationality['tag_id']?>', 'nationality', 'Nationality');
  view.getTopArtists('<?php echo $nationality['tag_id']?>', 'nationality', 'Nationality');
  view.getTopAlbums('<?php echo $year['year']?>', 'year', 'Year');
  view.popularGenre();
  view.popularKeyword();
  view.popularNationality();
  view.popularYear();
});

