<?php
if (!empty($json_data)) {
  $size = 64;
  if (is_array($json_data)) {
    $image_requests = array();
    foreach ($json_data as $row) {
      if (!empty($row['other_users'])) {
        $image_requests[] = array('type' => 'user', 'size' => $size, 'id' => $row['other_users'][0]['user_id']);
      }
    }
    prefetchImagePaths($image_requests);

    $renderAvatar = function ($row) use ($size) {
      $other_users = isset($row['other_users']) ? $row['other_users'] : array();
      $primary = !empty($other_users) ? $other_users[0] : null;
      $is_notification = ($row['type'] === 'notification');
      if ($primary !== null) {
        echo anchor(array('user', url_title($primary['username'])), '<div class="cover user_img img' . $size . '" style="background-image:url(' . getUserImg(array('user_id' => $primary['user_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'));
      }
      elseif (!$is_notification) {
        ?>
        <div class="cover user_img img<?=$size?>" style="background-image:url(<?=getUserImg(array('user_id' => 0, 'size' => $size))?>)"></div>
        <?php
      }
    };

    $renderContent = function ($row, $show_toggle) {
      $is_sent = ((int) $row['is_sent'] === 1);
      $is_notification = ($row['type'] === 'notification');
      $other_users = isset($row['other_users']) ? $row['other_users'] : array();
      ?>
      <div>
        <?php if ($is_notification) : ?>
          <span class="username title">Notification</span>
        <?php else : ?>
          <span class="username title">
            <?=$is_sent ? 'To' : 'From'?>
            <?php
            if (empty($other_users)) {
              echo 'Unknown recipient';
            }
            else {
              $links = array();
              foreach ($other_users as $user) {
                $links[] = anchor(array('user', url_title($user['username'])), html_escape($user['username']));
              }
              echo implode(', ', $links);
            }
            ?>
          </span>
        <?php endif; ?>
        <div class="metainfo">
          <div><?=timeAgo($row['date'])?></div>
        </div>
      </div>
      <div class="bulletin_subject"><?=html_escape($row['subject'])?></div>
      <div class="shout_text">
        <?=renderMarkdown($row['message'])?>
      </div>
      <?php if ($show_toggle && !empty($row['in_thread'])) : ?>
        <div class="thread_toggle_row">
          <a href="javascript:;" class="thread_toggle" aria-label="View full thread" data-bulletin-id="<?=$row['id']?>"><span>View full thread</span><i class="mask-icon mask-icon-chevron-down" aria-hidden="true"></i></a>
        </div>
      <?php endif; ?>
      <?php
    };

    if (!empty($thread_of)) {
      ?>
      <tr class="thread_message" data-thread-of="<?=(int) $thread_of?>">
        <td colspan="2">
          <div class="thread_tree">
            <?php
            foreach ($json_data as $row) {
              if ((int) $row['id'] === (int) $thread_of) {
                continue; // Already shown as the row that was expanded.
              }
              $is_sent = ((int) $row['is_sent'] === 1);
              $unread = (!$is_sent && (int) $row['state'] === 0);
              $depth = isset($row['depth']) ? (int) $row['depth'] : 0;
              ?>
              <div class="thread_item<?php if ($unread) : ?> unread<?php endif; ?>" data-bulletin-id="<?=$row['id']?>" data-bulletin-type="<?=$row['type']?>" style="margin-left: <?=($depth * 24)?>px;">
                <div class="img user_img"><?php $renderAvatar($row); ?></div>
                <div class="text"><?php $renderContent($row, false); ?></div>
              </div>
              <?php
            }
            ?>
          </div>
        </td>
      </tr>
      <?php
    }
    else {
      foreach ($json_data as $idx => $row) {
        $is_sent = ((int) $row['is_sent'] === 1);
        $unread = (!$is_sent && (int) $row['state'] === 0);
        ?>
        <tr id="bulletinTable<?=$idx?>" data-bulletin-id="<?=$row['id']?>" data-bulletin-type="<?=$row['type']?>" class="shout<?php if ($unread) : ?> unread<?php endif; ?>">
          <td class="img user_img"><?php $renderAvatar($row); ?></td>
          <td class="text"><?php $renderContent($row, true); ?></td>
        </tr>
        <?php
      }
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
