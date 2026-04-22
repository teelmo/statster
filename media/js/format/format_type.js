$.extend(view, {
  getTopAlbum10: function (lower_limit, upper_limit = false) {
    if (!upper_limit) {
      if (lower_limit === 'overall') {
        lower_limit = '1970-00-00';
      }
      else {
        var date = new Date();
        date.setDate(date.getDate() - parseInt(lower_limit));
        lower_limit = date.toISOString().split('T')[0];
      }
      upper_limit = '<?=CUR_DATE?>'
    }
    $.ajax({
      data:{
        format_name:'<?=$format_name?>',
        format_type_name:'<?=$format_type_name?>',
        hide:{},
        limit:8,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topAlbum10Loader, #topAlbum10Loader2').hide();
              $('#topAlbum10').html(data);
            },
            type:'POST',
            url:'/ajax/albumList'
          });
        },
        204: function (data) { // 204 No Content
          console.log(data)
          $('#topAlbum10Loader, #topAlbum10Loader2').hide();
          $('#topAlbum10').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
    var vars = {
      container:'#topAlbum',
      limit:'8, 192',
      template:'/ajax/columnTable'
    }
    view.getTopAlbum(lower_limit, upper_limit, vars);
  },
  getTopAlbum: function (lower_limit, upper_limit, vars) {
    $.ajax({
      data:{
        format_name:'<?=$format_name?>',
        format_type_name:'<?=$format_type_name?>',
        limit:vars.limit,
        lower_limit:lower_limit,
        upper_limit:upper_limit,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:vars.hide,
              json_data:data,
              rank:9,
              size:32
            },
            success: function (data) {
              $(vars.container + 'Loader').hide();
              $(vars.container).html(data);
            },
            type:'POST',
            url:vars.template
          });
        },
        204: function (data) { // 204 No Content
          $(vars.container + 'Loader').hide();
          $(vars.container).html('');
        }
      },
      type:'GET',
      url:'/api/format/get'
    });
  },
  // Get format type listeners.
  getUsers: function (from, where) {
    $.ajax({
      data:{
        from:from,
        limit:10,
        sub_group_by:'album',
        where:where
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
              size:32
            },
            success: function(data) {
              $('#topListenerLoader').hide();
              $('#topListener').html(data);
            },
            type:'POST',
            url:'/ajax/userTable'
          });
        },
        204: function () { // 204 No Content
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#topListenerLoader').hide();
          $('#topListener').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listener/get'
    });
  },
  // Get format type listenings.
  getListenings: function (from, where) {
    $.ajax({
      data:{
        from:from,
        limit:15,
        sub_group_by:'album',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>',
        where:where
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                artist:true,
                count:true,
                rank:true,
                spotify:true
              },
              json_data:data,
              size:32
            },
            success: function(data) {
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function () { // 400 Bad request
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_BAD_REQUEST?>');
        }
      },
      type:'GET',
      url:'/api/listening/get'
    });
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');
  view.getTopAlbum10('<?=$lower_limit?>');
  view.getTopAlbum10('<?=$lower_limit?>');
  var from  = '(SELECT listening_format_types.listening_id FROM listening_format_types WHERE listening_format_types.listening_format_type_id = <?=$format_type_id?>) AS listening_format_types';
  var where = 'listening_format_types.listening_id = <?=TBL_listening?>.id';

  view.getUsers(from, where);
  view.getListenings(from, where);
});
