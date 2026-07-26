// media/js/autocomplete.js
// Hand-rolled replacement for jQuery UI's autocomplete widget plus
// statster.js's old highlightPatch() ($.ui.autocomplete.prototype._renderItem
// override). That override applied to every jQuery UI autocomplete instance
// site-wide, so both consumers (the global .search_text box and the
// add-listening #addListeningText box) shared one item-rendering function -
// preserved here as autocompleteRenderItem so both keep behaving identically,
// including the "Artist – Album" dash-splitting used only by add-listening.
// Reuses the existing jQuery-UI-authored CSS (.ui-autocomplete, .ui-menu-item,
// .header, .no_img, .highlight, .ui-state-active) so no new styling is needed.
// Static file, no PHP interpolation - loaded as a real <script defer> in
// header.php, same as ajax.js and searchable_select.js.

function autocompleteEscapeHtml(s) {
  return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);
}

function autocompleteEscapeRegExp(s) {
  return s.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
}

function autocompleteHighlight(label, term) {
  return String(label).replace(new RegExp(term, 'gi'), '<span class="highlight">$&</span>');
}

function autocompleteItemMarkup(item, label) {
  if (item.img) {
    return `<a><div class="cover album_img img40" style="background-image: url(${item.image_server_protocol}${item.image_server_ip}/${item.img})"></div>${label}</a>`;
  }
  return `<a><span class="no_img">${label}</span></a>`;
}

// Mirrors the original's $.ui.autocomplete.prototype._renderItem exactly:
// returns {html, selectable} for a normal row, or null to suppress the row
// entirely (the dash-splitting "type the album next" placeholder case).
function autocompleteRenderItem(item, term, inputEl) {
  if (item.value === '') {
    return { html: `<li class="header">${autocompleteEscapeHtml(item.label)}</li>`, selectable: false };
  }
  if (item.value === 'search') {
    return { html: `<li class="header"><a>${item.label}</a></li>`, selectable: false };
  }
  if (term.indexOf('–') !== -1) {
    const item_arr = term.split('–');
    if (item_arr[1] !== '') {
      const artistTerm = autocompleteEscapeRegExp(item_arr[0].trim());
      const albumTerm = autocompleteEscapeRegExp(item_arr[1].trim());
      if (item.label !== `${item_arr[0]} – `) {
        const label = autocompleteItemMarkup(item, String(item.label).replace(new RegExp(`${artistTerm}|${albumTerm}|–`, 'gi'), '<span class="highlight">$&</span>'));
        return { html: `<li class="ui-menu-item" title="${item.value}">${label}</li>`, selectable: true };
      }
      if (inputEl) {
        inputEl.setAttribute('data-placeholder', `${term} (yyyy)`);
      }
      return null;
    }
    const artistOnlyTerm = autocompleteEscapeRegExp(item_arr[0].trim().replace(/<\/?[^>]+(>|$)/g, ''));
    const label2 = autocompleteItemMarkup(item, autocompleteHighlight(item.label, `${artistOnlyTerm}|–`));
    return { html: `<li class="ui-menu-item" title="${item.value}">${label2}</li>`, selectable: true };
  }
  const cleanTerm = autocompleteEscapeRegExp(term.trim().replace(/<\/?[^>]+(>|$)/g, ''));
  const label3 = autocompleteItemMarkup(item, autocompleteHighlight(item.label, cleanTerm));
  return { html: `<li class="ui-menu-item" title="${item.value}">${label3}</li>`, selectable: true };
}

