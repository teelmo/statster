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
  // Hand-rolled single-month calendar, reusing the same structural CSS
  // classes/markup shape as initDateRangePicker (date_filter_helper.js) -
  // see media/css/libs/daterangepicker.min.css - but with its own black/
  // white selected-day style (.day.selected in base.css) instead of that
  // file's blue .checked/.first-date-selected, no topbar/Clear/Today, and
  // month navigation bounded to Jan 2000..tomorrow. Opens on clicking the
  // date text itself, matching the original inline daterangepicker
  // (singleDate: true) widget this replaces.
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
    var container = document.querySelector('#addListeningDateContainer .calendar_container');
    dateInput.type = 'text';
    dateInput.readOnly = true;
    dateInput.value = curday('-');

    // Pages without a .calendar_container (e.g. the mosaic view) keep the
    // plain prefilled text field - no picker to wire up.
    if (!container) {
      return;
    }

    var pad = n => String(n).padStart(2, '0');
    var toISO = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    var atMidnight = d => new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
    var parseISO = s => {
      var parts = s.split('-').map(Number);
      return new Date(parts[0], parts[1] - 1, parts[2]);
    };

    var maxDate = parseISO(`<?=date('Y-m-d', strtotime(CUR_DATE . "+1 days"))?>`);
    var minDate = new Date(2000, 0, 1);
    var selected = parseISO(dateInput.value);
    var viewMonth = new Date(selected.getFullYear(), selected.getMonth(), 1);
    var resetTimer = null;

    var scheduleResetToToday = () => {
      clearTimeout(resetTimer);
      resetTimer = setTimeout(() => {
        selected = parseISO(curday('-'));
        dateInput.value = toISO(selected);
      }, 60 * 2 * 1000);
    };

    var buildMonthTable = monthDate => {
      var year = monthDate.getFullYear();
      var month = monthDate.getMonth();
      var firstOfMonth = new Date(year, month, 1);
      var startOffset = (firstOfMonth.getDay() + 6) % 7;
      var gridStart = new Date(year, month, 1 - startOffset);
      var daysInMonth = new Date(year, month + 1, 0).getDate();
      var totalWeeks = Math.ceil((startOffset + daysInMonth) / 7);

      var rows = '';
      for (var w = 0; w < totalWeeks; w++) {
        var cells = '';
        for (var i = 0; i < 7; i++) {
          var cellDate = new Date(gridStart);
          cellDate.setDate(gridStart.getDate() + w * 7 + i);
          var inMonth = cellDate.getMonth() === month;
          var t = atMidnight(cellDate);
          var isValid = inMonth && t >= atMidnight(minDate) && t <= atMidnight(maxDate);
          var monthClass = inMonth ? 'toMonth' : cellDate < firstOfMonth ? 'lastMonth' : 'nextMonth';
          var classes = ['day', monthClass, isValid ? 'valid' : 'invalid'];
          if (t === atMidnight(selected)) {
            // Reuses the range picker's own selected-day class (and its
            // matching base.css override) rather than inventing a new one.
            classes.push('checked', 'first-date-selected');
          }
          if (t === atMidnight(new Date())) {
            classes.push('real-today');
          }
          cells += `<td><div class="${classes.join(' ')}" data-date="${toISO(cellDate)}">${cellDate.getDate()}</div></td>`;
        }
        rows += `<tr>${cells}</tr>`;
      }

      var monthName = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(monthDate).toLowerCase();
      var canGoPrev = new Date(year, month - 1, 1) >= new Date(minDate.getFullYear(), minDate.getMonth(), 1);
      var canGoNext = new Date(year, month + 1, 1) <= new Date(maxDate.getFullYear(), maxDate.getMonth(), 1);
      return `
        <table class="month1">
          <thead>
            <tr class="caption">
              <th>${canGoPrev ? '<span class="prev"><i class="fa fa-angle-left"></i></span>' : ''}</th>
              <th class="month-name" colspan="5"><div class="month-element">${monthName}</div></th>
              <th>${canGoNext ? '<span class="next"><i class="fa fa-angle-right"></i></span>' : ''}</th>
            </tr>
            <tr class="week-name">
              <th>mo</th><th>tu</th><th>we</th><th>th</th><th>fr</th><th>sa</th><th>su</th>
            </tr>
          </thead>
          <tbody>${rows}</tbody>
        </table>
      `;
    };

    var render = () => {
      container.innerHTML = `
        <div class="date-picker-wrapper single-date no-shortcuts no-topbar inline-wrapper no-gap">
          <div class="month-wrapper">
            ${buildMonthTable(viewMonth)}
            <div class="dp-clearfix"></div>
          </div>
        </div>
      `;
    };

    var onOutsideClick = event => {
      if (!container.contains(event.target) && event.target !== dateInput) {
        close();
      }
    };

    var close = () => {
      container.innerHTML = '';
      document.removeEventListener('click', onOutsideClick, true);
    };

    var open = () => {
      viewMonth = new Date(selected.getFullYear(), selected.getMonth(), 1);
      render();
      setTimeout(() => {
        document.addEventListener('click', onOutsideClick, true);
      }, 0);
    };

    dateInput.addEventListener('click', event => {
      event.stopPropagation();
      if (container.innerHTML) {
        close();
      } else {
        open();
      }
    });

    container.addEventListener('click', event => {
      if (event.target.closest('.prev')) {
        viewMonth = new Date(viewMonth.getFullYear(), viewMonth.getMonth() - 1, 1);
        render();
        return;
      }
      if (event.target.closest('.next')) {
        viewMonth = new Date(viewMonth.getFullYear(), viewMonth.getMonth() + 1, 1);
        render();
        return;
      }
      var day = event.target.closest('.day.valid');
      if (!day) {
        return;
      }
      selected = parseISO(day.dataset.date);
      dateInput.value = toISO(selected);
      scheduleResetToToday();
      close();
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
    document.querySelector('#addListeningSubmit').addEventListener('click', event => {
      // #addListeningSubmit is an <input type="submit"> inside a real
      // <form>; jQuery's .click(handler) treated a `return false` as an
      // implicit preventDefault(), but a plain addEventListener listener
      // does not - without this, the native form submission fires
      // alongside the ajax() call below and reloads the page.
      event.preventDefault();
      var addListeningText = document.querySelector('#addListeningText');
      var text_value = addListeningText.value;
      if (text_value === '') {
        return;
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
            // Each getTopXxx() already hides its own "#topXxxLoader2" spinner
            // once its refresh completes (via inline style.display = 'none')
            // - normally time_interval_helper.js shows it first, before
            // calling the same function. This call path bypasses that, so
            // show it here instead, clearing both the inline style a prior
            // hide left behind and the "hidden" utility class.
            var showLoader = id => {
              var el = document.querySelector(id);
              if (el) {
                el.style.display = '';
                el.classList.remove('hidden');
              }
            };
            view.getRecentListenings();
            if (view.getTopArtists) {
              showLoader('#topArtistLoader2');
              view.getTopArtists(document.querySelector('.top_artist_value').dataset.value);
            }
            if (view.getTopAlbums) {
              showLoader('#topAlbumLoader2');
              view.getTopAlbums(document.querySelector('.top_album_value').dataset.value);
            }
            if (view.getTopFormats) {
              showLoader('#topFormatLoader2');
              view.getTopFormats(document.querySelector('.top_format_value').dataset.value);
            }
            if (view.getTopGenres) {
              showLoader('#topGenreLoader2');
              view.getTopGenres(document.querySelector('.top_genre_value').dataset.value);
            }
            if (view.getTopKeywords) {
              showLoader('#topKeywordLoader2');
              view.getTopKeywords(document.querySelector('.top_keyword_value').dataset.value);
            }
            if (view.getTopNationalities) {
              showLoader('#topNationalityLoader2');
              view.getTopNationalities(document.querySelector('.top_nationality_value').dataset.value);
            }
            if (view.getTopYears) {
              showLoader('#topYearLoader2');
              view.getTopYears(document.querySelector('.top_year_value').dataset.value);
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
    });
  }
});

view.initAutocomplete();
view.initDatepicker();
view.initKeystop();
view.initAddListeningHelperEvents();
