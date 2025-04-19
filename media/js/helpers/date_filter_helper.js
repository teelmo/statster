$.extend(view, {
  initDateRangePicker: function () {
    $('.date_range_picker').dateRangePicker({
      autoClose: true,
      container:'.calendar_container',
      customArrowNextSymbol:'<i class="fa fa-angle-right"></i>',
      customArrowPrevSymbol:'<i class="fa fa-angle-left"></i>',
      endDate:'<?=date('Y-m-d',strtotime(CUR_DATE . "+1 days"))?>',
      getValue: function() {
        return this.innerHTML;
      },
      hoveringTooltip: false,
      inline:true,
      setValue: function(s) {
        this.innerHTML = s;
        $('.date_filter_clear').show();
      },
      showShortcuts: false,
      showTopbar: false,
      showWeekNumbers: true,
      startOfWeek: 'monday'
    });
  },
  populateMonthPicker: function () {
    for (var i = 0; i < 12; i++)  {
      $('.month_selector').append('<option value="' + (i + 1) + '" ' + ((<?=(isset($month) ? $month : 'false')?> === (i + 1)) ? 'selected="selected"' : '') + '>' + moment.monthsShort(i) + '</option>');
    }
  },
  populateDayPicker: function () {
    function ordinal(n) {
      var s = ['th', 'st', 'nd', 'rd'];
      var v = n % 100;
      return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }
    for (var i = 0; i < 31; i++)  {
      $('.day_selector').append('<option class="day_option" value="' + (i + 1) + '" ' + ((<?=(isset($day) ? $day : 'false')?> === (i + 1)) ? 'selected="selected"' : '') + '>' + ordinal(i + 1) + '</option>');
    }
  },
  populateWeekdayPicker: function () {
    for (var i = 0; i < 7; i++)  {
      $('.weekday_selector').append('<option value="' + (i + 1)  + '" ' + ((<?=(isset($weekday) ? $weekday : 'false')?> === (i)) ? 'selected="selected"' : '') + '>' + moment.weekdays(i + 1) + '</option>');
    }
  },
  updateDayPicker: function (max_date) {
    $('.day_option').each(function(i) {
      if (i >= max_date) {
        $(this).prop('disabled', true);
        $(this).removeAttr('selected');
      }
      else {
        $(this).removeAttr('disabled');
      }
    });
  },
  initDateFilterHelperEvents: function () {
    function checkIfFilterActive() {
      return ($('.month_selector option:selected').val() === '' && $('.day_selector option:selected').val() === '' && $('.weekday_selector option:selected').val() === '' && $('.date_range_picker').html() === 'All time') ? true : false;
    }
    $('.date_filter_clear').click(function(event) {
      event.stopPropagation();
      $('.date_range_picker').data('dateRangePicker').clear();
      $('.date_range_picker').html('All time');
      $('.month_selector option:selected').removeAttr('selected');
      $('.day_selector option:selected').removeAttr('selected');
      $('.weekday_selector option:selected').removeAttr('selected');
      $('.date_filter_clear').hide();
    });
    $('.month_selector').change(function(event) {
      if ($(this).val() !== '') {
        view.updateDayPicker(new Date(2000, $(this).val(), 0).getDate());
        $('.date_filter_clear').show();
      }
      else if (checkIfFilterActive()) {
        $('.date_filter_clear').hide();
      }
    });
    $('.day_selector').change(function(event) {
      if ($(this).val() !== '') {
        $('.date_filter_clear').show();
      }
      else if (checkIfFilterActive()) {
        $('.date_filter_clear').hide();
      }
    });
    $('.weekday_selector').change(function(event) {
      if ($(this).val() !== '') {
        $('.date_filter_clear').show();
      }
      else if (checkIfFilterActive()) {
        $('.date_filter_clear').hide();
      }
    });
    $('.date_filter_submit').click(function(event) {
      var filter = [];
      if ($('.month_selector option:selected').val() !== '') {
        filter.push('month=' + $('.month_selector option:selected').val());
      }
      if ($('.day_selector option:selected').val() !== '') {
        filter.push('day=' + $('.day_selector option:selected').val());
      }
      if ($('.weekday_selector option:selected').val() !== '') {
        filter.push('weekday=' + $('.weekday_selector option:selected').val());
      }
      if ($('.date_range_picker').html() !== 'All time') {
        var dates = $('.date_range_picker').html().split(' to ');
        filter.push('from=' + dates[0]);
        filter.push('to=' + dates[1]);
      }
      if (filter.length > 0) {
        window.location.replace('/music?' + filter.join('&'));
      }
      else if (window.location.href.split('/')[3] !== 'music' || window.location.href.split('/').length > 3) {
        window.location.replace('/music');
      }
    });
  }
});
$(document).ready(function () {
  view.populateMonthPicker();
  view.populateDayPicker();
  view.populateWeekdayPicker();
  view.updateDayPicker(new Date(2000, <?=(isset($month) ? $month : 'false')?>, 0).getDate());
  view.initDateRangePicker();
  view.initDateFilterHelperEvents();
});