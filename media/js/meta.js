$.extend(view, {
  topGenre: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/genre/get',
      data: {
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (30 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode: {
        200: function(data) {
          $.ajax({
            type:'POST',
            url:'/ajax/popularTag',
            data: {
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader').hide();
              $('#topGenre').html(data);
            }
          });
        }
      }
    });
  },
  topKeyword: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/keyword/get',
      data: {
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (30 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode: {
        200: function(data) {
          $.ajax({
            type:'POST',
            url:'/ajax/popularTag',
            data: {
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader').hide();
              $('#topKeyword').html(data);
            }
          });
        }
      }
    });
  },
  popularGenre: function () {
    $.ajax({
      type:'GET',
      dataType:'json',
      url:'/api/genre/get',
      data: {
        limit:15,
        lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode: {
        200: function(data) {
          $.ajax({
            type:'POST',
            url:'/ajax/popularTag',
            data: {
              json_data:data
            },
            success: function(data) {
              $('#popularGenreLoader').hide();
              $('#popularGenre').html(data);
            }
          });  
        },
        204: function() { // 204 No Content
          alert('204 No Content');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      }
    });
  }
});

$(document).ready(function() {
  view.popularGenre();
  view.topGenre();
  view.topKeyword();
});

