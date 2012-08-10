function recentlyListened(isFirst) {
  if (isFirst != true) {
    jQuery('#recentlyListenedLoader2').show();
  }
  jQuery.ajax({
    type: 'POST', url: '/api/recentlyListened', 
    data: {
      limit : 100,
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/recentlyListened',
        data: {
          json_data : data,
        },
        success: function(data) {
          jQuery('#recentlyListenedLoader2').hide();
          jQuery('#recentlyListenedLoader').hide();
          jQuery('#recentlyListened').html(data);

          var currentTime = new Date();
          var hours = currentTime.getHours();
          var minutes = currentTime.getMinutes();
          if (minutes < 10) {
            minutes = "0" + minutes;
          }
          jQuery('#recentlyUpdated').html('updated '+ hours + ':' + minutes);
          jQuery('#recentlyUpdated').attr('value', currentTime.getTime());
        },
        complete: function() {
          setTimeout(recentlyListened, 60 * 10 * 1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
recentlyListened(true);