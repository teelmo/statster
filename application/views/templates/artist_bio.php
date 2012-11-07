<?php
$json_data = json_decode($json_data);
if (!empty($json_data)) {
  ?>
  <p class="summary"><?=nl2br($json_data->bio_summary)?></p>
  <div class="more moreDown">
    <?=anchor('javascript://', 'See more', array('title' => 'See full biography', 'id' => 'biographyMore'))?>
  </div>
  <p class="content"><?=nl2br($json_data->bio_content)?></p>
  <div class="more moreUp ">
    <?=anchor('javascript://', 'See less', array('title' => 'Suppress biograhpy', 'id' => 'biographyLess', 'class' => 'hidden'))?>
  </div>
  <?php
}
else {
  echo $json_data->error->msg;
}
?>