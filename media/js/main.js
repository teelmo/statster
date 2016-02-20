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
            url:'/ajax/chartTable'
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
              json_data:data
            },
            success: function (data) {
              $('#topAlbumLoader').hide();
              $('#topAlbum').html(data);
            },
            type:'POST',
            url:'/ajax/albumList/124'
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
                rank:true,
                spotify:true
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
                rank:true,
                spotify:true
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
  initAutocomplete: function () {
    $('#addListeningText').focus();
    $('#addListeningText').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      source:'/autoComplete/addListening',
      search: function () {
        $(this).addClass('working');
      }
    });
  },
  initDatepicker: function () {
    $('#addListeningDate').datepicker({
      dateFormat:'yy-mm-dd',
      firstDay:1,
      maxDate:'today',
      selectOtherMonths:true,
      showAnim:'slideDown',
      showOtherMonths:true
    });
    $('#addListeningDate').change(function () {
      setTimeout(function () {
        $('#addListeningDate').val('<?=CUR_DATE?>');
      }, 60 * 2 * 1000);
    });
  },
  initKeystop: function () {
    var keyStop = {
      8:':not(input:text,textarea,input:file,input:password)',
      13:'input:text,input:password',
      end:null
    }
    $(document).bind('keydown', function (event) {
      var selector = keyStop[event.which];
      if (selector !== undefined && $(event.target).is(selector)) {
        event.preventDefault();
      }
      return true;
    });
  },
  initMainEvents: function () {
    // Listening format click.
    $('.listening_format').click(function () {
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
      }
      else {
        $('.listening_format').removeClass('selected');
        $(this).addClass('selected');
      }
    });
    // Listening format keypress.
    $('.listening_format').keypress(function (e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13) {
        if ($(this).hasClass('selected')) {
          $(this).removeClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', false);
        }
        else {
          $('.listening_format').removeClass('selected');
          $(this).addClass('selected');
          $('#' + $(this).parent().attr('for')).prop('checked', true);
        }
      }
    });
    $('#addListeningSubmit').click(function () {
      var text_value = $('#addListeningText').val();
      var format_value = $('input[name="addListeningFormat"]:checked').val()
      $('#recentlyListenedLoader2').show();
      $('#addListeningText').val('');
      $('input[name="addListeningFormat"]').prop('checked', false);
      $('.listening_format').removeClass('selected');
      $.ajax({
        data:{
          date:$('#addListeningDate').val(),
          format:format_value,
          submitType:$('input[name="submitType"]').val(),
          text:text_value
        },
        dataType:'json',
        statusCode:{
          201: function (data) { // 201 Created
            view.getRecentListenings();
            view.getTopArtists();
            view.getTopAlbums();
            $('#addListeningText').focus();
          },
          400: function () { // 400 Bad Request
            alert('400 Bad Request');
            $('#recentlyListenedLoader2').hide();
          },
          401: function () { // 401 Unauthorized
            alert('401 Unauthorized');
            $('#recentlyListenedLoader2').hide();
          },
          404: function () { // 404 Not found
            alert('404 Not Found');
            $('#recentlyListenedLoader2').hide();
          }
        },
        type:'POST',
        url:'/api/listening/add'
      });
      return false;
    });
    // Recently listened hover keypress.
    $('#recentlyListened').hover(function () {
      var currentTime = new Date();    
      if ((currentTime.getTime() - $('#recentlyUpdated').attr('value') > (60 * 2 * 1000)) && $.active < 1) {
        view.getRecentListenings();
      }
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
  view.initAutocomplete();
  view.initDatepicker();
  view.initKeystop();
  view.initMainEvents();
});
