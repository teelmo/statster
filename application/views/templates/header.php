<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery -->
    <script type="text/javascript" src="/media/js/libs/jquery-1.10.2.min.js"></script>
    <!-- jQuery UI -->
    <script type="text/javascript" src="/media/js/libs/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- jQuery Chosen -->
    <script type="text/javascript" src="/media/js/libs/chosen.jquery.min.js"></script>
    <!-- Mousetrap -->
    <script type="text/javascript" src="/media/js/libs/mousetrap.min.js"></script>
    <!-- Dropdown -->
    <script type="text/javascript" src="/media/js/libs/dropdown.js"></script>
    <script type="text/javascript" src="/media/js/statster.js"></script>
    <?php
    echo link_tag('media/css/reset.css');
    echo link_tag('media/css/libs/jquery-ui-1.10.3.custom.min.css');
    echo link_tag('media/css/libs/chosen.css');
    echo link_tag('media/css/jquery.autocomplete.css');
    echo link_tag('media/css/styles.css');
    echo link_tag('media/css/responsive.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    //echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
    ?>
  </head>
  <body>
    <div id="topCont">
      <div id="topContInner">
        <div class="floatLeft">
          <h1 title="Statster"><?=anchor('/', 'Statster', array('title' => 'Statster'))?></h1>
          <img src="/media/img/icons/beta.png" alt="" id="betaLogo" />
        </div>
        <div class="floatRight">
          <div id="searchCont">
            <form action="http://beta.statster.info/" method="post" accept-charset="utf-8" class="" id="searchForm" onsubmit="return false;">
              <input type="text" class="middle searchForm" autocomplete="off" tabindex="10" id="searchString" placeholder="search.." name="searchStr" />
              <button id="searchSubmit" disabled="disabled" type="submit" class="submit" title="Search"></button>
            </form>
            <span class="divider"></span>
          </div>
          <?php
          if ($this->session->userdata('logged_in') === TRUE) {
            ?>
            <div id="userCont">
              <span id="userContProfile"><?=anchor(array('user', $this->session->userdata('username')), '' . $this->session->userdata('real_name') . '&nbsp;&nbsp;<img src="' . $this->session->userdata('user_image') . '" alt="" class="userImg userImg20" id="profile"/>', array('title' => 'Browse your profile'))?></span>
              <span id="userContDropdown"></span>
              <ul class="subnav" style="display: none;">
                <li><?=anchor(array('user', 'edit'), 'Edit')?></li>
                <li><?=anchor(array('inbox'), 'Inbox')?></li>
                <li><?=anchor(array('logout'), 'Logout')?></li>
              </ul>
            </div>
            <div id="addListeningCont">
              <span class="divider"></span>
              <?=anchor(array('/'), '<img src="/media/img/icons/bar_chart_20px.png" alt="" id="addlistening"/>', array('title' => 'Add listening', 'id' => 'addListeningLink'))?>
            </div>
            <!-- This is here because otherwise the responsive layout breaks -->
            <div>&nbsp;</div>
            <?
          }
          else {
            ?>
            <div id="userCont">
              <?=anchor(array('login'), 'Login', array('title' => 'Login', 'id' => 'loginLink'))?>
            </div>
            <?
          }
          ?>
        </div>
      </div>
    </div>
    <div id="logoCont"></div>
    <div id="mainCont">
      <div id="topLinks">
        <?=anchor(array('music'), 'Browse music')?>
        <?=anchor(array('tag'), 'Browse tags')?>
      </div>