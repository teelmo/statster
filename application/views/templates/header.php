<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="/media/js/libs/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/mousetrap.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/dropdown.js"></script>
    <script type="text/javascript" src="/media/js/statster.js"></script>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,700">
    <?php
    echo link_tag('media/css/styles.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    //echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
    ?>
  </head>
  <body>
    <div id="topCont">
      <div class="inner">
        <div class="floatLeft">
          <div class="search_container">
            <form action="http://beta.statster.info/" method="post" accept-charset="utf-8" class="search_form">
              <input type="text" class="middle searchForm" autocomplete="off" tabindex="10" id="searchString" placeholder="search…" name="searchStr" />
              <button disabled="disabled" type="submit" class="submit" title="Search"></button>
            </form>
          </div>
        </div>
        <div class="floatRight">
          <?php
          if ($this->session->userdata('logged_in') === TRUE) {
            ?>
            <div id="userCont" class="userContDropdown">
              <span class="profile_text"><?=$this->session->userdata('username')?></span>
              <span class="profile_img" style="background-image: url('<?=$this->session->userdata('user_image')?>');"><img src="<?=$this->session->userdata('user_image')?>" alt="" /></span>
              <ul class="subnav" style="display: none;">
                <li><?=anchor(array('user', $this->session->userdata('username')), 'Profile')?></li>
                <li><?=anchor(array('user', 'edit'), 'Edit')?></li>
                <li><?=anchor(array('inbox'), 'Inbox')?></li>
                <li><?=anchor(array('logout'), 'Logout')?></li>
              </ul>
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
        <?=anchor(array(''), 'What\'s new')?>
        <?=anchor(array('music'), 'Music')?>
        <?=anchor(array('tag'), 'Tags')?>
        <?=anchor(array('user'), 'Users')?>
      </div>