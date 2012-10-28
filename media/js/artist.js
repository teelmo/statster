function artistFan() {
  jQuery.ajax({
    type: 'POST', url: '/api/artistFan', 
    data: {
      artist_id : '<?=$artist_id?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/artistFan',
        data: {
          json_data : data
        },
        success: function(data) {
          jQuery('#artistFanLoader').hide();
          jQuery('#artistFan').html(data);
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
artistFan();