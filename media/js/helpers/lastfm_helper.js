$.extend(view, {
  getSimilar: () => {
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '/api/lastfm/fetchSimilar',
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 8
      },
      statusCode: {
        200: data => {
          $.ajax({
            type: 'POST',
            url: '/ajax/artistList',
            data: {
              json_data: data,
              hide: {
                count: true
              }
            },
            success: data => {
              $('#similarArtistLoader').hide();
              $('#similarArtist').html(data);
            }
          });
        }
      }
    });
  },
  getEvents: () => {
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '/api/lastfm/getEvents',
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 15
      },
      statusCode: {
        200: data => {
          $.ajax({
            type: 'POST',
            url: '/ajax/eventTable',
            data: {
              json_data: data
            },
            success: data => {
              $('#artistEventLoader').hide();
              $('#artistEvent').html(data);
            }
          });
        }
      }
    });
  }
});

$(document).ready(() => {
  view.getSimilar();
  // view.getEvents();
});
