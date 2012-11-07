function artistEvent() {
  jQuery.ajax({
    type:'POST',
    url:'/api/artistEvent',
    data: {
      limit:15,
      artist_name:'<?php echo $artist_name?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/eventTable',
        data: {
          json_data:data,
        },
        success: function(data) {
          jQuery('#artistEventLoader').hide();
          jQuery('#artistEvent').html(data);
        },
        complete: function() {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
artistEvent();