<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
    <script type="text/javascript" src="/media/js/jquery.min.js"></script>
    <script type="text/javascript" src="/media/js/jquery.autocomplete.js"></script>
    <script type="text/javascript">
      if (document.images) {
        preLoadImg1 = new Image();
        preLoadImg1.src = "/media/img/ajax-loader-bar.gif";
        preLoadImg2 = new Image();
        preLoadImg2.src = "/media/img/ajax-loader-circle.gif";
      }
    </script>
    <?php
    echo link_tag('media/css/styles.css');
    echo link_tag('media/css/jquery.autocomplete.css');
    echo link_tag('media/css/responsive.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    //echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
    ?>
    <style type="text/css">
       /* Nothing here */
    </style>
  </head>
  <body>
    <div id="topCont"></div>

    <div id="mainCont">