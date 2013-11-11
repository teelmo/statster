$(document).ready(function() {
  getListenings(true, getAlbums);
  getArtists();
  recommentedTopAlbum();
  recommentedNewAlbum();
  highlightPatch();
 
  $('#addListeningText').focus();
  $('#addListeningText').autocomplete({
    minLength:3,html:true,source:'/autoComplete/addListening',
    search: function() {
      $(this).addClass('working');
    },
    open: function() {
      $(this).removeClass('working');
    }
  });

  $('.listeningFormat').click(function() {
    $('.listeningFormat').removeClass('selected');
    $(this).addClass('selected');
  });

  $('.listeningFormat').keypress(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
      $('.listeningFormat').removeClass('selected');
      $(this).addClass('selected');
      $('#' + $(this).parent().attr('for')).prop('checked', true);
    }
  });

  $('#addListeningShowmore').click(function() {
    $('.listeningFormat').removeClass('hidden');
    $(this).remove();
  });

  $('#addListeningShowmore').keypress(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code === 13) {
      $('.listeningFormat').removeClass('hidden');
      $(this).remove();
    }
  });

  $('#recentlyListened').hover(function () {
    var currentTime = new Date();    
    if ((currentTime.getTime() - $('#recentlyUpdated').attr('value') > (60 * 2 * 1000))) {
      getListenings();
    }
  });

  $('#addListeningSubmit').click(function() {
    var text_value = $('#addListeningText').val();
    var format_value = $('input[name="addListeningFormat"]:checked').val()
    $('#recentlyListenedLoader2').show();
    $('#addListeningText').val('');
    $('input[name="addListeningFormat"]').prop('checked', false);
    $('img.listeningFormat').removeClass('selected');
    $.ajax({
      type:'POST',dataType:'json',url:'/api/listening/add',
      data: {
        text:text_value,
        format:format_value,
        date:$('#addListeningDate').val(),
        submitType:$('input[name="submitType"]').val(),
      },
      statusCode: {
        201: function(data) { // 201 Created
          getListenings();
          getArtists();
          getAlbums();
          $('#addListeningText').focus();
        },
        400: function() { // 400 Bad Request
          alert('400 Bad Request');
        },
        401: function() { // 401 Unauthorized
          alert('401 Unauthorized');
        },
        404: function() { // 404 Not found
          alert('404 Not Found');
        }
      }
    });
    return false;
  });
});

function highlightPatch() {
  $.ui.autocomplete.prototype._renderItem = function(ul, item) {
    var t = String(item.label).replace(new RegExp(this.term, 'gi'), '<span class="highlight">$&</span>');
    return $('<li></li>').data('item.autocomplete', item).append('<a>' + t + '</a>').appendTo(ul);
  };
}

function getListenings(isFirst, callback) {
  if (isFirst != true) {
    $('#recentlyListenedLoader2').show();
  }
  $.ajax({
    type:'GET',dataType:'json',url:'/api/listening/get',
    data: {
      limit:11,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        $.ajax({
          type:'POST',url:'/ajax/chartTable',
          data: {
            json_data:data,
            hide: {
              del:true
            }
          },
          success: function(data) {
            $('#recentlyListenedLoader2').hide();
            $('#recentlyListenedLoader').hide();
            $('#recentlyListened').html(data);
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            if (minutes < 10) {
              minutes = '0' + minutes;
            }
            $('#recentlyUpdated').html('updated '+ hours + ':' + minutes);
            $('#recentlyUpdated').attr('value', currentTime.getTime());
          }
        })
      },
      204: function() { // 204 No Content
        $('#recentlyListenedLoader').hide();
        $('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    },
    complete: function() {
      setTimeout(getListenings, 60 * 10 * 1000);
      if (callback != undefined) {
        callback();
      }
    }
  });
}

function getAlbums() {
  $.ajax({
    type:'GET',dataType:'json',url:'/api/album/get',
    data: {
      limit:8,
      lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',url:'/ajax/albumList/124',
          data: {
            json_data:data,
          },
          success: function(data) {
            $('#topAlbumLoader').hide();
            $('#topAlbum').html(data);
          },
          complete: function() {
            setTimeout(getAlbums, 60 * 10 * 1000);
          }
        });
      },
      204: function() { // 204 No Content
        $('#topAlbumLoader').hide();
        $('#topAlbum').html('<?=ERR_NO_RESULTS?>');
      }
    }
  });
}

