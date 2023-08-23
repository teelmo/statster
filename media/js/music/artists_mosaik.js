$.extend(view, {
  getTopArtist: function () {
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
              type:'artist'
            },
            success: function (data) {
              $('#artistMosaikLoader').hide();
              $('#artistMosaik').html(data);
            },
            type:'POST',
            url:'/ajax/mosaik'
          });
        },
        204: function (data) { // 204 No Content
          $('#artistMosaikLoader').hide();
          $('#artistMosaik').html('<?=ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/artist/get'
    });
  }
});

$(document).ready(function () {
  view.getTopArtist();
  
});
