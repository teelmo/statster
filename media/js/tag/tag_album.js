Object.assign(view, {
  topAlbum10: interval => {
    var lower_limit;
    if (interval === 'overall') {
      lower_limit = '1970-00-00';
    } else {
      const date = new Date();
      date.setDate(date.getDate() - parseInt(interval, 10));
      lower_limit = date.toISOString().split('T')[0];
    }
    ajax({
      data: {
        limit: 8,
        lower_limit: lower_limit,
        tag_id: parseInt('<?=$tag_id?>', 10),
        tag_type: '<?=$tag_type?>',
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
              document.querySelectorAll('#topAlbum10Loader, #topAlbum10Loader2').forEach(el => {
                el.style.display = 'none';
              });
              document.querySelector('#topAlbum10').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/albumList'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelectorAll('#topAlbum10Loader, #topAlbum10Loader2').forEach(el => {
            el.style.display = 'none';
          });
          document.querySelector('#topAlbum10').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
    view.topAlbum(lower_limit);
  },
  topAlbum: (lower_limit, upper_limit = false, vars = false) => {
    if (!upper_limit) {
      vars = {
        container: '#topAlbum',
        limit: '8, 200',
        rank: 9,
        template: '/ajax/columnTable'
      };
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      upper_limit = '<?=CUR_DATE?>';
    }
    ajax({
      data: {
        limit: vars.limit,
        lower_limit: lower_limit,
        tag_id: '<?=$tag_id?>',
        tag_type: '<?=$tag_type?>',
        upper_limit: upper_limit,
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              hide: vars.hide,
              json_data: data,
              rank: vars.rank,
              size: 32
            },
            success: data => {
              document.querySelector(`${vars.container}Loader`).style.display = 'none';
              document.querySelector(vars.container).innerHTML = data;
            },
            type: 'POST',
            url: vars.template
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector(`${vars.container}Loader`).style.display = 'none';
          document.querySelector(vars.container).innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/tag/get'
    });
  },
  topAlbumYearly: () => {
    var vars;
    for (year = parseInt(`<?=CUR_YEAR?>`, 10); year >= 2003; year--) {
      document.querySelector('#sideTable').insertAdjacentHTML('beforeend', `<div class="container"><h2 class="number">${year}</h3><div class="lds-facebook" id="sideTopAlbum${year}Loader"><div></div><div></div><div></div></div><table id="sideTopAlbum${year}" class="side_table"></table><div class="more"><a href="/<?=$tag_type?>/${year}/<?=$type?>" title="Browse more">More <span class="number">${year}</span></</a></div></div><div class="container"><hr /></div>`);
      vars = {
        container: `#sideTopAlbum${year}`,
        hide: {
          artist: true,
          calendar: true,
          date: true,
          spotify: true
        },
        limit: 5,
        rank: 1,
        template: '/ajax/sideTable'
      };
      view.topAlbum(`${year}-00-00`, `${year}-12-31`, vars);
    }
  }
});

app.setOverlayBackground(`<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>`);
view.topAlbum10('<?=$top_album_tag_album?>');
view.topAlbumYearly();