function getArtists() {
  $.ajax({
    type:'GET',dataType:'json',url:'/api/artist/get',
    data: {
      limit:10,
      lower_limit:'<?=date('Y-m-d', ($interval == 'overall') ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/barTable',
          data: {
            json_data:data,
          },
          success: function(data) {
            $('#topArtistLoader').hide();
            $('#topArtist').html(data);
          },
          complete: function() {
            setTimeout(getArtists, 60 * 10 * 1000);
          }
        });
      },
      204: function() { // 204 No Content
        $('#topArtistLoader').hide();
        $('#topArtist').html('<?=ERR_NO_RESULTS?>');
      }
    }
  });
}

function recommentedTopAlbum() {
  $.ajax({
    type:'GET',dataType:'json',url:'/api/recommentedTopAlbum',
    data: {
      limit:10,
      lower_limit:'<?=date('Y-m-d', time() - (90 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>' 
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/sideTable',
          data: {
            json_data:data,
            limit:3,
            hide: {
              artist:true,
              count:true,
              rank:true,
              date:true,
              calendar:true
            }
          },
          success: function(data) {
            $('#recommentedTopAlbumLoader').hide();
            $('#recommentedTopAlbum').html(data);
          },
          complete: function() {
            setTimeout(recommentedTopAlbum, 60 * 10 * 1000);
          }
        });
      },
      204: function() { // 204 No Content
        $('#recommentedTopAlbumLoader').hide();
        $('#recommentedTopAlbum').html('<?=ERR_NO_RESULTS?>');
      }
    }
  });
}

function recommentedNewAlbum() {
  $.ajax({
    type:'GET',dataType:'json',url:'/api/recommentedNewAlbum',
    data: {
      limit:10,
      order_by:'album.year DESC, album.created DESC',
      lower_limit:'<?=date('Y-m-d', time() - (365 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) {
        $.ajax({
          type:'POST',
          url:'/ajax/sideTable',
          data: {
            json_data:data,
            limit:3,
            hide: {
              artist:true,
              count:true,
              rank:true,
              date:true,
              calendar:true
            }
          },
          success: function(data) {
            $('#recommentedNewAlbumLoader').hide();
            $('#recommentedNewAlbum').html(data);
          },
          complete: function() {
            setTimeout(recommentedNewAlbum, 60 * 10 * 1000);
          }
        });
      },
      204: function() { // 204 No Content
        $('#recommentedNewAlbumLoader').hide();
        $('#recommentedNewAlbum').html('<?=ERR_NO_RESULTS?>');
      }
    }
  });
}

$(function() {
  $('#addListeningDate').datepicker({
    dateFormat:'yy-mm-dd',maxDate:'today',showOtherMonths:true,selectOtherMonths:true,showAnim:'slideDown',firstDay:1
  });
  $('#addListeningDate').change(function() {
    setTimeout(function() {
      $('#addListeningDate').val('<?=CUR_DATE?>');
    }, 60 * 10 * 1000);
  });
});

$(function() {
  var keyStop = {
    8:':not(input:text,textarea,input:file,input:password)',
    13:'input:text,input:password',
    end: null
  };
  $(document).bind('keydown', function(event) {
    var selector = keyStop[event.which];
    if (selector !== undefined && $(event.target).is(selector)) {
      event.preventDefault();
    }
    return true;
  });
});