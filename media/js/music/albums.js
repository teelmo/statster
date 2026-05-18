$.extend(view, {
  getTopAlbum10: (lower_limit, upper_limit = false) => {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      } else {
        const date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit, 10));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data: {
        limit: 8,
        hide: {},
        lower_limit: lower_limit,
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data
            },
            success: data => {
              $('#topAlbum10Loader, #topAlbum10Loader2').hide();
              $('#topAlbum10').html(data);
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          // 204 No Content
          $('#topAlbum10Loader, #topAlbum10Loader2').hide();
          $('#topAlbum10').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
    var vars = {
      container: '#topAlbum',
      limit: '8, 192',
      template: '/ajax/columnTable'
    };
    view.getTopAlbum(lower_limit, upper_limit, vars);
  },
  getTopAlbum: (lower_limit, upper_limit, vars) => {
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
              rank: 9,
              size: 32
            },
            success: data => {
              $(`${vars.container}Loader`).hide();
              $(vars.container).html(data);
            },
            type: 'POST',
            url: vars.template
          });
        },
        204: () => {
          // 204 No Content
          $(`${vars.container}Loader`).hide();
          $(vars.container).html('');
        }
      },
      type: 'GET',
      url: '/api/album/get'
    });
  },
  dailyAlbumCount: (limit, vars) => {
    $.ajax({
      data: {
        limit: vars.limit,
        lower_limit: limit,
        upper_limit: limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $(`${vars.container}Loader`).hide();
          $(vars.container).html(data);
        },
        204: () => {
          // 204 No Content
          $(`${vars.container}Loader`).hide();
          $(vars.container).html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/album/get/count'
    });
  },
  getTopAlbumYearly: () => {
    var vars;
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      $(
        `<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook" id="sideTopAlbum${year}Loader"><div></div><div></div><div></div></div><table id="sideTopAlbum${year}" class="side_table"></table><div class="more"><a href="/album/${year}" title="Browse more">More <span class="number">${year}</span></</a></div></div><div class="container"><hr /></div>`
      ).appendTo($('#sideTable'));
      vars = {
        container: `#sideTopAlbum${year}`,
        hide: {
          artist: true,
          calendar: true,
          date: true,
          spotify: true
        },
        limit: 5,
        template: '/ajax/sideTable'
      };
      view.getTopAlbum(`${year}-00-00`, `${year}-12-31`, vars);
    }
  },
  getTopAlbumMonthly: year => {
    var str;
    var pad;
    var pad_month;
    for (month = 1; month <= 12; month++) {
      const month_str = [];
      month_str[1] = 'January';
      month_str[2] = 'February';
      month_str[3] = 'March';
      month_str[4] = 'April';
      month_str[5] = 'May';
      month_str[6] = 'June';
      month_str[7] = 'July';
      month_str[8] = 'August';
      month_str[9] = 'September';
      month_str[10] = 'October';
      month_str[11] = 'November';
      month_str[12] = 'December';
      str = `${month}`;
      pad = '00';
      pad_month = pad.substring(0, pad.length - str.length) + str;
      $(
        '<div class="container"><h2 class="number">' +
          month_str[month] +
          '</h3><div class="lds-facebook" id="sideTopAlbum' +
          month +
          'Loader"><div></div><div></div><div></div></div><table id="sideTopAlbum' +
          month +
          '" class="side_table"></table><div class="more"><a href="/album/' +
          year +
          '/' +
          pad_month +
          '" title="Browse more">More</a></div></div><div class="container"><hr /></div>'
      ).appendTo($('#sideTable'));
      const vars = {
        container: `#sideTopAlbum${month}`,
        hide: {
          artist: true,
          calendar: true,
          date: true,
          spotify: true
        },
        limit: 5,
        template: '/ajax/sideTable'
      };
      view.getTopAlbum(`${year}-${pad_month}-00`, `${year}-${pad_month}-31`, vars);
    }
  },
  getTopAlbumDaily: (year, month) => {
    var str = `${month}`;
    var pad = '00';
    var pad_month = pad.substring(0, pad.length - str.length) + str;
    var weekday = [];
    weekday[0] = 'Sunday';
    weekday[1] = 'Monday';
    weekday[2] = 'Tuesday';
    weekday[3] = 'Wednesday';
    weekday[4] = 'Thursday';
    weekday[5] = 'Friday';
    weekday[6] = 'Saturday';
    for (day = 1; day <= new Date(year, month, 0).getDate(); day++) {
      str = `${day}`;
      const pad_day = pad.substring(0, pad.length - str.length) + str;
      $(
        '<div class="container"><div><div class="lds-facebook" id="sideTopAlbum' +
          day +
          'Loader"><div></div><div></div><div></div></div><span id="sideTopAlbum' +
          day +
          '" class="number"></span> listenings <div class="metainfo">' +
          weekday[new Date(year, month, day).getDay()] +
          ' – <span class="number">' +
          app.getGetOrdinal(day) +
          '</span></div></div><div class="more"><a href="/album/' +
          year +
          '/' +
          pad_month +
          '/' +
          pad_day +
          '" title="Browse more">More</a></div></div>'
      ).appendTo($('#sideTable'));
      const vars = {
        container: `#sideTopAlbum${day}`,
        limit: 100
      };
      view.dailyAlbumCount(`${year}-${pad_month}-${pad_day}`, vars);
    }
  },
  getListenings: (date, vars) => {
    $.ajax({
      data: {
        date: date,
        limit: vars.limit,
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
              size: 32
            },
            success: data => {
              $(`${vars.container}Loader`).hide();
              $(vars.container).html(data);
            },
            type: 'POST',
            url: vars.template
          });
        },
        204: () => {
          // 204 No Content
          $(`${vars.container}Loader`).hide();
          $(vars.container).html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $(`${vars.container}Loader`).hide();
          $(vars.container).html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  getUsers: (date, _vars) => {
    $('<div class="container"><div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div><table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table></div>').appendTo('#sideTable');
    $.ajax({
      data: {
        limit: 14,
        lower_limit: date,
        sub_group_by: 'album',
        upper_limit: date
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          $.ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_NO_RESULTS?>`);
        },
        400: () => {
          // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html(`<?=ERR_BAD_REQUEST?>`);
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
  var day = `<?=$day?>`;
  var month = `<?=$month?>`;
  var year = `<?=$year?>`;
  if (day === '') {
    if (month !== '') {
      view.getTopAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
      view.getTopAlbumDaily('<?=$year?>', '<?=$month?>');
    } else if (year !== '') {
      view.getTopAlbum10('<?=$lower_limit?>', '<?=$upper_limit?>');
      view.getTopAlbumMonthly('<?=$year?>');
    } else {
      view.getTopAlbum10('<?=$lower_limit?>');
      view.getTopAlbumYearly();
    }
  } else {
    $('#topAlbum10, #topAlbum10Loader').hide();
    $('#topAlbum').removeClass('column_table').addClass('music_table');
    const vars = {
      container: '#topAlbum',
      hide: {
        calendar: true,
        count: true,
        date: true,
        rank: true,
        spotify: true
      },
      template: '/ajax/musicTable'
    };
    let str = '' + '<?=$month?>';
    const pad = '00';
    const pad_month = pad.substring(0, pad.length - str.length) + str;
    str = '' + '<?=$day?>';
    const pad_day = pad.substring(0, pad.length - str.length) + str;
    view.getListenings(`<?=$year?>-${pad_month}-${pad_day}`, vars);
    view.getUsers(`<?=$year?>-${pad_month}-${pad_day}`, vars);
  }
});
