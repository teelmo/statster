$(document).ready(function() {
  topGenre();
  topKeyword();
});

function topGenre() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/genre/get',
    data: {
      limit:7,
      lower_limit:'<?=date("Y-m-d", time() - (30 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/popularTag',
        data: {
          json_data:data
        },
        success: function(data) {
          $('#topGenreLoader').hide();
          $('#topGenre').html(data);
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

function topKeyword() {
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/api/keyword/get',
    data: {
      limit:7,
      lower_limit:'<?=date("Y-m-d", time() - (30 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      $.ajax({
        type:'POST',
        url:'/ajax/popularTag',
        data: {
          json_data:data
        },
        success: function(data) {
          $('#topKeywordLoader').hide();
          $('#topKeyword').html(data);
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
