$.extend(view, {
  popularGenre: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
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
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/genre/get'
    });
  },
  popularKeyword: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularKeywordLoader').hide();
              $('#popularKeyword').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
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
      url:'/api/keyword/get'
    });
  },
  popularNationality: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularNationalityLoader').hide();
              $('#popularNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
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
      url:'/api/nationality/get'
    });
  },
  popularYear: function () {
    $.ajax({
      data:{
        limit:7,
        lower_limit:'<?=date('Y-m-d', time() - (180 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function(data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function(data) {
              $('#popularYearLoader').hide();
              $('#popularYear').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
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
      url:'/api/year/get'
    });
  }
});

$(document).ready(function() {
  view.popularGenre();
  view.popularKeyword();
  view.popularNationality();
  view.popularYear();
});

