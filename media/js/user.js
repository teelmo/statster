$.extend(view, {
  // Get users.
  getUsers: function () {
    $.ajax({
      data:{},
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#userMosaikLoader').hide();
              $('#userMosaik').html(data);
            },
            type:'POST',
            url:'/ajax/userMosaik'
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
  },
  topListeners: function () {
    $.ajax({
      data:{
        limit:10
      },
      dataType:'json',
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                calendar:true,
                date:true
              },
              json_data:data,
              size:32,
              type:'user'
            },
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  getAlbumShouts: function (size) {
    $.ajax({
      data:{
        limit:3,
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
        limit:3,
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
        limit:3,
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
  initUserEvents: function () {
    $(document).ajaxStop(function (event, request, settings) {
      $('#musicShout').append($('.shouts tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#musicShoutLoader').hide();
    });
  }
});

$(document).ready(function () {
  view.getUsers();
  var size = 32;
  view.getAlbumShouts(size);
  view.getArtistShouts(size);
  view.getUserShouts(size);
  view.topListeners();
  view.initUserEvents();
});