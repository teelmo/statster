Object.assign(view, {
  getYearsHistory: (lower_limit, type = '%Y') => {
    view.getTopYears(lower_limit);
    if (lower_limit === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const date = new Date();
      date.setDate(date.getDate() - parseInt(lower_limit, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 200,
        lower_limit: lower_limit,
        order_by: '<?=TBL_album?>.`year` ASC',
        select: '<?=TBL_album?>.`year` as bar_date',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`,
        where: '<?=TBL_album?>.`year` <> 0'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: type
            },
            success: data => {
              document.querySelector('#historyLoader').style.display = 'none';
              var history = document.querySelector('#history');
              ajaxSetHtml(history, data);
              history.style.display = 'none';
              document.querySelector('.music_bar').style.display = '';
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type: 'POST',
            url: '/ajax/musicBar'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#historyLoader, .music_bar, #topYearLoader2').forEach(el => {
            el.style.display = 'none';
          });
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#historyLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/year/get/'
    });
  },
  getAgeHistory: () => {
    ajax({
      data: {
        group_by: 'GROUP BY `bar_date`',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              json_data: data,
              type: '%Y'
            },
            success: data => {
              document.querySelector('#historyLoader').style.display = 'none';
              var history = document.querySelector('#history');
              ajaxSetHtml(history, data);
              history.style.display = 'none';
              document.querySelector('.music_bar').style.display = '';
              app.chart.xAxis[0].setCategories(view.categories, false);
              app.chart.series[0].setData(view.chart_data, true);
            },
            type: 'POST',
            url: '/ajax/musicBar'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#historyLoader, .music_bar, #topYearLoader2').forEach(el => {
            el.style.display = 'none';
          });
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#historyLoader').style.display = 'none';
          alert(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/year/get/age'
    });
  },
  getTopYears: (lower_limit, upper_limit = false, vars = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        date.setDate(new Date().getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      vars = {
        container: '#topYear',
        limit: '0, 10',
        template: '/ajax/columnTable'
      };
      upper_limit = '<?=CUR_DATE?>';
    }
    ajax({
      data: {
        limit: vars.limit,
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
              hide: vars.hide,
              json_data: data,
              rank: 1
            },
            success: data => {
              document.querySelectorAll(`${vars.container}Loader, ${vars.container}Loader2`).forEach(el => {
                el.style.display = 'none';
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
            el.style.display = 'none';
          });
          document.querySelector(vars.container).innerHTML = '<?=ERR_NO_RESULTS?>';
        }
      },
      type: 'GET',
      url: '/api/year/get'
    });
    if (vars.container === '#topYear') {
      view.getTopAlbumPerYear(lower_limit, upper_limit, vars);
    }
  },
  getTopYearsYearly: () => {
    var vars;
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      document.querySelector('#years').insertAdjacentHTML('beforeend', `<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook" id="topYear${year}Loader"><div></div><div></div><div></div></div><table id="topYear${year}" class="side_table"></table></div><div class="container"><hr /></div>`);
      vars = {
        container: `#topYear${year}`,
        limit: 3,
        template: '/ajax/sideTable',
        hide: {
          calendar: true,
          date: true,
          size: 32,
          spotify: true
        }
      };
      view.getTopYears(`${year}-00-00`, `${year}-12-31`, vars);
    }
  },
  getTopAlbumPerYear: (lower_limit, upper_limit, _vars) => {
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
              type: 'album'
            },
            success: data => {
              document.querySelector('#topAlbumYearlyLoader').style.display = 'none';
              document.querySelector('#topAlbumYearly').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topAlbumYearlyLoader').style.display = 'none';
          document.querySelector('#topAlbumYearly').innerHTML = '<?=ERR_NO_RESULTS?>';
        }
      },
      type: 'GET',
      url: '/api/year/get/yearly'
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initChart();
view.getYearsHistory('<?=$top_year_year?>', '%Y');
view.getTopYearsYearly();
