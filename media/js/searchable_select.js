// media/js/searchable_select.js
// Hand-rolled replacement for chosen.jquery.min.js plus statster.js's
// prioritizedChosenSearch extension (starts-with-beats-contains ranking).
// Progressively enhances a <select> (single or multiple): the underlying
// <select>'s options/selected state stays the source of truth throughout,
// so any code reading .value / .selectedOptions / iterating :selected
// elsewhere keeps working unchanged. Static file, no PHP interpolation -
// loaded as a real <script defer> in header.php, same as ajax.js.

function initSearchableSelect(selectEl) {
  if (!selectEl || selectEl.dataset.searchableSelectInit) {
    return;
  }
  selectEl.dataset.searchableSelectInit = 'true';

  var multiple = selectEl.multiple;
  // A single-select's first option with no `value` attribute (e.g.
  // "Select artist to delete") is a Chosen-style placeholder, not a real
  // choice - excluded from results, and the select starts deselected
  // (selectedIndex -1) rather than defaulting to it like a native select.
  // Chosen displayed this option's own text as its placeholder (since a
  // native select auto-selects it), so it takes priority over data-placeholder
  // - which only fires here for multi-selects, which have no such option.
  var firstOption = selectEl.querySelector('option');
  var placeholderOption = !multiple && firstOption && firstOption.getAttribute('value') === null ? firstOption : null;
  var placeholder = placeholderOption ? placeholderOption.textContent : selectEl.dataset.placeholder || '';
  if (placeholderOption) {
    selectEl.selectedIndex = -1;
  }

  var UNFILTERED_RESULT_CAP = 100;
  var activeIndex = -1;

  selectEl.style.display = 'none';

  var wrapper = document.createElement('div');
  wrapper.className = multiple ? 'searchable_select searchable_select_multiple' : 'searchable_select searchable_select_single';

  var control = document.createElement('div');
  control.className = 'searchable_select_control';

  var chipsContainer = document.createElement('span');
  chipsContainer.className = 'searchable_select_chips';

  var input = document.createElement('input');
  input.type = 'text';
  input.autocomplete = 'off';
  input.className = 'searchable_select_input';
  input.placeholder = placeholder;

  chipsContainer.append(input);
  control.append(chipsContainer);
  wrapper.append(control);

  var dropdown = document.createElement('div');
  dropdown.className = 'searchable_select_dropdown hidden';
  wrapper.append(dropdown);

  selectEl.insertAdjacentElement('afterend', wrapper);

  function getOptions() {
    return Array.from(selectEl.querySelectorAll('option')).filter(o => o !== placeholderOption);
  }

  function optionGroupLabel(option) {
    return option.parentElement && option.parentElement.tagName === 'OPTGROUP' ? option.parentElement.label : null;
  }

  function escapeHtml(s) {
    return s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  }

  function highlight(text, term) {
    if (!term) {
      return escapeHtml(text);
    }
    var idx = text.toLowerCase().indexOf(term.toLowerCase());
    if (idx === -1) {
      return escapeHtml(text);
    }
    return `${escapeHtml(text.slice(0, idx))}<strong>${escapeHtml(text.slice(idx, idx + term.length))}</strong>${escapeHtml(text.slice(idx + term.length))}`;
  }

  function rankOptions(term) {
    var lower = term.toLowerCase();
    return getOptions()
      .filter(o => !o.selected && (!lower || o.textContent.toLowerCase().includes(lower)))
      .sort((a, b) => {
        // Only rank within a group - cross-group pairs return 0, so the
        // stable sort leaves groups in their original (optgroup) order
        // instead of interleaving them alphabetically.
        if (optionGroupLabel(a) !== optionGroupLabel(b)) {
          return 0;
        }
        var aText = a.textContent.toLowerCase();
        var bText = b.textContent.toLowerCase();
        var aRank = aText.startsWith(lower) ? 0 : aText.includes(lower) ? 1 : 2;
        var bRank = bText.startsWith(lower) ? 0 : bText.includes(lower) ? 1 : 2;
        if (aRank !== bRank) {
          return aRank - bRank;
        }
        return aText.localeCompare(bText);
      });
  }

  function renderChips() {
    chipsContainer.querySelectorAll('.searchable_select_chip').forEach(el => el.remove());
    if (!multiple) {
      return;
    }
    var selected = getOptions().filter(o => o.selected);
    input.placeholder = selected.length ? '' : placeholder;
    selected.forEach(option => {
        var chip = document.createElement('span');
        chip.className = 'searchable_select_chip';
        var label = document.createElement('span');
        label.textContent = option.textContent;
        var remove = document.createElement('a');
        remove.href = 'javascript:;';
        remove.className = 'searchable_select_chip_remove';
        remove.innerHTML = '&times;';
        remove.addEventListener('click', event => {
          event.stopPropagation();
          option.selected = false;
          selectEl.dispatchEvent(new Event('change', { bubbles: true }));
          renderChips();
          renderDropdown();
        });
        chip.append(label, remove);
        chipsContainer.insertBefore(chip, input);
      });
  }

  function getResultEls() {
    return Array.from(dropdown.querySelectorAll('.searchable_select_result'));
  }

  function moveActive(delta) {
    var results = getResultEls();
    if (results.length === 0) {
      return;
    }
    if (activeIndex === -1) {
      activeIndex = delta > 0 ? 0 : results.length - 1;
    } else {
      activeIndex = (activeIndex + delta + results.length) % results.length;
    }
    results.forEach(el => el.classList.remove('active'));
    results[activeIndex].classList.add('active');
    results[activeIndex].scrollIntoView({ block: 'nearest' });
  }

  function renderDropdown() {
    activeIndex = -1;
    var term = input.value.trim();
    var ranked = rankOptions(term);
    if (ranked.length === 0) {
      dropdown.classList.add('hidden');
      dropdown.innerHTML = '';
      return;
    }
    var groups = [];
    var groupMap = {};
    ranked.forEach(option => {
      var label = optionGroupLabel(option);
      var key = label || '';
      if (!groupMap[key]) {
        groupMap[key] = { label: label, options: [] };
        groups.push(groupMap[key]);
      }
      groupMap[key].options.push(option);
    });
    // Cap per group rather than the flat list, so a single oversized group
    // (e.g. Genres) can't crowd the others out of the unfiltered view.
    var truncated = false;
    groups.forEach(group => {
      if (!term && group.options.length > UNFILTERED_RESULT_CAP) {
        group.options = group.options.slice(0, UNFILTERED_RESULT_CAP);
        truncated = true;
      }
    });
    dropdown.innerHTML =
      groups
        .map(
          group =>
            (group.label ? `<div class="searchable_select_group_label">${escapeHtml(group.label)}</div>` : '') +
            group.options.map(option => `<div class="searchable_select_result" data-value="${escapeHtml(option.value)}">${highlight(option.textContent, term)}</div>`).join('')
        )
        .join('') + (truncated ? '<div class="searchable_select_hint">Type to narrow down more results&hellip;</div>' : '');
    dropdown.classList.remove('hidden');
  }

  function selectByValue(value) {
    var option = getOptions().find(o => o.value === value);
    if (!option) {
      return;
    }
    if (!multiple) {
      getOptions().forEach(o => {
        o.selected = false;
      });
    }
    option.selected = true;
    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
    if (!multiple) {
      input.value = option.textContent;
      dropdown.classList.add('hidden');
    } else {
      input.value = '';
      renderChips();
      renderDropdown();
    }
  }

  dropdown.addEventListener('click', event => {
    var result = event.target.closest('.searchable_select_result');
    if (!result) {
      return;
    }
    selectByValue(result.dataset.value);
    input.focus();
  });

  input.addEventListener('focus', () => {
    if (!multiple) {
      input.value = '';
    }
    renderDropdown();
  });
  input.addEventListener('input', renderDropdown);
  input.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      dropdown.classList.add('hidden');
      input.blur();
      return;
    }
    if (dropdown.classList.contains('hidden')) {
      return;
    }
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      moveActive(1);
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      moveActive(-1);
    } else if (event.key === 'Enter') {
      event.preventDefault();
      var results = getResultEls();
      if (activeIndex >= 0 && results[activeIndex]) {
        selectByValue(results[activeIndex].dataset.value);
        input.focus();
      }
    }
  });

  document.addEventListener('click', event => {
    if (wrapper.contains(event.target)) {
      return;
    }
    dropdown.classList.add('hidden');
    if (!multiple) {
      var selected = getOptions().find(o => o.selected);
      input.value = selected ? selected.textContent : '';
    }
  });

  // Public API mirroring what the codebase used from Chosen: clear the
  // current selection and refresh the visible chips/label (was:
  // .removeAttr('selected') + .val('') + .trigger('chosen:updated')).
  selectEl.searchableSelectReset = () => {
    getOptions().forEach(o => {
      o.selected = false;
    });
    if (placeholderOption) {
      selectEl.selectedIndex = -1;
    }
    input.value = '';
    renderChips();
  };

  renderChips();
  if (!multiple) {
    var initiallySelected = getOptions().find(o => o.selected);
    input.value = initiallySelected ? initiallySelected.textContent : '';
  }
}
