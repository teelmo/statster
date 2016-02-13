$.extend(view, {
  topTags: function (container, type, select) {
    $.ajax({
      data:{
        limit:5,
        lower_limit:'1970-00-00',
        select:select,
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              rank:1
            },
            success: function (data) {
              $('#' + container + 'Loader').hide();
              $('#' + container).html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function (data) { // 204 No Content
          $('#' + container + 'Loader').hide();
          $('#' + container).html('<?php echo ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/' + type + '/get'
    });
  }
});


$(document).ready(function () {
  view.topTags('topGenre', 'genre');
  view.topTags('topKeyword', 'keyword');
  view.topTags('topNationality', 'nationality');
  view.topTags('topYear', 'year', '<?=TBL_album?>.`year` as `name`');
});