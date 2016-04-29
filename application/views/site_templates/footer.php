      <div class="clear"></div>
    </div>
    <div id="footer">
      <div class="inner">
        <div class="float_left links">
          © <span class="statster_year number">2007–2016</span> Statster&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=anchor(array('about'), 'About', array('title' => 'About Statster'))?>&nbsp;&nbsp;<?=anchor(array('terms'), 'Terms', array('title' => 'Terms of service'))?>&nbsp;&nbsp;<?=anchor(array('privacy'), 'Privacy', array('title' => 'Privacy policy'))?>&nbsp;&nbsp;<?=anchor(array('career'), 'Career', array('title' => 'Career'))?>&nbsp;&nbsp;<?=anchor(array('developers'), 'Developers', array('title' => 'Developers'))?>
        </div>
        <div class="float_right social_media">
          <a href="https://www.facebook.com/statster" target="_blank"><i class="fa fa-facebook"></i></a>
          <a href="javascript:;"><i class="fa fa-twitter"></i></a>
          <a href="javascript:;"><i class="fa fa-rss"></i></a>
        </div>
      </div>
      <div class="clear"></div>
    </div>
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
            if (file_exists('./media/js/' . $file . '.js')) {
              include('./media/js/' . $file . '.js');
            }
          }
        }
        ?>
      });
    </script>
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
  </body>
</html>