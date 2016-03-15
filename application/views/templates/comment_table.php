<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="commentTable<?=$idx?>" class="comment">
        <td class="img user_img">
          <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img64" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
        </td>
        <td class="text">
          <div>
            <span class="username"><?=anchor(array('user', url_title($row['username'])), $row['username'])?></span>
            <?php
            if ($this->session->userdata('user_id') === $row['user_id']) {
              ?>
              <span class="delete" data-confirmation-container=".confirmation_<?=$idx?>"><a href="javascript:;"><i class="fa fa-times"></i></a></span>
              <div class="confirmation confirmation_<?=$idx?>">Are you sure: <a href="javascript:;" class="confirm" data-comment-id="<?=$row['comment_id']?>" data-comment-type="<?=$row['type']?>" data-row-id="commentTable<?=$idx?>">Ok</a> / <a href="javascript:;" class="cancel">Cancel</a></div>
              <?php
            }
            ?>
          </div>
          <?=nl2br($row['text'])?>
        </td>
        <td class="datetime"><?=timeAgo($row['created'])?></td>
      </tr>
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
  No results
  <?php
}
?>