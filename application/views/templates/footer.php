      </div>
    </div>
    <div id="footer">
      <div class="floatLeft">
        <?=anchor(array('about'), 'About', array('title' => 'About Statster'))?>&nbsp; | &nbsp;
        <?=anchor(array('terms'), 'Terms', array('title' => 'Terms of service'))?>&nbsp; | &nbsp;
        <?=anchor(array('privacy'), 'Privacy', array('title' => 'Privacy policy'))?>&nbsp; | &nbsp;
        <?=anchor(array('career'), 'Career', array('title' => 'Career'))?>&nbsp; | &nbsp;
        <?=anchor(array('developers'), 'Developers', array('title' => 'Developers'))?>&nbsp; | &nbsp;
        Statster,&nbsp;&nbsp;2007 <?=DASH?> 2012&nbsp;
      </div>
      <div class="floatRight socialMedia">
        <?=anchor(array('/'), '<img src="/media/img/icons/facebook.png" alt="" class="middle icon" />', array('title' => 'Statster @ Facebook'))?>
        <?=anchor(array('/'), '<img src="/media/img/icons/twitter.png" alt="" class="middle icon" />', array('title' => 'Statster @ Twitter'))?>
        <?=anchor(array('/'), '<img src="/media/img/icons/rss.png" alt="" class="middle icon" />', array('title' => 'RSS feed'))?>
      </div>
      <script type="text/javascript">
        jQuery(document).ready(function() {
          <?php
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
    </div>
  </body>
</html>