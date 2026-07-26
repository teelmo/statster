Object.assign(view, {
  getSimilar: () => {
    ajax({
      type: 'GET',
      dataType: 'json',
      url: '/api/lastfm/fetchSimilar',
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 8
      },
      statusCode: {
        200: data => {
          ajax({
            type: 'POST',
            url: '/ajax/artistList',
            data: {
              json_data: data,
              hide: {
                count: true
              }
            },
            success: data => {
              document.querySelector('#similarArtistLoader').classList.add('hidden');
              document.querySelector('#similarArtist').innerHTML = data;
            }
          });
        }
      }
    });
  },
  getEvents: () => {
    ajax({
      type: 'GET',
      dataType: 'json',
      url: '/api/lastfm/getEvents',
      data: {
        artist_name: '<?=$artist_name?>',
        limit: 15
      },
      statusCode: {
        200: data => {
          ajax({
            type: 'POST',
            url: '/ajax/eventTable',
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#artistEventLoader').classList.add('hidden');
              document.querySelector('#artistEvent').innerHTML = data;
            }
          });
        }
      }
    });
  }
});

view.getSimilar();
// view.getEvents();
