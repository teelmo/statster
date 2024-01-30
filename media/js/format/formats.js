$.extend(view, {
  getFormats: function () {
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
            },
            success: function (data) {
              $('#topListeningFormatTypesLoader').hide();
              $('#topListeningFormatTypes').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListeningFormatTypesLoader').hide();
          $('#topListeningFormatTypes').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  },
  // Get album listenings.
  getListenings: function () {
    $.ajax({
      data:{
        limit:14,
        sub_group_by:'album'
      },
      dataType:'json',
      statusCode:{
        200: function(data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true
              },
              json_data:data,
              size:32
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'<?=(!empty($album_name)) ? '/ajax/userTable' : '/ajax/sideTable'?>'
          });
        },
        204: function() { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function() { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  initFormatsEvents: function () {
  
  }
});

$(document).ready(function () {
  view.getFormats();
  view.initFormatsEvents();
  view.getListenings();
});
