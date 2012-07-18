jQuery('#addListeningText').focus();

jQuery("#addListeningSubmit").click(function() {
  jQuery.ajax({
    type: 'POST', url: '/api/addListening',
    data: {
      text : jQuery('#addListeningText').val(),
      date : jQuery('#addListeningDate').val(),
      format : jQuery('input[name="addListeningFormat"]:checked').val(),
      submitType : jQuery('input[name="submitType"]').val(),
    },
    success: function(data) {
      if (data == '') {  
        jQuery('#addListeningText').val("");
        jQuery('input[name="addListeningFormat"]').prop('checked', false);
        jQuery('img.listeningFormat').removeClass('selected');
        recentlyListened();
        topAlbum();
        topArtist();
      }
      jQuery('#addListeningText').focus();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
  return false;
});

jQuery(function() {
  jQuery('#addListeningText').autocomplete({
    serviceUrl: '/autoComplete/addListening',
    minChars: 3,
    maxHeight: 312,
    onSelect: function(value, data) {
      jQuery('#addListeningText').value = data;
    },
    observableElement: { },
    images: true,
    list: true
  });
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

function recentlyListened() {
  jQuery.ajax({
    type: 'POST', url: '/api/recentlyListened', 
    data: {
      limit : 11,
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/recentlyListened',
        data: {
          json_data : data,
        },
        success: function(data) {
          jQuery('#recentlyListenedLoader').hide();
          jQuery('#recentlyListened').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
recentlyListened();

function topAlbum() {
  jQuery.ajax({
    type: 'POST', url: '/api/topAlbum',
    data: {
      limit : 8,
      lower_limit : '<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      upper_limit : '<?=date("Y-m-d")?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumList/124',
        data: {
          json_data : data,
        },
        success: function(data) {
          jQuery('#topAlbumLoader').hide();
          jQuery('#topAlbum').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
topAlbum();

function topArtist() {
  jQuery.ajax({
    type: 'POST', url: '/api/topArtist',
    data: {
      limit : 8,
      lower_limit : '<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      upper_limit : '<?=date("Y-m-d")?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/topArtistBar',
        data: {
          json_data : data,
        },
        success: function(data) {
          jQuery('#topArtistLoader').hide();
          jQuery('#topArtist').html(data);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
}
topArtist();

function recommentedTopAlbum() {
  jQuery.ajax({
    type: 'POST', url: '/api/recommentedTopAlbum',
    data: {
      limit : 10,
      lower_limit : 90 * 24 * 60 * 60 
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumList/64',
        data: {
          json_data : data,
          limit : 2,
        },
        success: function(data) {
          jQuery('#recommentedTopAlbumLoader').hide();
          jQuery('#recommentedTopAlbum').html(data);
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
    type: 'POST', url: '/api/recommentedNewAlbum',
    data: {
      limit : 10,
      order_by : 'album.year DESC, album.created DESC',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/albumList/64',
        data: {
          json_data : data,
          limit : 2,
        },
        success: function(data) {
          jQuery('#recommentedNewAlbumLoader').hide();
          jQuery('#recommentedNewAlbum').html(data);
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