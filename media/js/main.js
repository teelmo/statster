jQuery("#addListeningSubmit").click(function() {
  jQuery.ajax({
    type: 'POST', url: '/api/addListening',
    data: {
      text : jQuery('#addListeningText').val(),
      submitType : jQuery('input[name="submitType"]').val(),
    },
    success: function(data) {
      jQuery('#addListeningText').val("");
      recentlyListened();
      topAlbum();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    }
  });
  return false;
});

function recentlyListened() {
  jQuery.ajax({
    type: 'GET', url: '/api/recentlyListened', 
    data: {
      limit : 12,
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/recentlyListened',
        data: {
          json_data : data,
        },
        success: function(data) {
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
    type: 'GET', url: '/api/topAlbum',
    data: {
      limit : 8,
      lower_limit : '<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      upper_limit : '<?=date("Y-m-d")?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/topAlbum',
        data: {
          json_data : data,
        },
        success: function(data) {
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
    type: 'GET', url: '/api/topArtist',
    data: {
      limit : 8,
      lower_limit : '<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
      upper_limit : '<?=date("Y-m-d")?>',
    },
    success: function(data) {
      jQuery.ajax({
        type: 'POST', url: '/ajax/topArtist',
        data: {
          json_data : data,
        },
        success: function(data) {
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

/*
jQuery.ajax({
  type: 'GET', url: '/api/recommendationNewAlbum',
  data: {
    limit : 2,
  },
  success: function(data) {
    jQuery.ajax({
      type: 'POST', url: '/ajax/',
      data: {
        json_data : data,
      },
      success: function(data) {
        jQuery('#recommentedAlbums').html(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      }
    });
  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
  }
});

jQuery.ajax({
  type: 'GET', url: '/api/recommendationPopularAlbum',
  data: {
    limit : 2,
  },
  success: function(data) {
    jQuery.ajax({
      type: 'POST', url: '/ajax/',
      data: {
        json_data : data,
      },
      success: function(data) {
        jQuery('#recentlyReleased').html(data);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      }
    });
  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
  }
});*/