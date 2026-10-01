Object.assign(view, {
  getBulletins: folder => {
    ajax({
      data: {
        folder: folder
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          ajax({
            data: {
              json_data: data
            },
            success: data => {
              document.querySelector('#inboxLoader').classList.add('hidden');
              document.querySelector('#inbox').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/inboxTable'
          });
        },
        204: () => {
          document.querySelector('#inboxLoader').classList.add('hidden');
          document.querySelector('#inbox').innerHTML = `<?=ERR_NO_RESULTS?>`;
        }
      },
      type: 'GET',
      url: '/api/inbox/get'
    });
  },
  initInboxEvents: folder => {
    view.getBulletins(folder);
    document.querySelector('html').addEventListener('click', event => {
      var target = event.target.closest('tr.shout.unread');
      if (!target) {
        return;
      }
      var id = parseInt(target.dataset.bulletinId, 10);
      target.classList.remove('unread');
      ajax({
        data: {
          type: target.dataset.bulletinType
        },
        type: 'POST',
        url: `/api/inbox/update/${id}`
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initInboxEvents(`<?=$folder?>`);
