<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <?php
    echo link_tag('/media/css/styles.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    ?>
    <style type="text/css">
      /* Portrait Tablet */
      @media (min-width: 481px) and (max-width: 960px) {
        div#rightCont {
          display: none;
        }
        div#leftCont {
          width: 100%;
        }
        div#leftContOuter {
        }
      }
      /* Landscape smart phone */
      @media (min-width: 321px) and (max-width: 480px) {
        div#topCont {
          display: none;
        }
        div#mainCont {
          margin-top: 0px;
        }
        div#rightCont {
          display: none;
        }
        div#leftCont {
          width: 100%;
          max-width: 480px;
          min-width: 321px;
        }
      }
      /* Portrait smart phone */
      @media (max-width: 320px) {
        div#topCont {
          display: none;
        }
        div#mainCont {
          margin-top: 0px;
        }
        div#rightCont {
          display: none;
        }
        div#leftCont {
          width: 320px;
        }
      }
    </style>
  </head>
  <body>
    <div id="topCont">

    </div>

    <div id="mainCont">