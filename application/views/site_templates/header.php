<?php
header('HTTP/1.1 200 OK');
?>
<?=doctype('html5')?>
<html>
  <head>
    <title><?=TITLE?></title>
    <meta charset="utf-8">
    <meta name="description" content="Reconcile with music" />
    <meta property="og:description" content="Reconcile with music" />
    <meta property="og:url" content="https://statster.info<?=$_SERVER['REQUEST_URI']?>" />
    <meta property="og:image" content="https://statster.info/media/img/og_image.jpg" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Statster" />
    <meta property="og:site_name" content="Statster" />
    <meta property="fb:app_id" content="144593758916156" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@statster" />
    <meta name="apple-mobile-web-app-title" content="Statster" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="msapplication-navbutton-color" content="#000" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#000" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="/media/js/libs/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/jquery-ui-1.13.2.custom.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/moment.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/mousetrap.min.js"></script>
    <script type="text/javascript" src="/media/js/libs/tooltipster.bundle.min.js"></script>
    <script type="text/javascript" src="/media/js/statster.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">
    <?php
    echo link_tag('/media/css/themes/' . (($this->session->userdata('logged_in') === TRUE) ? ($this->session->userdata('theme')) ? $this->session->userdata('theme') : 'light' : 'light') . '/styles.css');
    echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
    //echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
    ?>
  </head>
  <body>
    <div class="top_container">
      <div class="inner">
        <div class="float_left">
          <div class="mobile meta">Reconcile with music</div>
          <div class="search_container">
            <form action="/search" method="get" accept-charset="utf-8" class="search_form">
              <button disabled="disabled" type="submit" class="search_submit" title="Search!"></button>
              <div class="autocomplete_container"><input type="text" class="middle search_text" autocomplete="off" tabindex="20" placeholder="Search…" name="q" /><span class="lds-ring hidden"><div></div><div></div><div></div><div></div></span></div>
              
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
                <li class="mobile"><?=anchor(array(''), 'Overview')?></li>
                <li class="mobile"><?=anchor(array('music'), 'Music')?></li>
                <li class="mobile"><?=anchor(array('users'), 'Users')?></li>
                <li><a href="javascript:;" class="toggle_username <?=(!empty($this->session->userdata('get_username'))) ? 'active' : ''; ?>"><?=(!empty($this->session->userdata('get_username'))) ? 'Show all' : 'Your stats only'; ?></a></li>
                <li><?=anchor(array('user', $this->session->userdata('username')), 'Profile')?></li>
                <li><?=anchor(array('user', 'edit'), 'Edit')?></li>
                <!-- <li><?=anchor(array('inbox'), 'Inbox')?></li> -->
                <li><?=anchor(array('search'), 'Search')?></li>
                <?php
                if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
                  ?>
                  <li><?=anchor(array('admin'), 'Admin')?></li>
                  <?php
                }
                ?>
                <li><?=anchor(array('logout'), 'Logout')?></li>
              </ul>
            </div>
            <?php
          }
          else {
            ?>
            <div class="user_container">
              <?=($_SERVER['REQUEST_URI'] !== '/') ? anchor(array('login' . '?redirect=' . $_SERVER['REQUEST_URI']), 'Login', array('title' => 'Login', 'id' => 'loginLink')) : anchor(array('login'), 'Login', array('title' => 'Login', 'id' => 'loginLink'))?>
            </div>
            <?php
          }
          ?>
        </div>
        <?php
        if (isset($_GET['u']) && $_GET['u'] !== $this->session->userdata('username')) {
          ?>
          <div class="selected_user">selected: <?=anchor(array('user', $_GET['u']), $_GET['u'])?></div>
          <?php
        }
        ?>
      </div>
    </div>