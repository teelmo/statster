Object.assign(view, {
  // Get recent listenings.
  getRecentListenings: (_isFirst, _callback) => {
    ajax({
      data: {
        limit: 100,
        sub_group_by: 'album',
        username: `<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>`
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          const today = new Date();
          ajax({
            data: {
              cur_date: `${today.getFullYear()}-${(`0${today.getMonth() + 1}`).slice(-2)}-${(`0${today.getDate()}`).slice(-2)}`,
              json_data: data,
              strlenght: 50,
              time: Math.floor((Date.now() - new Date().getTimezoneOffset() * 60000) / 1000)
            },
            success: data => {
              document.querySelectorAll('#recentlyListenedLoader, #recentlyListenedLoader2').forEach(el => {
                el.classList.add('hidden');
              });
              document.querySelector('#recentlyListened').innerHTML = data;
              var hours = today.getHours();
              var minutes = today.getMinutes();
              if (minutes < 10) {
                minutes = `0${minutes}`;
              }
              document.querySelector('#recentlyUpdated').innerHTML = `updated <span class="number">${hours}</span>:<span class="number">${minutes}</span>`;
              document.querySelector('#recentlyUpdated').setAttribute('value', today.getTime());
            },
            type: 'POST',
            url: '/ajax/musicTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#recentlyListenedLoader').classList.add('hidden');
          document.querySelector('#recentlyListened').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          alert('400 Bad Request');
        }
      },
      type: 'GET',
      url: '/api/listening/get'
    });
  },
  getUsers: () => {
    ajax({
      data: {
        limit: 14,
        sub_group_by: 'album'
      },
      dataType: 'json',
      statusCode: {
        200: data => {
          // 200 OK
          ajax({
            data: {
              hide: {
                calendar: true,
                date: true
              },
              json_data: data,
              size: 32
            },
            success: data => {
              document.querySelector('#topListenerLoader').classList.add('hidden');
              document.querySelector('#topListener').innerHTML = data;
            },
            type: 'POST',
            url: '/ajax/userTable'
          });
        },
        204: () => {
          // 204 No Content
          document.querySelector('#topListenerLoader').classList.add('hidden');
          document.querySelector('#topListener').innerHTML = `<?=ERR_NO_RESULTS?>`;
        },
        400: () => {
          // 400 Bad request
          document.querySelector('#topListenerLoader').classList.add('hidden');
          document.querySelector('#topListener').innerHTML = `<?=ERR_BAD_REQUEST?>`;
        }
      },
      type: 'GET',
      url: '/api/listener/get'
    });
  },
  initRecentEvents: () => {
    document.querySelector('#refreshRecentAlbums').addEventListener('click', () => {
      var loader = document.querySelector('#recentlyListenedLoader2');
      loader.classList.remove('hidden');
      view.getRecentListenings();
    });
    document.addEventListener('click', event => {
      var target = event.target.closest('span.delete');
      if (!target) {
        return;
      }
      var container = document.querySelector(target.dataset.confirmationContainer);
      if (container) {
        container.classList.remove('hidden');
      }
    });
    document.addEventListener('click', event => {
      var target = event.target.closest('a.cancel');
      if (!target) {
        return;
      }
      target.closest('div').classList.add('hidden');
    });
    document.addEventListener('click', event => {
      var target = event.target.closest('a.confirm');
      if (!target) {
        return;
      }
      var rowId = target.dataset.rowId;
      ajax({
        statusCode: {
          200: () => {
            // 200 OK
            var row = document.querySelector(`#${rowId}`);
            if (row) {
              if (row.classList.contains('just_added')) {
                document.querySelectorAll('tr').forEach(el => {
                  el.classList.remove('just_added_rest');
                });
              }
              row.remove();
            }
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
          }
        },
        type: 'POST',
        url: `/api/listening/delete/${target.dataset.listeningId}`
      });
    });
  }
});

app.setOverlayBackground(`<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>`);
view.getRecentListenings();
view.getUsers();
view.initRecentEvents();
