      </div>
    </div>
    <div id="footer">
      <script type="text/javascript">
        jQuery.ajax({
          type: 'GET',
          url: '/api/recentlyListened',
          data: {
            limit : 12,
          },
          success: function(data) {
            jQuery.ajax({
              type: 'POST',
              url: '/main/recentlyListened',
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
          type: 'GET',
          url: '/api/topAlbum',
          data: {
            limit : 8,
            lower_limit : '<?=date("Y-m-d", ($interval == "overall") ? 0 : time() - ($interval * 24 * 60 * 60))?>',
            upper_limit : '<?=date("Y-m-d")?>',
          },
          success: function(data) {
            jQuery.ajax({
              type: 'POST',
              url: '/main/topAlbum',
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
      </script>
      </script>
    </div>
  </body>
</html>