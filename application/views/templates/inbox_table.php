<?php
if (!empty($json_data)) {
  $size = 64;
  if (is_array($json_data)) {
    $image_requests = array();
    foreach ($json_data as $row) {
      $image_requests[] = array('type' => 'user', 'size' => $size, 'id' => $row['other_user_id']);
    }
    prefetchImagePaths($image_requests);
    foreach ($json_data as $idx => $row) {
      $is_sent = ($row['path'] === '/sent/');
      $unread = (!$is_sent && (int) $row['state'] === 0);
      ?>
      <tr id="bulletinTable<?=$idx?>" data-bulletin-id="<?=$row['id']?>" class="shout<?php if ($unread) : ?> unread<?php endif; ?>">
        <td class="img user_img">
          <?=anchor(array('user', url_title($row['other_username'])), '<div class="cover user_img img' . $size . '" style="background-image:url(' . getUserImg(array('user_id' => $row['other_user_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
        </td>
        <td class="text">
          <div>
            <span class="username title"><?=$is_sent ? 'To' : 'From'?> <?=anchor(array('user', url_title($row['other_username'])), html_escape($row['other_username']))?></span>
            <div class="metainfo">
              <div><?=timeAgo($row['date'])?></div>
            </div>
          </div>
          <div class="bulletin_subject"><?=html_escape($row['subject'])?></div>
          <div class="shout_text">
            <?=nl2br(html_escape($row['message']))?>
          </div>
        </td>
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
  echo ERR_NO_RESULTS;
}
?>
