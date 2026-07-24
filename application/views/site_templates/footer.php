      <div class="clear"></div>
    </div>
    <div class="footer">
      <div class="links">
        © <span class="statster_year number">2007–2026</span> Statster&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=anchor(array('about'), 'About', array('title' => 'About Statster'))?>&nbsp;&nbsp;<?=anchor(array('terms'), 'Terms', array('title' => 'Terms of service'))?>&nbsp;&nbsp;<?=anchor(array('privacy'), 'Privacy', array('title' => 'Privacy policy'))?>&nbsp;&nbsp;<?=anchor(array('career'), 'Career', array('title' => 'Career'))?>&nbsp;&nbsp;<?=anchor(array('developers'), 'Developers', array('title' => 'Developers'))?>
      </div>
    </div>
    <?php
    // Vendored libs (libs/*) are static, so load them as real cacheable <script src>
    // tags instead of splicing them into the inline block below - they used to be
    // re-downloaded and re-parsed as inline text on every page that needed them.
    if (isset($js_include)) {
      foreach ($js_include as $file) {
        if (strpos($file, 'libs/') === 0 && file_exists('./media/js/' . $file . '.js')) {
          echo '<script type="text/javascript" src="/media/js/' . $file . '.js" defer></script>';
        }
      }
    }
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        <?php
        if (!empty($artist_name)) {
          $artist_name = addslashes($artist_name);
        }
        if (!empty($album_name)) {
          $album_name = addslashes($album_name);
        }
        if (isset($js_include)) {
          foreach ($js_include as $file) {
            if (strpos($file, 'libs/') !== 0 && file_exists('./media/js/' . $file . '.js')) {
              include('./media/js/' . $file . '.js');
            }
          }
        }
        ?>
      });
    </script>
  </body>
</html>
