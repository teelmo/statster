Object.assign(view, {
  initAutocomplete: () => {
    var input = document.querySelector('#addListeningText');
    input.focus();
    view.addListeningAutocomplete = initAutocomplete(input, {
      dropdownId: 'ui-id-2',
      minLength: 3,
      source: '/autoComplete/addListening'
    });
  },
  // Native <input type="date"> replaces the inline daterangepicker
  // (singleDate: true) widget - the browser's own date picker covers the
  // same "pick one date" need without a library, and the min/max bound
  // (today, up to tomorrow) maps directly to the input's max attribute.
  initDatepicker: () => {
    var curday = sp => {
      const today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();

      if (dd < 10) dd = `0${dd}`;
      if (mm < 10) mm = `0${mm}`;
      return yyyy + sp + mm + sp + dd;
    };
    var dateInput = document.querySelector('#addListeningDate');
    dateInput.type = 'date';
    dateInput.max = `<?=date('Y-m-d', strtotime(CUR_DATE . "+1 days"))?>`;
    dateInput.value = curday('-');
    dateInput.addEventListener('change', () => {
      setTimeout(
        () => {
          dateInput.value = curday('-');
        },
        60 * 2 * 1000
      ); //
    });
  },
  initKeystop: () => {
    // Backspace navigates the browser back unless focus is in an editable
    // field; Enter inside a text/password field is swallowed so it can't
    // accidentally submit some other form on the page.
    var editableSelector = 'input:not([type]), input[type="text"], input[type="password"], input[type="file"], textarea';
    var textSelector = 'input:not([type]), input[type="text"], input[type="password"]';
    document.addEventListener('keydown', event => {
      if (event.key === 'Backspace' && !event.target.matches(editableSelector)) {
        event.preventDefault();
      } else if (event.key === 'Enter' && event.target.matches(textSelector)) {
        event.preventDefault();
      }
    });
  },
  initAddListeningHelperEvents: () => {
    var toggleFormat = el => {
      var checkbox = document.querySelector(`#${el.parentElement.getAttribute('for')}`);
      if (el.classList.contains('selected')) {
        el.classList.remove('selected');
        checkbox.checked = false;
      } else {
        document.querySelectorAll('.listening_format').forEach(format => {
          format.classList.remove('selected');
        });
        el.classList.add('selected');
        checkbox.checked = true;
      }
      checkbox.dispatchEvent(new Event('change'));
    };
    document.querySelectorAll('.listening_format').forEach(el => {
      el.addEventListener('click', event => {
        toggleFormat(el);
        event.preventDefault();
        event.stopPropagation();
      });
      el.addEventListener('keypress', event => {
        var code = event.keyCode || event.which;
        if (code === 13) {
          toggleFormat(el);
        }
        event.preventDefault();
        event.stopPropagation();
      });
    });
    document.querySelector('#addListeningSubmit').addEventListener('click', () => {
      var addListeningText = document.querySelector('#addListeningText');
      var text_value = addListeningText.value;
      if (text_value === '') {
        return false;
      }
      var checkedFormat = document.querySelector('input[name="addListeningFormat"]:checked');
      var format_value = checkedFormat ? checkedFormat.value : undefined;
      var selectedItem = view.addListeningAutocomplete.getSelectedItem();
      var album_id = selectedItem ? selectedItem.album_id : false;
      var artist_ids = selectedItem ? selectedItem.artist_ids : false;
      document.querySelector('#recentlyListenedLoader2').classList.remove('hidden');
      addListeningText.value = '';
      document.querySelectorAll('input[name="addListeningFormat"]').forEach(el => {
        el.checked = false;
      });
      document.querySelectorAll('.listening_format').forEach(el => {
        el.classList.remove('selected');
      });
      ajax({
        data: {
          album_id: album_id,
          artist_ids: artist_ids,
          created: new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' '),
          date: document.querySelector('#addListeningDate').value,
          format: format_value,
          submitType: document.querySelector('input[name="submitType"]')?.value,
          text: text_value
        },
        dataType: 'json',
        statusCode: {
          201: () => {
            // 201 Created
            view.getRecentListenings();
            if (view.getTopArtists) {
              view.getTopArtists(document.querySelector('.top_artist_value').dataset.value);
            }
            if (view.getTopAlbums) {
              view.getTopAlbums(document.querySelector('.top_album_value').dataset.value);
            }
            if (view.getUsers) {
              view.getUsers();
            }
            var tagMetaValue = document.querySelector('.tag_meta div.value');
            if (tagMetaValue) {
              var nextValue = parseInt(tagMetaValue.dataset.value, 10) + 1;
              tagMetaValue.dataset.value = nextValue;
              tagMetaValue.innerHTML = nextValue.toLocaleString();
            }
            var tagMetaUserValue = document.querySelector('.tag_meta span.user_value .value');
            if (tagMetaUserValue) {
              var nextUserValue = parseInt(tagMetaUserValue.dataset.value, 10) + 1;
              tagMetaUserValue.dataset.value = nextUserValue;
              tagMetaUserValue.innerHTML = nextUserValue.toLocaleString();
            }
            addListeningText.focus();
          },
          400: () => {
            // 400 Bad Request
            alert('400 Bad Request');
            document.querySelector('#recentlyListenedLoader2').classList.add('hidden');
          },
          401: () => {
            // 401 Unauthorized
            alert('401 Unauthorized');
            document.querySelector('#recentlyListenedLoader2').classList.add('hidden');
          },
          404: () => {
            // 404 Not found
            alert('404 Not Found');
            document.querySelector('#recentlyListenedLoader2').classList.add('hidden');
          }
        },
        type: 'POST',
        url: '/api/listening/add'
      });
      return false;
    });
  }
});

view.initAutocomplete();
view.initDatepicker();
view.initKeystop();
view.initAddListeningHelperEvents();
