<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="/media/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/media/js/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/media/js/dropdown.js"></script>
    <script type="text/javascript">
      if (document.images) {
        preLoadImg1 = new Image();
        preLoadImg1.src = "/media/img/ajax-loader-bar.gif";
        preLoadImg2 = new Image();
        preLoadImg2.src = "/media/img/ajax-loader-circle.gif";
      }
    </script>
    <?php
    echo link_tag('media/css/reset.css');
    echo link_tag('media/css/styles.css');
    echo link_tag('media/css/jquery.autocomplete.css');
    echo link_tag('media/css/responsive.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    //echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
    ?>
  </head>
  <body>
    <div id="topCont">
      <div id="topContInner">
        <div class="floatLeft">
          <h1><?=anchor('/', 'Statster')?></h1>
          <img src="/media/img/icons/beta.png" alt="" id="betaLogo" />
        </div>
        <div class="floatRight">
          <!--
          <div id="searchCont">
            <form action="http://beta.statster.info/" method="post" accept-charset="utf-8" class="" id="searchForm">
              <div style="display: none;">
                <input type="hidden" name="searchType" value="form">
              </div>
              <input type="text" class="middle searchForm" autocomplete="off" tabindex="10" id="searchString" placeholder="search.." name="searchStr" />
              <button id="searchSubmit" type="submit" class="submit searchForm" title="Search"></button>
            </form>
          </div>
          <span class="divider"></span>
          -->
          <?php
          if($this->session->userdata('logged_in') === TRUE) {
            ?>
            <div id="userCont">
              <span id="userContProfile"><?=anchor(array('user', 'profile', $this->session->userdata('username')), '' . $this->session->userdata('real_name') . '&nbsp;&nbsp;<img src="' . $this->session->userdata('user_image') . '" alt="" class="userImg userImg20" id="profile"/>', array('title' => 'Browse your profile'))?></span>
              <span id="userContDropdown"></span>
              <ul class="subnav" style="display: none;">
                <li><?=anchor(array('user', 'edit'), 'Edit')?></li>
                <li><?=anchor(array('inbox'), 'Inbox')?></li>
                <li><?=anchor(array('logout'), 'Logout')?></li>
              </ul>
            </div>
            <div>
              <span class="divider"></span>
              <?=anchor(array('/'), '<img src="/media/img/icons/bar_chart_20px.png" alt="" id="addlistening"/>', array('title' => 'Add listening', 'id' => 'addListeningLink'))?>
            </div>
            <?
          }
          else {
            ?>
            <div id="userCont">
              <?=anchor(array('login'), 'Login', array('title' => 'Login'))?>
            </div>
            <?
          }
          ?>
        </div>
      </div>
    </div>
    <div id="logoCont"></div>
    <div id="mainCont">