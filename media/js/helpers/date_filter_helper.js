Object.assign(view, {
  getMonthShort: i => new Intl.DateTimeFormat('en-US', { month: 'short' }).format(new Date(2000, i, 1)),
  // Hand-rolled replacement for jquery.daterangepicker.min.js's inline
  // two-month range picker. Reuses the plugin's own CSS classes/markup shape
  // (date-picker-wrapper, month-wrapper, day/week-number cells, etc. - see
  // media/css/libs/daterangepicker.min.css) so no new CSS was needed, only
  // this file generating the same DOM shape by hand.
  initDateRangePicker: () => {
    var container = document.querySelector('.calendar_container');
    var trigger = document.querySelector('.date_range_picker');
    var endBoundParts = `<?=date('Y-m-d', strtotime(CUR_DATE . "+1 days"))?>`.split('-').map(Number);
    var endBound = new Date(endBoundParts[0], endBoundParts[1] - 1, endBoundParts[2]);
    var rangeStart = null;
    var rangeEnd = null;
    var viewMonth = new Date(endBound.getFullYear(), endBound.getMonth() - 1, 1);

    var pad = n => String(n).padStart(2, '0');
    var toISO = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    var atMidnight = d => new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
    var parseISO = s => {
      var parts = s.split('-').map(Number);
      return new Date(parts[0], parts[1] - 1, parts[2]);
    };

    var isoWeekNumber = date => {
      var d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
      var dayNum = (d.getUTCDay() + 6) % 7;
      d.setUTCDate(d.getUTCDate() - dayNum + 3);
      var firstThursday = new Date(Date.UTC(d.getUTCFullYear(), 0, 4));
      var diff = d - firstThursday;
      return 1 + Math.round(diff / (7 * 24 * 60 * 60 * 1000));
    };

    var buildMonthTable = (monthDate, tableClass, showPrev, showNext) => {
      var year = monthDate.getFullYear();
      var month = monthDate.getMonth();
      var firstOfMonth = new Date(year, month, 1);
      var startOffset = (firstOfMonth.getDay() + 6) % 7;
      var gridStart = new Date(year, month, 1 - startOffset);
      var daysInMonth = new Date(year, month + 1, 0).getDate();
      var totalWeeks = Math.ceil((startOffset + daysInMonth) / 7);

      var rows = '';
      for (var w = 0; w < totalWeeks; w++) {
        var weekStart = new Date(gridStart);
        weekStart.setDate(gridStart.getDate() + w * 7);
        var cells = `<td><div class="week-number">${isoWeekNumber(weekStart)}</div></td>`;
        for (var i = 0; i < 7; i++) {
          var cellDate = new Date(gridStart);
          cellDate.setDate(gridStart.getDate() + w * 7 + i);
          var monthClass = cellDate.getMonth() === month ? 'toMonth' : cellDate < firstOfMonth ? 'lastMonth' : 'nextMonth';
          var isValid = atMidnight(cellDate) <= atMidnight(endBound);
          var classes = ['day', monthClass, isValid ? 'valid' : 'invalid'];
          if (isValid) {
            if (atMidnight(cellDate) === atMidnight(new Date())) {
              classes.push('real-today');
            }
            if (rangeStart && rangeEnd) {
              var lo = Math.min(atMidnight(rangeStart), atMidnight(rangeEnd));
              var hi = Math.max(atMidnight(rangeStart), atMidnight(rangeEnd));
              var t = atMidnight(cellDate);
              if (t === lo) {
                classes.push('checked', 'first-date-selected');
              } else if (t === hi) {
                classes.push('checked', 'last-date-selected');
              } else if (t > lo && t < hi) {
                classes.push('checked');
              }
            } else if (rangeStart && atMidnight(cellDate) === atMidnight(rangeStart)) {
              classes.push('checked', 'first-date-selected');
            }
          }
          cells += `<td><div class="${classes.join(' ')}" data-date="${toISO(cellDate)}">${cellDate.getDate()}</div></td>`;
        }
        rows += `<tr>${cells}</tr>`;
      }

      var monthName = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(monthDate).toLowerCase();
      return `
        <table class="${tableClass}">
          <thead>
            <tr class="caption">
              <th>${showPrev ? '<span class="prev"><i class="fa fa-angle-left"></i></span>' : ''}</th>
              <th class="month-name" colspan="6"><div class="month-element">${monthName}</div></th>
              <th>${showNext ? '<span class="next"><i class="fa fa-angle-right"></i></span>' : ''}</th>
            </tr>
            <tr class="week-name">
              <th>W</th><th>mo</th><th>tu</th><th>we</th><th>th</th><th>fr</th><th>sa</th><th>su</th>
            </tr>
          </thead>
          <tbody>${rows}</tbody>
        </table>
      `;
    };

    var render = () => {
      var month2 = new Date(viewMonth.getFullYear(), viewMonth.getMonth() + 1, 1);
      container.innerHTML = `
        <div class="date-picker-wrapper no-shortcuts no-topbar inline-wrapper no-gap two-months">
          <div class="month-wrapper">
            ${buildMonthTable(viewMonth, 'month1', true, false)}
            <div class="gap"></div>
            ${buildMonthTable(month2, 'month2', false, true)}
            <div class="dp-clearfix"></div>
          </div>
        </div>
      `;
    };

    var onOutsideClick = event => {
      if (!container.contains(event.target) && event.target !== trigger) {
        close();
      }
    };

    var close = () => {
      container.innerHTML = '';
      document.removeEventListener('click', onOutsideClick, true);
    };

    var open = () => {
      viewMonth = new Date((rangeStart || endBound).getFullYear(), (rangeStart || endBound).getMonth() - (rangeStart ? 0 : 1), 1);
      render();
      setTimeout(() => {
        document.addEventListener('click', onOutsideClick, true);
      }, 0);
    };

    trigger.addEventListener('click', event => {
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
      var clicked = parseISO(day.dataset.date);
      if (!rangeStart || rangeEnd) {
        rangeStart = clicked;
        rangeEnd = null;
        render();
      } else if (clicked < rangeStart) {
        rangeEnd = rangeStart;
        rangeStart = clicked;
        trigger.textContent = `${toISO(rangeStart)} to ${toISO(rangeEnd)}`;
        document.querySelector('.date_filter_clear').style.display = '';
        document.querySelector('.date_filter_clear').classList.remove('hidden');
        close();
      } else {
        rangeEnd = clicked;
        trigger.textContent = `${toISO(rangeStart)} to ${toISO(rangeEnd)}`;
        document.querySelector('.date_filter_clear').style.display = '';
        document.querySelector('.date_filter_clear').classList.remove('hidden');
        close();
      }
    });

    view.clearDateRangePicker = () => {
      rangeStart = null;
      rangeEnd = null;
      trigger.textContent = 'All time';
      close();
    };
  },
  populateMonthPicker: () => {
    var monthSelector = document.querySelector('.month_selector');
    for (i = 0; i < 12; i++) {
      var option = document.createElement('option');
      option.value = i + 1;
      option.textContent = view.getMonthShort(i);
      if (parseInt(`<?=(isset($month) ? $month : 'false')?>`, 10) === i + 1) {
        option.selected = true;
      }
      monthSelector.append(option);
    }
  },
  populateDayPicker: () => {
    function ordinal(n) {
      var s = ['th', 'st', 'nd', 'rd'];
      var v = n % 100;
      return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }
    var daySelector = document.querySelector('.day_selector');
    for (i = 0; i < 31; i++) {
      var option = document.createElement('option');
      option.className = 'day_option';
      option.value = i + 1;
      option.textContent = ordinal(i + 1);
      if (parseInt(`<?=(isset($day) ? $day : 'false')?>`, 10) === i + 1) {
        option.selected = true;
      }
      daySelector.append(option);
    }
  },
  populateWeekdayPicker: () => {
    // 2000-01-03 was a Monday (UTC), so Date.UTC(2000, 0, 3 + i) walks
    // Monday..Sunday for i = 0..6, matching startOfWeek: monday.
    var weekdaySelector = document.querySelector('.weekday_selector');
    for (i = 0; i < 7; i++) {
      var option = document.createElement('option');
      option.value = i + 1;
      option.textContent = new Intl.DateTimeFormat('en-US', { weekday: 'long', timeZone: 'UTC' }).format(new Date(Date.UTC(2000, 0, 3 + i)));
      if (parseInt(`<?=(isset($weekday) ? $weekday : 'false')?>`, 10) === i) {
        option.selected = true;
      }
      weekdaySelector.append(option);
    }
  },
  updateDayPicker: max_date => {
    document.querySelectorAll('.day_option').forEach((el, i) => {
      if (i >= max_date) {
        el.disabled = true;
        el.removeAttribute('selected');
      } else {
        el.disabled = false;
      }
    });
  },
  initDateFilterHelperEvents: () => {
    function checkIfFilterActive() {
      var monthSelected = document.querySelector('.month_selector').value;
      var daySelected = document.querySelector('.day_selector').value;
      var weekdaySelected = document.querySelector('.weekday_selector').value;
      return !!(monthSelected === '' && daySelected === '' && weekdaySelected === '' && document.querySelector('.date_range_picker').textContent === 'All time');
    }
    document.querySelector('.date_filter_clear').addEventListener('click', event => {
      event.stopPropagation();
      view.clearDateRangePicker();
      document.querySelectorAll('.month_selector option:checked, .day_selector option:checked, .weekday_selector option:checked').forEach(el => {
        el.selected = false;
      });
      document.querySelector('.date_filter_clear').style.display = 'none';
    });
    document.querySelector('.month_selector').addEventListener('change', function () {
      if (this.value !== '') {
        view.updateDayPicker(new Date(2000, this.value, 0).getDate());
        document.querySelector('.date_filter_clear').style.display = '';
        document.querySelector('.date_filter_clear').classList.remove('hidden');
      } else if (checkIfFilterActive()) {
        document.querySelector('.date_filter_clear').style.display = 'none';
      }
    });
    document.querySelector('.day_selector').addEventListener('change', function () {
      if (this.value !== '') {
        document.querySelector('.date_filter_clear').style.display = '';
        document.querySelector('.date_filter_clear').classList.remove('hidden');
      } else if (checkIfFilterActive()) {
        document.querySelector('.date_filter_clear').style.display = 'none';
      }
    });
    document.querySelector('.weekday_selector').addEventListener('change', function () {
      if (this.value !== '') {
        document.querySelector('.date_filter_clear').style.display = '';
        document.querySelector('.date_filter_clear').classList.remove('hidden');
      } else if (checkIfFilterActive()) {
        document.querySelector('.date_filter_clear').style.display = 'none';
      }
    });
    document.querySelector('.date_filter_submit').addEventListener('click', () => {
      var filter = [];
      var monthSelected = document.querySelector('.month_selector').value;
      var daySelected = document.querySelector('.day_selector').value;
      var weekdaySelected = document.querySelector('.weekday_selector').value;
      if (monthSelected !== '') {
        filter.push(`month=${monthSelected}`);
      }
      if (daySelected !== '') {
        filter.push(`day=${daySelected}`);
      }
      if (weekdaySelected !== '') {
        filter.push(`weekday=${weekdaySelected}`);
      }
      if (document.querySelector('.date_range_picker').textContent !== 'All time') {
        const dates = document.querySelector('.date_range_picker').textContent.split(' to ');
        filter.push(`from=${dates[0]}`);
        filter.push(`to=${dates[1]}`);
      }
      if (filter.length > 0) {
        window.location.replace(`/music?${filter.join('&')}`);
      } else if (window.location.href.split('/')[3] !== 'music' || window.location.href.split('/').length > 3) {
        window.location.replace('/music');
      }
    });
  }
});
view.populateMonthPicker();
view.populateDayPicker();
view.populateWeekdayPicker();
view.updateDayPicker(new Date(2000, `<?=(isset($month) ? $month : 'false')?>`, 0).getDate());
view.initDateRangePicker();
view.initDateFilterHelperEvents();
