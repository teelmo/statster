$.extend(view, {
  getTopAlbum: function (lower_limit, upper_limit = false) {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      else {
        var date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>'
    }
    $.ajax({
      data:{
        limit:102,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data,
              type:'album'
            },
            success: function (data) {
              $('#albumMosaicLoader, #albumMosaicLoader2').hide();
              $('#albumMosaic').html(data);
            },
            type:'POST',
            url:'/ajax/mosaic'
          });
        },
        204: function (data) { // 204 No Content
          $('#albumMosaicLoader, #albumMosaicLoader2').hide();
          $('#albumMosaic').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  }
});

$(document).ready(function () {
  view.getTopAlbum('<?=$lower_limit?>', '<?=$upper_limit?>');
});
