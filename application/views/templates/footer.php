      </div>
    </div>
    <div id="footer">
      <p>
        <?=anchor(array('about'), 'About', array('title' => 'About Statster'))?>&nbsp; | &nbsp;
        <?=anchor(array('terms'), 'Terms', array('title' => 'Terms of service'))?>&nbsp; | &nbsp;
        <?=anchor(array('privacy'), 'Privacy', array('title' => 'Privacy policy'))?>&nbsp; | &nbsp;
        <?=anchor(array('career'), 'Career', array('title' => 'Career'))?>&nbsp; | &nbsp;
        <?=anchor(array('developers'), 'Developers', array('title' => 'Developers'))?>&nbsp; | &nbsp;
        &copy; Statster 2007 <?=DASH?> 2012. All rights reserved.
      </p>
      <script type="text/javascript">
        <?php
        if(isset($request)) {
          if (file_exists('./media/js/' . $request . '.js')) {
            include('./media/js/' . $request . '.js');
          }
        }
        ?>
      </script>
    </div>
  </body>
</html>