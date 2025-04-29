<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      $row['user_ids'] = explode(',', $row['user_ids']);
      switch ($row['type']) {

        case 'nationality' {
          ?>
          <li class="tag <?=$row['type']?>">
            <?=anchor(array($row['type'], url_title($row['name'])), '<img src="/media/img/flag_img/' . strtolower($row['country_code']) . '.png"/ alt="' . $row['name'] . '" />')?>
            <?php
            if ($this->session->userdata('logged_in') === TRUE && (in_array($this->session->userdata('user_id'), $row['user_ids']) || in_array($this->session->userdata['user_id'], ADMIN_USERS)) && $delete == 'true') {
              ?>
              <a href="javascript:;" class="hidden remove" data-tag-id="<?=$row['tag_id']?>" data-tag-type="<?=$row['type']?>"><i class="fa fa-times"></i></a>
              <?php
            }
            ?>
          </li>
          <?php
          break;
        case 'genre':
          ?>
          <li class="tag <?=$row['type']?> <?=(in_array($this->session->userdata('user_id'), $row['user_ids'])) ? 'active' : ''?>">
            <?=anchor(array($row['type'], url_title($row['name'])), '<i class="fa fa-music"></i> ' . $row['name'])?>
            <?php
            if ($this->session->userdata('logged_in') === TRUE && (in_array($this->session->userdata('user_id'), $row['user_ids']) || in_array($this->session->userdata['user_id'], ADMIN_USERS)) && $delete == 'true') {
              ?>
              <a href="javascript:;" class="hidden remove" data-tag-id="<?=$row['tag_id']?>" data-tag-type="<?=$row['type']?>"><i class="fa fa-times"></i></a>
              <?php
            }
            ?>
          </li>
          <?php
          break;
        case 'keyword':
          ?>
          <li class="tag <?=$row['type']?>">
            <?=anchor(array($row['type'], url_title($row['name'])), '<i class="fa fa-tag"></i> ' . $row['name'])?>
            <?php
            if ($this->session->userdata('logged_in') === TRUE && (in_array($this->session->userdata('user_id'), $row['user_ids']) || in_array($this->session->userdata['user_id'], ADMIN_USERS)) && $delete == 'true') {
              ?>
              <a href="javascript:;" class="hidden remove" data-tag-id="<?=$row['tag_id']?>" data-tag-type="<?=$row['type']?>"><i class="fa fa-times"></i></a>
              <?php
            }
            ?>
          </li>
          <?php
          break;
        default:
          break;
      }
    }
    if ($logged_in === 'true' && empty($hide['add'])) {
      ?>
      <li class="tag addtags" id="addtags"><a href="javascript:;"><i class="fa fa-bars"></i></a></li>
      <?php
    }
  }
  elseif (is_object($json_data)) {
    echo $json_data->error->msg;
  }
  else {
    echo $json_data;
  }
}
else {
  ?>
  <li><?=ERR_NO_RESULTS?></li>
  <?php
  if ($logged_in === 'true' && empty($hide['add'])) {
    ?>
    <li class="tag addtags" id="addtags"><a href="javascript:;"><i class="fa fa-bars"></i></a></li>
    <?php
  }
}
?>