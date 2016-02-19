$.extend(view, {
  topGenre: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (30 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topGenreLoader').hide();
              $('#topGenre').html(data);
            },
            type:'POST',
            url:'/ajax/popularTag'
          });
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  topKeyword: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (30 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#topKeywordLoader').hide();
              $('#topKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/popularTag'
          });
        }
      },
      type:'GET',
      url:'/api/keyword/get'
    });
  },
  popularGenre: function () {
    $.ajax({
      data:{
        limit:15,
        lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularGenreLoader').hide();
              $('#popularGenre').html(data);
            },
            type:'POST',
            url:'/ajax/popularTag'
          });  
        },
        204: function() { // 204 No Content
          alert('204 No Content');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  }
});

$(document).ready(function() {
  view.popularGenre();
  view.topGenre();
  view.topKeyword();
});

