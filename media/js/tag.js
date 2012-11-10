function topGenre() {
  jQuery.ajax({
    type:'GET', 
    url:'/api/genre/get', 
    data: {
      limit:7,
      lower_limit:'<?=date("Y-m-d", time() - (30 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/popularTag',
        data: {
          json_data:data
        },
        success: function(data) {
          jQuery('#topGenreLoader').hide();
          jQuery('#topGenre').html(data);
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
topGenre();

function topKeyword() {
  jQuery.ajax({
    type:'GET',
    url:'/api/keyword/get',
    data: {
      limit:7,
      lower_limit:'<?=date("Y-m-d", time() - (30 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/popularTag',
        data: {
          json_data:data
        },
        success: function(data) {
          jQuery('#topKeywordLoader').hide();
          jQuery('#topKeyword').html(data);
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
topKeyword();