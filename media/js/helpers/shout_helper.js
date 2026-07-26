Object.assign(view, {
  shoutEvents: () => {
    // Note: the original bound these same three delegated handlers on both
    // 'html' and 'body' (a two-element jQuery selection), so each fired
    // twice per matching click - preserved here via two separate roots
    // rather than collapsing to one.
    [document.querySelector('html'), document.querySelector('body')].forEach(root => {
      root.addEventListener('click', event => {
        var target = event.target.closest('span.delete');
        if (!target) {
          return;
        }
        var container = document.querySelector(target.dataset.confirmationContainer);
        if (container) {
          // .confirmation's own CSS class sets display: none (no separate
          // "hidden" utility class involved), so clearing to '' can't
          // override it - an explicit display value is required, same as
          // jQuery's .show() would have computed for this <div>.
          container.style.display = 'block';
        }
      });
      root.addEventListener('click', event => {
        var target = event.target.closest('a.cancel');
        if (!target) {
          return;
        }
        target.closest('div').style.display = 'none';
      });
      root.addEventListener('click', event => {
        var target = event.target.closest('a.confirm');
        if (!target) {
          return;
        }
        var rowId = target.dataset.rowId;
        ajax({
          statusCode: {
            200: () => {
              // 200 OK
              // Note: jQuery's fadeOut('slow') animated this; plain hide
              // drops the animation but keeps the same end state.
              var row = document.querySelector(`#${rowId}`);
              if (row) {
                row.style.display = 'none';
              }
              var shoutTotalNumber = document.querySelector('#shoutTotal .number');
              var shout_total = parseInt(shoutTotalNumber.textContent, 10);
              shout_total--;
              if (shout_total > 0) {
                shoutTotalNumber.textContent = shout_total;
              } else {
                // Note: jQuery's fadeOut(500) animated this; plain hide
                // drops the animation but keeps the same end state.
                document.querySelector('#shoutTotal').style.display = 'none';
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
          url: `/api/shout/delete/${target.dataset.shoutType}/${target.dataset.shoutId}`
        });
      });
    });
    document.querySelector('#shoutSubmit').addEventListener('click', () => {
      var text_value = document.querySelector('#shoutText').value.trim();
      if (text_value === '') {
        return;
      }
      var shoutLoader = document.querySelector('#shoutLoader2');
      shoutLoader.style.display = '';
      shoutLoader.classList.remove('hidden');
      document.querySelector('#shoutText').value = '';
      ajax({
        data: {
          content_id: document.querySelector('#contentID').value,
          text: text_value,
          type: document.querySelector('#contentType').value
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            document.querySelector('#shoutLoader2').classList.add('hidden');
            view.getShouts();
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            document.querySelector('#shoutLoader2').classList.add('hidden');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            document.querySelector('#shoutLoader2').classList.add('hidden');
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
            document.querySelector('#shoutLoader2').classList.add('hidden');
          }
        },
        type: 'POST',
        url: '/api/shout/add'
      });
    });
  }
});

view.shoutEvents();
