$.extend(view, {
  artistAlbum: order_by => {
    $.ajax({
      data: {
        artist_name: '<?=$artist_name?>',
        order_by: order_by,
        username: `<?=!empty($_GET['u']) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              hide: {
                artist: true
              }
            },
            success: data => {
              $('#artistAlbumLoader').hide();
              $('#discographyLoader').hide();
              $('#artistAlbum').html(data);
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          $('#artistAlbumLoader').hide();
          $('#artistAlbum').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/artistAlbum'
    });
  },
  associatedArtist: () => {
    $.ajax({
      data: {
        artist_id: `<?=(isset($artists)) ? implode(',', array_map(function($artist) { return $artist['artist_id'];}, $artists)) : $artist_id?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              json_data: data,
              hide: {
                count: true
              }
            },
            success: data => {
              $('#associatedArtistLoader').hide();
              $('#associatedArtist').html(data);
            },
            type: 'POST',
            url: '/ajax/artistList'
          });
        },
        204: () => {
          $('#associatedArtistLoader').hide();
          $('#associatedArtist').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/associatedArtist'
    });
  },
  artistAlbumEvents: () => {
    $('#biographyMore').click(event => {
      $('#biographyMore').hide();
      $('.summary').hide();
      $('#biographyLess').show();
      $('.content').fadeIn();
      event.preventDefault();
    });
    $('#biographyLess').click(event => {
      $('#biographyLess').hide();
      $('.content').hide();
      $('#biographyMore').show();
      $('.summary').show();
      event.preventDefault();
    });
  }
});

$(document).ready(() => {
  view.artistAlbum('<?=$artist_album?>');
  view.associatedArtist();
  view.artistAlbumEvents();
});
