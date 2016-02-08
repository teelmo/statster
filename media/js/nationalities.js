$.extend(view, {
  topNationality: function () {
    $.ajax({
      dataType:'json',
      data:{
        limit:'0, 200',
        lower_limit:'1970-00-00',
        username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
      },
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              rank:1
            },
            success: function (data) {
              $('#popularNationalityLoader').hide();
              $('#popularNationality').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('<?php echo ERR_NO_DATA?>');
        }
      },
      type:'GET',
      url:'/api/nationality/get'
    });
  }
});

$(document).ready(function () {
  view.topNationality();
});
