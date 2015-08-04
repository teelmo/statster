<div id="leftCont">
  <div class="container">
    <h1><div class="desc"><?=anchor(array('user'), 'Users', array('title' => 'Browse to artist\'s page'))?></div><?=$username?></h1>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <div class="floatLeft"><img src="<?=getUserImg(array('user_id' => $id, 'size' => 300))?>" alt="" class="userImg img300" /></div>
    <div class="userInfo">
      <?php
      if (!empty($real_name)) {
        ?>
        <div><span class="value"><?=$real_name?></span></div>
        <?php
      }
      if (date_create($birthday) && !empty($birthday)) {
        ?>
        <div><span class="value"><?=date_diff(date_create($birthday), date_create('today'))->y;?> years</span></div>
        <?php
      }
      if (!empty($homepage)) {
        ?>
        <div><span class="value"><?=anchor($homepage, $homepage, array('title' => 'Homepage'))?></span></div>
        <?php
      }
      ?>
      <div><?=$about?></div>
    </div>
  </div>
</div>

<div id="rightCont">
  <div class="container">
    <h1></h1>
  </div>
</div>