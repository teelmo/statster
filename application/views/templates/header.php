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
    <div id="topCont">
      <div id="topContInner">
        <h1><?=anchor('./', 'Statster')?></h1>
        <img src="/media/img/beta.png" alt="" style="vertical-align: -35%;" />
        <div class="floatRight" style="width: 200px;">
          <?php
            if($this->session->userdata('logged_in') === TRUE) {
              ?>
              <?=anchor('user/'.$this->session->userdata('username'), $this->session->userdata('username'))?>
              <?=anchor('user/'.$this->session->userdata('username'), '<img src="' . $this->session->userdata('user_image') . '" alt="" class="userImg userImg32" style="vertical-align: -45%;"/>')?>
              <?=anchor('logout', 'Logout')?>  
              <?
            }
          ?>
        </div>
        <div class="floatLeft" style="margin-right: 160px; width: 250px;">
          <form action="http://beta.statster.info/" method="post" accept-charset="utf-8" class="" id="searchForm">
            <div style="display: none;">
              <input type="hidden" name="searchType" value="form">
            </div>
            <input type="text" class="middle" autocomplete="off" tabindex="10" id="searchString" placeholder="Search.." name="searchStr" />
          </form>
        </div>
      </div>
    </div>
    <br  />
    <br  />
    <br  />
    <?php
    //print_r($this->session->userdata);
    ?>
    <div id="logoCont"></div>
    <div id="mainCont">