$.extend(view, {
  getAlbumShouts: function (size) {
    $.ajax({
      data:{
        limit:20,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:size
            },
            success: function (data) {
              $('#albumShout').html(data);
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
  getArtistShouts: function (size) {
    $.ajax({
      data:{
        limit:20,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:size
            },
            success: function (data) {
              $('#artistShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/artist'
    });
  },
  getUserShouts: function (size) {
    $.ajax({
      data:{
        limit:20,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              json_data:data,
              size:size
            },
            success: function (data) {
              $('#userShout').html(data);
            },
            type:'POST',
            url:'/ajax/shoutTable'
          });
        }
      },
      type:'GET',
      url:'/api/shout/get/user'
    });
  },
  getShoutUsers: function () {
    $.ajax({
      data:{
        limit:20,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
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
  initShoutEvents: function () {
    $(document).ajaxStop(function (event, request, settings) {
      $('#shout').append($('.shouts tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#shoutLoader').hide();
    });
  }
});

$(document).ready(function () {
  var size = 32;
  view.getAlbumShouts(size);
  view.getArtistShouts(size);
  view.getUserShouts(size);
  view.getShoutUsers(size);
  view.initShoutEvents();
});