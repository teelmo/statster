$.extend(view, {
  getTopGenres: (lower_limit, upper_limit = false, vars = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      vars = {
        container: '#topGenre',
        limit: '0, 200',
        template: '/ajax/columnTable'
      };
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data: {
        limit: vars.limit,
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: vars.hide,
              json_data: data,
              rank: 1
            },
            success: data => {
              $(`${vars.container}Loader, ${vars.container}Loader2`).hide();
              $(`${vars.container}`).html(data);
            },
            type: 'POST',
            url: vars.template
          });
        },
        204: () => {
          // 204 No Content
          $(`${vars.container}Loader`).hide();
          $(vars.container).html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/genre/get'
    });
  },
  getTopGenresYearly: () => {
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      $(`<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook" id="topGenre${year}Loader"><div></div><div></div><div></div></div><table id="topGenre${year}" class="side_table"></table></div><div class="container"><hr /></div>`).appendTo($('#years'));
      const vars = {
        container: `#topGenre${year}`,
        limit: 3,
        size: 32,
        template: '/ajax/sideTable',
        hide: {
          calendar: true,
          date: true,
          spotify: true
        }
      };
      view.getTopGenres(`${year}-00-00`, `${year}-12-31`, vars);
    }
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  view.getTopGenres('<?=$top_genre_genre?>');
  view.getTopGenresYearly();
});
