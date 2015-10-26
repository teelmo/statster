<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="/media/js/libs/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/highcharts.js"></script>
    <script type="text/javascript" src="/media/js/libs/jquery.highchartTable.js"></script>
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
        <div class="float_left">
          <div class="search_container">
            <form action="http://beta.statster.info/" method="post" accept-charset="utf-8" class="search_form">
              <input type="text" class="middle search_text" autocomplete="off" tabindex="10" placeholder="Search" name="searchStr" />
              <button disabled="disabled" type="submit" class="submit" title="Search"></button>
            </form>
          </div>
        </div>
        <div class="float_right">
          <div class="top_links">
            <?=anchor(array(''), 'Overview')?>
            <?=anchor(array('music'), 'Music')?>
            <?=anchor(array('user'), 'Users')?>
          </div>
          <?php
          if ($this->session->userdata('logged_in') === TRUE) {
            ?>
            <div class="user_container">
              <div class="profile_text"><?=$this->session->userdata('username')?></div>
              <div class="profile_img" style="background-image: url('<?=$this->session->userdata('user_image')?>');"><img src="<?=$this->session->userdata('user_image')?>" alt="" /></div>
              <ul class="subnav" style="display: none;">
                <li><?=anchor(array('user', $this->session->userdata('username')), 'Profile')?></li>
                <li><?=anchor(array('user', 'edit'), 'Edit')?></li>
                <li><?=anchor(array('inbox'), 'Inbox')?></li>
                <li><?=anchor(array('logout'), 'Logout')?></li>
              </ul>
            </div>
            <?
          }
          else {
            ?>
            <div class="user_container">
              <?=anchor(array('login'), 'Login', array('title' => 'Login', 'id' => 'loginLink'))?>
            </div>
            <?
          }
          ?>
          <div>&nbsp;</div>
        </div>
      </div>
    </div>