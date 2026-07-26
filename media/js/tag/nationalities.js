Object.assign(view, {
  getTopNationalities: (lower_limit, upper_limit = false, vars = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      vars = {
        container: '#topNationality',
        limit: '0, 50',
        template: '/ajax/columnTable'
      };
      upper_limit = '<?=CUR_DATE?>';
    }
    ajax({
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
          ajax({
            data: {
              hide: vars.hide,
              json_data: data,
              rank: 1
            },
            success: data => {
              document.querySelectorAll(`${vars.container}Loader, ${vars.container}Loader2`).forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector(`${vars.container}`).innerHTML = data;
            },
            type: 'POST',
            url: vars.template
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll(`${vars.container}Loader, ${vars.container}Loader2`).forEach(el => {
            el.classList.add('hidden');
          });
          document.querySelector(vars.container).innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/nationality/get'
    });
    if (vars.container === '#topNationality') {
      view.getTopArtistPerNationality(lower_limit, upper_limit, vars);
    }
  },
  getTopArtistPerNationality: (lower_limit, upper_limit, _vars) => {
    ajax({
      data: {
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=!(empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              document.querySelector('#topArtistNationalityLoader').classList.add('hidden');
              document.querySelector('#topArtistNationality').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/artistList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topArtistNationalityLoader').classList.add('hidden');
          document.querySelector('#topArtistNationality').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/nationality/get/artist'
    });
  },
  getTopNationalitiesYearly: () => {
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      document
        .querySelector('#years')
        .insertAdjacentHTML('beforeend', `<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook inline" id="topNationality${year}Loader"><div></div><div></div><div></div></div><table id="topNationality${year}" class="side_table"></table></div><div class="container"><hr /></div>`);
      const vars = {
        container: `#topNationality${year}`,
        limit: 3,
        template: '/ajax/sideTable',
        hide: {
          calendar: true,
          date: true,
          size: 32,
          spotify: true
        }
      };
      view.getTopNationalities(`${year}-00-00`, `${year}-12-31`, vars);
    }
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getTopNationalities('<?=$top_nationality_nationality?>');
view.getTopNationalitiesYearly();
