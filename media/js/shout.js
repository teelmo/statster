$.extend(view, {
  getAlbumShouts: function () {
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
              type:'artist'
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
  getArtistShouts: function () {
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
              type:'artist'
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
  getUserShouts: function () {
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
              type:'user'
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
  initShoutEvents: function () {
    $(document).ajaxStop(function (event, request, settings) {
      $('#musicShout').append($('.shouts tr').detach().sort(function (a, b) {
        return app.compareStrings($(a).data('created'), $(b).data('created'));
      }));
      $('#musicShoutLoader').hide();
    });
  }
});

$(document).ready(function () {
  view.getAlbumShouts();
  view.getArtistShouts();
  view.getUserShouts();
  view.initShoutEvents();
});