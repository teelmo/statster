<?php
if (!empty($json_data)) {
  $size = 64;
  if (is_array($json_data)) {
    $thread_avatar_size = 32;

    $image_requests = array();
    foreach ($json_data as $row) {
      if (!empty($thread_of)) {
        if (!empty($row['sender'])) {
          $image_requests[] = array('type' => 'user', 'size' => $thread_avatar_size, 'id' => $row['sender']['user_id']);
        }
      }
      elseif (!empty($row['other_users'])) {
        $image_requests[] = array('type' => 'user', 'size' => $size, 'id' => $row['other_users'][0]['user_id']);
      }
    }
    prefetchImagePaths($image_requests);

    $renderAvatar = function ($user, $is_notification, $avatar_size) {
      if ($user !== null) {
        echo anchor(array('user', url_title($user['username'])), '<div class="cover user_img img' . $avatar_size . '" style="background-image:url(' . getUserImg(array('user_id' => $user['user_id'], 'size' => $avatar_size)) . ')"></div>', array('title' => 'Browse to user\'s page'));
      }
      elseif (!$is_notification) {
        ?>
        <div class="cover user_img img<?=$avatar_size?>" style="background-image:url(<?=getUserImg(array('user_id' => 0, 'size' => $avatar_size))?>)"></div>
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
          <a href="javascript:;" class="thread_toggle" aria-label="Show thread" data-bulletin-id="<?=$row['id']?>"><span>Show thread</span><i class="mask-icon mask-icon-chevron-down" aria-hidden="true"></i></a>
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
              $sender = isset($row['sender']) ? $row['sender'] : null;
              ?>
              <div class="thread_item<?php if ($unread) : ?> unread<?php endif; ?>" data-bulletin-id="<?=$row['id']?>" data-bulletin-type="<?=$row['type']?>" style="margin-left: <?=(($depth + 1) * 20)?>px;">
                <div class="img user_img"><?php $renderAvatar($sender, FALSE, $thread_avatar_size); ?></div>
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
        $is_notification = ($row['type'] === 'notification');
        $unread = (!$is_sent && (int) $row['state'] === 0);
        $other_users = isset($row['other_users']) ? $row['other_users'] : array();
        $primary = !empty($other_users) ? $other_users[0] : null;
        $zebra = ($idx % 2 === 0) ? 'zebra-even' : 'zebra-odd';
        ?>
        <tr id="bulletinTable<?=$idx?>" data-bulletin-id="<?=$row['id']?>" data-bulletin-type="<?=$row['type']?>" class="shout <?=$zebra?><?php if ($unread) : ?> unread<?php endif; ?>">
          <td class="img user_img"><?php $renderAvatar($primary, $is_notification, $size); ?></td>
          <td class="text"><?php $renderContent($row, true); ?></td>
        </tr>
        <?php
      }
      $page = isset($page) ? (int) $page : 1;
      if ($page > 1 || !empty($has_more)) {
        ?>
        <tr class="pagination_row">
          <td colspan="2">
            <div class="pagination">
              <?php if (!empty($has_prev)) : ?>
                <a href="javascript:;" class="pagination_link pagination_prev" data-page="<?=($page - 1)?>">&larr; Newer</a>
              <?php endif; ?>
              <span class="pagination_page">Page <?=$page?></span>
              <?php if (!empty($has_more)) : ?>
                <a href="javascript:;" class="pagination_link pagination_next" data-page="<?=($page + 1)?>">Older &rarr;</a>
              <?php endif; ?>
            </div>
          </td>
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
