$.extend(view, {
  initDateRangePicker: function () {
    $('.date_range_picker').dateRangePicker({
      autoClose: true,
      endDate: '<?=CUR_DATE?>',
      getValue: function() {
        return this.innerHTML;
      },
      hoveringTooltip: false,
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
  populateDayPicker: function (max_day = 31) {
    function ordinal(n) {
      var s = ['th', 'st', 'nd', 'rd'];
      var v = n % 100;
      return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }
    for (var i = 0; i < max_day; i++)  {
      $('.day_selector').append('<option value="' + (i + 1) + '" ' + ((<?=(isset($day) ? $day : 'false')?> === (i + 1)) ? 'selected="selected"' : '') + '>' + ordinal(i + 1) + '</option>');
    }
  },
  populateWeekdayPicker: function () {
    for (var i = 0; i < 7; i++)  {
      $('.weekday_selector').append('<option value="' + (i + 1)  + '" ' + ((<?=(isset($weekday) ? $weekday : 'false')?> === (i)) ? 'selected="selected"' : '') + '>' + moment.weekdays(i + 1) + '</option>');
    }
  },
  initDateFilterHelperEvents: function () {
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
      $('.date_filter_clear').show();
    });
    $('.day_selector').change(function(event) {
      $('.date_filter_clear').show();
    });
    $('.weekday_selector').change(function(event) {
      $('.date_filter_clear').show();
    });
    $('.date_filter_submit').click(function(event) {
      var filter = [];
      if ($('.month_selector option:selected').val() !== '') {
        filter.push('month=' + $('.month_selector option:selected').val())
      }
      if ($('.day_selector option:selected').val() !== '') {
        filter.push('day=' + $('.day_selector option:selected').val())
      }
      if ($('.weekday_selector option:selected').val() !== '') {
        filter.push('weekday=' + $('.weekday_selector option:selected').val())
      }
      if ($('.date_range_picker').html() !== 'All time') {
        var dates = $('.date_range_picker').html().split(' to ');
        filter.push('from=' + dates[0]);
        filter.push('to=' + dates[1]);
      }
      window.location.replace('/music/?' + filter.join('&'));
    });
  }
});

$(document).ready(function () {
  view.populateMonthPicker();
  view.populateDayPicker();
  view.populateWeekdayPicker();
  view.initDateRangePicker();
  view.initDateFilterHelperEvents();
});