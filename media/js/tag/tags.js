Object.assign(view, {
  // Get top albums.
  getTopAlbums: (tag_id, tag_type, element, lower_limit) => {
    ajax({
      data: {
        limit: 9,
        lower_limit: lower_limit,
        tag_id: tag_id,
        tag_type: tag_type,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'album'
            },
            success: data => {
              document.querySelectorAll(`#topAlbum${element}Loader, #top${element}Loader3`).forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector(`#topAlbum${element}`).innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topAlbumLoader').style.display = 'none';
          document.querySelector('#topAlbum').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  // Get top artists.
  getTopArtists: (tag_id, tag_type, element, lower_limit) => {
    ajax({
      data: {
        group_by: '`artist_id`',
        limit: 9,
        lower_limit: lower_limit,
        order_by: '`count` DESC, <?=TBL_artist?>.`artist_name` ASC',
        tag_id: tag_id,
        tag_type: tag_type,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              document.querySelector(`#topArtist${element}Loader`).style.display = 'none';
              document.querySelector(`#topArtist${element}`).innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/musicWall'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topArtistLoader').style.display = 'none';
          document.querySelector('#topArtist').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  topGenre: lower_limit => {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 20,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          var heading = document.querySelector('.genre_heading .value');
          heading.innerHTML = `<a href="/genre/${data[0].name.replace(/ /g, '+')}">${data[0].name}</a>`;
          heading.classList.remove('not_available');
          var link = document.querySelector('.genre_link');
          link.innerHTML = `<a href="/genre/${data[0].name.replace(/ /g, '+')}">More</a>`;
          link.classList.remove('not_available');
          view.getTopAlbums(data[0].tag_id, 'genre', 'Genre', lower_limit);
          view.getTopArtists(data[0].tag_id, 'genre', 'Genre', lower_limit);
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topGenreLoader, #topGenreLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topGenre').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topGenreLoader, #topGenreLoader2, #topGenreLoader3, #topAlbumGenreLoader, #topArtistGenreLoader').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelectorAll('#topGenre, #topAlbumGenre').forEach(el => {
            el.innerHTML = `<?=ERR_NO_RESULTS?>`;
          });
          document.querySelector('#topArtistGenre').innerHTML = '';
          var heading = document.querySelector('.genre_heading .value');
          heading.innerHTML = 'Genres';
          heading.classList.remove('not_available');
        }
      },
      type: 'GET',
      url: '/api/genre/get'
    });
  },
  topKeyword: lower_limit => {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 20,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          var heading = document.querySelector('.keyword_heading .value');
          heading.innerHTML = `<a href="/keyword/${data[0].name.replace(/ /g, '+')}">${data[0].name}</a>`;
          heading.classList.remove('not_available');
          var link = document.querySelector('.keyword_link');
          link.innerHTML = `<a href="/keyword/${data[0].name.replace(/ /g, '+')}">More</a>`;
          link.classList.remove('not_available');
          view.getTopAlbums(data[0].tag_id, 'keyword', 'Keyword', lower_limit);
          view.getTopArtists(data[0].tag_id, 'keyword', 'Keyword', lower_limit);
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topKeyword').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topKeywordLoader, #topKeywordLoader2, #topKeywordLoader3, #topAlbumKeywordLoader, #topArtistKeywordLoader').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelectorAll('#topKeyword, #topAlbumKeyword').forEach(el => {
            el.innerHTML = `<?=ERR_NO_RESULTS?>`;
          });
          document.querySelector('#topArtistKeyword').innerHTML = '';
          var heading = document.querySelector('.keyword_heading .value');
          heading.innerHTML = 'Keywords';
          heading.classList.remove('not_available');
        }
      },
      type: 'GET',
      url: '/api/keyword/get'
    });
  },
  topNationality: lower_limit => {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 20,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          var heading = document.querySelector('.nationality_heading .value');
          heading.innerHTML = `<a href="/nationality/${data[0].name.replace(/ /g, '+')}">${data[0].name}</a>`;
          heading.classList.remove('not_available');
          var link = document.querySelector('.nationality_link');
          link.innerHTML = `<a href="/nationality/${data[0].name.replace(/ /g, '+')}">More</a>`;
          link.classList.remove('not_available');
          view.getTopAlbums(data[0].tag_id, 'nationality', 'Nationality', lower_limit);
          view.getTopArtists(data[0].tag_id, 'nationality', 'Nationality', lower_limit);
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topNationality').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topNationalityLoader, #topNationalityLoader2, #topNationalityLoader3, #topAlbumNationalityLoader, #topArtistNationalityLoader').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelectorAll('#topNationality, #topAlbumNationality').forEach(el => {
            el.innerHTML = `<?=ERR_NO_RESULTS?>`;
          });
          document.querySelector('#topArtistNationality').innerHTML = '';
          var heading = document.querySelector('.nationality_heading .value');
          heading.innerHTML = 'Nationalities';
          heading.classList.remove('not_available');
        }
      },
      type: 'GET',
      url: '/api/nationality/get/listenings'
    });
  },
  topYear: lower_limit => {
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 20,
        lower_limit: lower_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          var heading = document.querySelector('.year_heading .value');
          heading.innerHTML = `<a href="/year/${data[0].name.replace(/ /g, '+')}">${data[0].name}</a>`;
          heading.classList.remove('not_available');
          var link = document.querySelector('.year_link');
          link.innerHTML = `<a href="/year/${data[0].name.replace(/ /g, '+')}">More</a>`;
          link.classList.remove('not_available');
          view.getTopAlbums(data[0].tag_id, 'year', 'Year', lower_limit);
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelectorAll('#topYearLoader, #topYearLoader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topYear').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/columnTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topYearLoader, #topYearLoader2, #topYearLoader3, #topAlbumYearLoader').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelectorAll('#topYear, #topAlbumYear').forEach(el => {
            el.innerHTML = `<?=ERR_NO_RESULTS?>`;
          });
          var heading = document.querySelector('.year_heading .value');
          heading.innerHTML = 'Years';
          heading.classList.remove('not_available');
        }
      },
      type: 'GET',
      url: '/api/year/get'
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.topGenre('<?=$top_genre_tags?>');
view.topKeyword('<?=$top_keyword_tags?>');
view.topNationality('<?=$top_nationality_tags?>');
view.topYear('<?=$top_year_tags?>');
