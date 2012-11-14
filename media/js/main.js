jQuery(document).ready(function() {
  jQuery('#addListeningText').focus();
  jQuery('#addListeningText').autocomplete({
    serviceUrl:'/autoComplete/addListening',
    minChars:3,
    maxHeight:312,
    onSelect: function(value, ac_data) {
      jQuery('#addListeningText').value = ac_data;
    },
    observableElement:{},
    images:true,
    list:true
  });

  jQuery(".listeningFormat").click(function() {
    jQuery(".listeningFormat").removeClass('selected');
    jQuery(this).addClass('selected');
  });
  
  jQuery(".listeningFormat").keypress(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
     if (code == 13) {
        jQuery(".listeningFormat").removeClass('selected');
        jQuery(this).addClass('selected');

        jQuery('#' + jQuery(this).parent().attr("for")).prop('checked', true);
     }
  });

  jQuery("#addListeningShowmore").click(function() {
    jQuery(".listeningFormat").removeClass('hidden');
    jQuery(this).remove();
  });

  jQuery("#addListeningShowmore").keypress(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
      jQuery(".listeningFormat").removeClass('hidden');
      jQuery(this).remove();
    }
  });

  jQuery("#recentlyListened").hover(function () {
    var currentTime = new Date();    
    if ((currentTime.getTime() - jQuery("#recentlyUpdated").attr('value') > (60 * 2 * 1000))) {
      getListenings();
    }
  });

  jQuery("#addListeningSubmit").click(function() {
    jQuery.ajax({
      type:'POST',
      url:'/api/listening/add',
      data: {
        text:jQuery('#addListeningText').val(),
        date:jQuery('#addListeningDate').val(),
        format:jQuery('input[name="addListeningFormat"]:checked').val(),
        submitType:jQuery('input[name="submitType"]').val(),
      },
      statusCode: {
        201: function(data) { // 200 OK
          jQuery('#addListeningText').val('');
          jQuery('input[name="addListeningFormat"]').prop('checked', false);
          jQuery('img.listeningFormat').removeClass('selected');
          getListenings();
          topAlbum();
          topArtist();
          jQuery('#addListeningText').focus();
        },
        400: function(data) {alert('400 Bad Request')},
        401: function(data) {alert('401 Unauthorized')},
        404: function(data) {alert('404 Not Found')}
      }
    });
    return false;
  });
});


function getListenings(isFirst) {
  if (isFirst != true) {
    jQuery('#recentlyListenedLoader2').show();
  }
  jQuery.ajax({
    type:'GET',
    url:'/api/listening/get',
    data: {
      limit:11,
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    statusCode: {
      200: function(data) { // 200 OK
        jQuery.ajax({
          type:'POST',
          url:'/ajax/chartTable',
          data: {
            json_data:data,
            hide: {
              del:true
            }
          },
          success: function(data) {
            jQuery('#recentlyListenedLoader2').hide();
            jQuery('#recentlyListenedLoader').hide();
            jQuery('#recentlyListened').html(data);

            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            if (minutes < 10) {
              minutes = "0" + minutes;
            }
            jQuery('#recentlyUpdated').html('updated '+ hours + ':' + minutes);
            jQuery('#recentlyUpdated').attr('value', currentTime.getTime());
          }
        })
      },
      204: function() { // 204 No Content
        jQuery('#recentlyListenedLoader').hide();
        jQuery('#recentlyListened').html('<?=ERR_NO_RESULTS?>');
      },
      400: function(data) {alert('400 Bad Request')}
    },
    complete: function() {
      setTimeout(getListenings, 60 * 10 * 1000);
    }
  });
}
getListenings(true);

function getAlbums() {
  jQuery.ajax({
    type:'GET',
    url:'/api/album/get',
    data: {
      limit:8,
      lower_limit:'<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumList/124',
        data: {
          json_data:data,
        },
        success: function(data) {
          jQuery('#topAlbumLoader').hide();
          jQuery('#topAlbum').html(data); 
        },
        complete: function() {
          setTimeout(getAlbums, 60 * 10 * 1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
getAlbums();

function getArtists() {
  jQuery.ajax({
    type:'GET',
    url:'/api/artist/get',
    data: {
      limit:10,
      lower_limit:'<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/artistBar',
        data: {
          json_data:data,
        },
        success: function(data) {
          jQuery('#topArtistLoader').hide();
          jQuery('#topArtist').html(data);
        },
        complete: function() {
          setTimeout(getArtists, 60 * 10 * 1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
getArtists();

function recommentedTopAlbum() {
  jQuery.ajax({
    type:'GET',
    url: '/api/recommentedTopAlbum',
    data: {
      limit:10,
      lower_limit:'<?=date("Y-m-d", time() - (90 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>' 
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumTable',
        data: {
          json_data:data,
          limit:2,
          hide: {
            artist:true,
            count:true,
            rank:true,
            date:true,
            calendar:true
          }
        },
        success: function(data) {
          jQuery('#recommentedTopAlbumLoader').hide();
          jQuery('#recommentedTopAlbum').html(data);
        },
        complete: function() {
          setTimeout(recommentedTopAlbum, 60 * 10 * 1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
recommentedTopAlbum();

function recommentedNewAlbum() {
  jQuery.ajax({
    type:'GET',
    url:'/api/recommentedNewAlbum',
    data: {
      limit:10,
      order_by:'album.year DESC, album.created DESC',
      lower_limit:'<?=date("Y-m-d", time() - (365 * 24 * 60 * 60))?>',
      username:'<?php echo !empty($_GET['u']) ? $_GET['u'] : ''?>'
    },
    success: function(data) {
      jQuery.ajax({
        type:'POST',
        url:'/ajax/albumTable',
        data: {
          json_data:data,
          limit:2,
          hide: {
            artist:true,
            count:true,
            rank:true,
            date:true,
            calendar:true
          }
        },
        success: function(data) {
          jQuery('#recommentedNewAlbumLoader').hide();
          jQuery('#recommentedNewAlbum').html(data);
        },
        complete: function() {
          setTimeout(recommentedNewAlbum, 60 * 10 * 1000);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
recommentedNewAlbum();