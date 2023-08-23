$.extend(view, {
  getTopAlbum: function () {
    $.ajax({
      data:{
        limit:102,
        lower_limit:'1970-00-00',
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
              $('#albumMosaikLoader').hide();
              $('#albumMosaik').html(data);
            },
            type:'POST',
            url:'/ajax/mosaik'
          });
        },
        204: function (data) { // 204 No Content
          $('#albumMosaikLoader').hide();
          $('#albumMosaik').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  }
});

$(document).ready(function () {
  view.getTopAlbum();
  
});
