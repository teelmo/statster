      </div>
    </div>
    <div id="footer">
      <div>
        <?=anchor(array('about'), 'About', array('title' => 'About Statster'))?>&nbsp; | &nbsp;
        <?=anchor(array('terms'), 'Terms', array('title' => 'Terms of service'))?>&nbsp; | &nbsp;
        <?=anchor(array('privacy'), 'Privacy', array('title' => 'Privacy policy'))?>&nbsp; | &nbsp;
        <?=anchor(array('career'), 'Career', array('title' => 'Career'))?>&nbsp; | &nbsp;
        <?=anchor(array('developers'), 'Developers', array('title' => 'Developers'))?>&nbsp; | &nbsp;
        Statster,&nbsp;&nbsp;2007 <?=DASH?> 2012&nbsp;
        <?=anchor(array('/'), '<img src="/media/img/icons/facebook.png" alt="" class="middle icon" />', array('title' => 'Statster @ Facebook'))?>
        <?=anchor(array('/'), '<img src="/media/img/icons/twitter.png" alt="" class="middle icon" />', array('title' => 'Statster @ Twitter'))?>
        <?=anchor(array('/'), '<img src="/media/img/icons/rss.png" alt="" class="middle icon" />', array('title' => 'RSS feed'))?>
      </div>
      <script type="text/javascript">
        <?php
        if(isset($request)) {
          if (file_exists('./media/js/' . $request . '.js')) {
            include('./media/js/' . $request . '.js');
          }
        }
        ?>
        jQuery(document).ready(function() {
          jQuery('a').tipsy({delayIn: 350, delayOut: 125, gravity: 'nw', live: true});
          jQuery('img.listeningFormatType').tipsy({delayIn: 350, delayOut: 125, gravity: 'nw', live: true});
          jQuery('span.loveIcon').tipsy({delayIn: 350, delayOut: 125, gravity: 'nw', live: true});
        });
      </script>
    </div>
  </body>
</html>