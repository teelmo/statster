$.extend(view, {
  getAlbumShouts: function () {
    $.ajax({
      data:{
        limit:100,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:32
            },
            success: function (data) {
              $('#shoutLoader').hide();
              $('#shout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/album'
    });
  },
  getShoutUsers: function () {
    $.ajax({
      data:{
        limit:20,
        type:'album'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32,
              term:'shouts'
            },
            success: function (data) {
              $('#shoutersLoader').hide();
              $('#shouters').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/users'
    });
  },
  initShoutEvents: function () {
    
  }
});

$(document).ready(function () {
  view.getAlbumShouts();
  view.getShoutUsers();
  view.initShoutEvents();
});