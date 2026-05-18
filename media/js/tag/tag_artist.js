$.extend(view, {
  topArtist10: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const date = new Date();
      date.setDate(date.getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    $.ajax({
      data: {
        group_by: '`artist_id`',
        limit: 8,
        lower_limit: lower_limit,
        tag_id: '<?=$tag_id?>',
        tag_type: '<?=$tag_type?>',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              json_data: data,
              type: 'artist'
            },
            success: data => {
              $('#topArtist10Loader, #topArtist10Loader2').hide();
              $('#topArtist10').html(data);
            },
            type: 'POST',
            url: '/ajax/artistList'
          });
        },
        204: () => {
          // 204 No Content
          $('#topArtist10Loader, #topArtist10Loader2').hide();
          $('#topArtist10').html(`<?=ERR_NO_RESULTS?>`);
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
    view.topArtist(lower_limit);
  },
  topArtist: (lower_limit, upper_limit = false, vars = false) => {
    if (!upper_limit) {
      vars = {
        container: '#topArtist',
        hide: {
          album: true
        },
        limit: '8, 200',
        rank: 9,
        template: '/ajax/columnTable'
      };
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    $.ajax({
      data: {
        group_by: '`artist_id`',
        limit: vars.limit,
        lower_limit: lower_limit,
        tag_id: parseInt('<?=$tag_id?>', 10),
        tag_type: '<?=$tag_type?>',
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          $.ajax({
            data: {
              hide: vars.hide,
              json_data: data,
              rank: vars.rank,
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
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  topArtistYearly: () => {
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      $(
        `<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook" id="sideTopArtist${year}Loader"><div></div><div></div><div></div></div><table id="sideTopArtist${year}" class="side_table"></table><div class="more"><a href="/<?=$tag_type?>/${year}/<?=$type?>" title="Browse more">More <span class="number">${year}</span></</a></div></div><div class="container"><hr /></div>`
      ).appendTo($('#sideTable'));
      const vars = {
        container: `#sideTopArtist${year}`,
        hide: {
          album: true,
          calendar: true,
          date: true,
          spotify: true
        },
        limit: 5,
        rank: 1,
        template: '/ajax/sideTable'
      };
      view.topArtist(`${year}-00-00`, `${year}-12-31`, vars);
    }
  }
});

$(document).ready(() => {
  app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>`);
  view.topArtist10('<?=$top_artist_tag_artist?>');
  view.topArtistYearly();
});
