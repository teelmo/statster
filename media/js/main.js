$.extend(view, {
  // Get recent listenings.
  getRecentListenings: function (isFirst, callback) {
    if (isFirst != true) {
      $('#recentlyListenedLoader2').show();
    }
    $.ajax({
      complete: function () {
        // setTimeout(view.getRecentListenings, 60 * 10 * 1000);
        if (callback != undefined) {
          callback();
        }
      },
      data:{
        limit:12,
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) { // 200 OK
          $.ajax({
            data:{
              hide:{
                del:true
              },
              json_data:data
            },
            success: function (data) {
              $('#recentlyListenedLoader2').hide();
              $('#recentlyListenedLoader').hide();
              $('#recentlyListened').html(data);
              var currentTime = new Date();
              var hours = currentTime.getHours();
              var minutes = currentTime.getMinutes();
              if (minutes < 10) {
                minutes = '0' + minutes;
              }
              $('#recentlyUpdated').html('updated <span class="number">' + hours + '</span>:<span class="number">' + minutes + '</span>');
              $('#recentlyUpdated').attr('value', currentTime.getTime());
            },
            type:'POST',
            url:'/ajax/musicTable'
          });
        },
        204: function () { // 204 No Content
          $('#recentlyListenedLoader').hide();
          $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
        },
        400: function (data) {alert('400 Bad Request')}
      },
      type:'GET',
      url:'/api/listening/get'
    });
  },
  // Get top albums.
  getTopAlbums: function () {
    $.ajax({
      data:{
        limit:10,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            complete: function () {
              // setTimeout(view.getTopAlbums, 60 * 10 * 1000);
            },
            data:{
              json_data:data,
              limit:9,
              type:'album'
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/musicWall'
          });
        },
        204: function () { // 204 No Content
          $('#topAlbumLoader').hide();
          $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/album/get'
    });
  },
  // Get top artists.
  getTopArtists: function () {
    $.ajax({
      data:{
        limit:10,
        lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            complete: function () {
              // setTimeout(view.getTopArtists, 60 * 10 * 1000);
            },
            data:{
              json_data:data
            },
            success: function (data) {
              $('#topArtistLoader').hide();
              $('#topArtist').html(data);
            },
            type:'POST',
            url:'/ajax/columnTable'
          });
        },
        204: function () { // 204 No Content
          $('#topArtistLoader').hide();
          $('#topArtist').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/artist/get'
    });
  },
  // Get recommented top albums.
  getRecommentedTopAlbum: function () {
    $.ajax({
      data:{
        limit:15,
        lower_limit:'<?=date('Y-m-d', time() - (90 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            complete: function () {
              setTimeout(view.recommentedTopAlbum, 60 * 10 * 1000);
            },
            data:{
              json_data:data,
              hide:{
                artist:true,
                calendar:true,
                count:true,
                date:true,
                rank:true
              },
              limit:4
            },
            success: function (data) {
              $('#recommentedTopAlbumLoader').hide();
              $('#recommentedTopAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function () { // 204 No Content
          $('#recommentedTopAlbumLoader').hide();
          $('#recommentedTopAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/recommentedTopAlbum'
    });
  },
  // Get recommented new albums.
  getRecommentedNewAlbum: function () {
    $.ajax({
      data:{
        limit:15,
        order_by:'album.year DESC, album.created DESC',
        lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
        username:'<?=(!empty($_GET['u'])) ? $_GET['u'] : ''?>'
      },
      dataType:'json',
      statusCode:{
        200: function (data) {
          $.ajax({
            complete: function () {
              setTimeout(view.recommentedNewAlbum, 60 * 10 * 1000);
            },
            data:{
              json_data:data,
              hide:{
                artist:true,
                calendar:true,
                count:true,
                date:true,
                rank:true
              },
              limit:4
            },
            success: function (data) {
              $('#recommentedNewAlbumLoader').hide();
              $('#recommentedNewAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/sideTable'
          });
        },
        204: function () { // 204 No Content
          $('#recommentedNewAlbumLoader').hide();
          $('#recommentedNewAlbum').html('<?=ERR_NO_RESULTS?>');
        }
      },
      type:'GET',
      url:'/api/recommentedNewAlbum'
    });
  },
  initMainEvents: function () {
    // Recently listened hover keypress.
    $('#recentlyListened').hover(function () {
      var currentTime = new Date();    
      if ((currentTime.getTime() - $('#recentlyUpdated').attr('value') > (60 * 2 * 1000)) && $.active < 1) {
        view.getRecentListenings();
      }
    });
    $('#refreshRecentAlbums').click(function () {
      view.getRecentListenings();
    });
    $('#refreshPopularAlbums').click(function () {
      view.getRecommentedTopAlbum();
    });
    $('#refreshNewAlbums').click(function () {
      view.getRecommentedNewAlbum();
    });
    // $('#addListeningShowmore').click(function () {
    //   $('.listeningFormat').removeClass('hidden');
    //   $(this).remove();
    // });

    // $('#addListeningShowmore').keypress(function (e) {
    //   var code = (e.keyCode ? e.keyCode : e.which);
    //   if (code === 13) {
    //     $('.listeningFormat').removeClass('hidden');
    //     $(this).remove();
    //   }
    // });
  }
});

$(document).ready(function () {
  view.getRecentListenings(true, view.getTopAlbums);
  view.getTopArtists();
  view.getRecommentedTopAlbum();
  view.getRecommentedNewAlbum();
  view.initMainEvents();
});