// Initializes a combobox on inputEl. options:
//   source: URL to fetch (term appended as ?term=...)
//   minLength: minimum chars before searching (default 3)
//   delay: debounce ms (default 300)
//   dropdownId: DOM id for the <ul> (matches jquery.autocomplete.css's
//     #ui-id-1 / #ui-id-2 rules so existing styling still applies)
//   onSelect(item): called when an item is chosen
// Returns { getSelectedItem() } - mirrors the old $(el).data('ui-autocomplete').selectedItem read.
function initAutocomplete(inputEl, options) {
  var source = options.source;
  var minLength = options.minLength || 3;
  var delay = options.delay || 300;
  var dropdownId = options.dropdownId;
  var onSelect = options.onSelect || (() => {});

  var container = inputEl.closest('.autocomplete_container') || inputEl.parentElement;
  container.style.position = container.style.position || 'relative';

  var dropdown = document.createElement('ul');
  dropdown.className = 'ui-autocomplete ui-menu ui-front hidden';
  dropdown.id = dropdownId;
  container.append(dropdown);

  var rows = [];
  var activeIndex = -1;
  var debounceTimer = null;
  var fetchToken = 0;
  var selectedItem = null;

  function closeDropdown() {
    dropdown.classList.add('hidden');
    dropdown.innerHTML = '';
    rows = [];
    activeIndex = -1;
  }

  function setActive(index) {
    var lis = dropdown.querySelectorAll('li[data-index]');
    lis.forEach(li => {
      var a = li.querySelector('a');
      if (a) a.classList.remove('ui-state-active');
    });
    activeIndex = index;
    if (index >= 0) {
      const li = dropdown.querySelector(`li[data-index="${index}"]`);
      const a = li?.querySelector('a');
      if (a) {
        a.classList.add('ui-state-active');
        li.scrollIntoView({ block: 'nearest' });
      }
    }
  }

  function renderRows(results) {
    var term = inputEl.value.trim();
    rows = [];
    var html = '';
    results.forEach(item => {
      var rendered = autocompleteRenderItem(item, term, inputEl);
      if (!rendered) {
        return;
      }
      if (rendered.selectable) {
        const index = rows.length;
        rows.push(item);
        html += rendered.html.replace('<li ', `<li data-index="${index}" `);
      } else {
        html += rendered.html;
      }
    });
    dropdown.innerHTML = html;
    activeIndex = -1;
    if (html === '') {
      closeDropdown();
      return;
    }
    dropdown.classList.toggle('hidden');
  }

  function fetchResults() {
    var term = inputEl.value.trim();
    if (term.length < minLength) {
      closeDropdown();
      return;
    }
    inputEl.classList.add('working');
    var token = ++fetchToken;
    fetch(`${source}${source.indexOf('?') !== -1 ? '&' : '?'}term=${encodeURIComponent(term)}`)
      .then(response => response.json())
      .then(data => {
        if (token !== fetchToken) {
          return;
        }
        inputEl.classList.remove('working');
        renderRows(data);
      })
      .catch(() => {
        if (token === fetchToken) {
          inputEl.classList.remove('working');
        }
      });
  }

  function select(index) {
    var item = rows[index];
    if (!item) {
      return;
    }
    selectedItem = item;
    inputEl.value = item.value;
    closeDropdown();
    onSelect(item);
  }

  inputEl.addEventListener('input', () => {
    selectedItem = null;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchResults, delay);
  });

  inputEl.addEventListener('keydown', event => {
    if (dropdown.classList.contains('hidden')) {
      return;
    }
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      const next = activeIndex + 1 >= rows.length ? 0 : activeIndex + 1;
      setActive(next);
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      const prev = activeIndex - 1 < 0 ? rows.length - 1 : activeIndex - 1;
      setActive(prev);
    } else if (event.key === 'Enter') {
      if (activeIndex >= 0) {
        event.preventDefault();
        select(activeIndex);
      }
    } else if (event.key === 'Escape') {
      closeDropdown();
    }
  });

  // mousemove (not mouseenter/mouseover) so a stationary mouse never fights
  // a keyboard-driven setActive() - the hovered row only takes over active
  // state again once the pointer actually moves, matching jQuery UI's
  // original combined mouse/keyboard focus behavior.
  dropdown.addEventListener('mousemove', event => {
    var li = event.target.closest('li[data-index]');
    if (!li) {
      return;
    }
    var index = parseInt(li.dataset.index, 10);
    if (index !== activeIndex) {
      setActive(index);
    }
  });

  // mousedown (not click) fires before the input's blur, so a selection is
  // registered before any blur-driven cleanup runs.
  dropdown.addEventListener('mousedown', event => {
    var li = event.target.closest('li[data-index]');
    if (!li) {
      return;
    }
    event.preventDefault();
    select(parseInt(li.dataset.index, 10));
  });

  // The original overrode jQuery UI's close() to ignore blur events
  // entirely (worked around virtual-keyboard-hide blur on mobile) - so this
  // widget never closes on blur either, only via outside click/Escape/select.
  document.addEventListener('click', event => {
    if (!container.contains(event.target) && !dropdown.contains(event.target)) {
      closeDropdown();
    }
  });

  return {
    getSelectedItem: () => selectedItem
  };
}
