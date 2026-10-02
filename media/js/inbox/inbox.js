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
      var target = event.target.closest('.unread[data-bulletin-id]');
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
    document.querySelector('html').addEventListener('click', event => {
      var toggle = event.target.closest('a.thread_toggle');
      if (!toggle) {
        return;
      }
      event.preventDefault();
      var id = parseInt(toggle.dataset.bulletinId, 10);
      var existing = document.querySelectorAll(`tr[data-thread-of="${id}"]`);
      if (existing.length > 0) {
        var collapsing = !existing[0].classList.contains('hidden');
        existing.forEach(row => row.classList.toggle('hidden', collapsing));
        toggle.classList.toggle('expanded', !collapsing);
        return;
      }
      var row = toggle.closest('tr');
      ajax({
        dataType: 'json',
        statusCode: {
          200: data => {
            ajax({
              data: {
                json_data: data,
                thread_of: id
              },
              success: data => {
                row.insertAdjacentHTML('afterend', data);
                toggle.classList.add('expanded');
              },
              type: 'POST',
              url: '/ajax/inboxTable'
            });
          },
          204: () => {}
        },
        type: 'GET',
        url: `/api/inbox/thread/${id}`
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.initInboxEvents(`<?=$folder?>`);
