      </div>
    </div>
    <div id="footer">
      <script type="text/javascript">
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
        <?php
        $interval = 12;
        ?>
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
        });
        jQuery.ajax({
          type: 'GET', url: '/api/popularGenre',
          data: {
            limit : 15,
            lower_limit : '<?=date("Y-m-d", time() - (365 * 24 * 60 * 60))?>',
            upper_limit : '<?=date("Y-m-d")?>',
          },
          success: function(data) {
            jQuery.ajax({
              type: 'POST', url: '/ajax/popularGenre',
              data: {
                json_data : data,
              },
              success: function(data) {
                jQuery('#popularGenre').html(data);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
              }
            });
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
          }
        });
        jQuery.ajax({
          type: 'GET', url: '/api/topAlbum',
          data: {
            limit : 15,
            lower_limit : '<?=date("Y-m-d", time() - (183 * 24 * 60 * 60))?>',
            upper_limit : '<?=date("Y-m-d")?>',
          },
          success: function(data) {
            jQuery.ajax({
              type: 'POST', url: '/ajax/popularAlbum',
              data: {
                json_data : data,
              },
              success: function(data) {
                jQuery('#popularAlbum').html(data);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
              }
            });
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
          }
        });
        jQuery("#addListeningSubmit").click(function() {
          jQuery.ajax({
            type: 'POST', url: '/api/addListening',
            data: {
              text : jQuery('#addListeningText').val(),
              submitType : jQuery('input[name="submitType"]').val(),
            },
            success: function(data) {
              console.log(data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            }
          });
          return false;
        });
      </script>
    </div>
  </body>
</html>