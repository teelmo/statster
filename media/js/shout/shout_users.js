$.extend(view, {
  getUserShouts: function () {
    $.ajax({
      data:{
        limit:100,
        type:'user',
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
      url:'/api/shout/get/user'
    });
  },
  getShoutUsers: function () {
    $.ajax({
      data:{
        limit:20,
        type:'user'
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
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.getUserShouts();
  view.getShoutUsers();
  view.initShoutEvents();
});