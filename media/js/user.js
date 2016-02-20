$.extend(view, {
  // Get users.
  getUsers: function () {
    $.ajax({
      data:{},
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            type:'POST',
            url:'/ajax/userMosaik',
            data:{
              json_data:data
            },
            success: function (data) {
              $('#userMosaikLoader').hide();
              $('#userMosaik').html(data);
            }
          });
        },
        204: function () { // 204 No Content
          alert('204 No Content');
        },
        404: function () { // 404 Not found
          alert('404 Not Found');
        }
      },
      type:'GET',
      url:'/api/user/get'
    });
  }
});

$(document).ready(function () {
  view.getUsers();
});