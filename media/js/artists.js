function topArtist10() {
  jQuery.ajax({
    type: 'POST', url: '/api/topArtist', 
    data: {
      limit: "0, 8",
      lower_limit: '1970-01-01',
      username: '<?php echo $_GET['username']?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/artistList/124',
        data: {
          json_data : data
        },
        success: function(data) {
          jQuery('#topArtist10').html(data);
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
topArtist10();

function topArtist() {
  jQuery.ajax({
    type: 'POST', url: '/api/topArtist', 
    data: {
      limit: "8, 200",
      lower_limit: '1970-01-01',
      username: '<?php echo $_GET['username']?>'
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/artistBar',
        data: {
          json_data : data,
          size : 32,
          rank : 9
        },
        success: function(data) {
          jQuery('#topArtistLoader').hide();
          jQuery('#topArtist').html(data);
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
topArtist();