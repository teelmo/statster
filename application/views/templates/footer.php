      </div>
    </div>
    <div id="footer">
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