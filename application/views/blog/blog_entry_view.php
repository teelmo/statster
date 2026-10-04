<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container">
<?php $this->load->view('templates/page_links_main'); ?>
  <div class="full_container">
    <div class="container blog_entry_heading">
      <?php prefetchImagePaths(array(array('type' => 'user', 'size' => 64, 'id' => $entry['user_id']))); ?>
      <div class="img user_img">
        <?=anchor(array('user', url_title($entry['username'])), '<div class="cover user_img img64" style="background-image:url(' . getUserImg(array('user_id' => $entry['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
      </div>
      <div class="text">
        <h2><?=html_escape($entry['subject'])?></h2>
        <div class="metainfo">
          by <?=anchor(array('user', url_title($entry['username'])), html_escape($entry['username']), array('title' => 'Browse to user\'s page'))?>
          &nbsp;&middot;&nbsp;<?=timeAgo($entry['created'])?>
          <?php if ($entry['updated'] !== $entry['created'] && $entry['updated'] !== '0000-00-00 00:00:00') : ?>
            &nbsp;&middot;&nbsp;updated <?=timeAgo($entry['updated'])?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="container shout_text">
      <?=renderMarkdown($entry['text'])?>
    </div>
    <div class="container" id="comments">
      <h3><?=count($comments)?> comment<?=(count($comments) === 1) ? '' : 's'?></h3>
      <?php if (empty($comments)) : ?>
        <p>No comments yet.</p>
      <?php else : ?>
        <?php
        $image_requests = array();
        foreach ($comments as $comment) {
          if (!empty($comment['user_id'])) {
            $image_requests[] = array('type' => 'user', 'size' => 64, 'id' => $comment['user_id']);
          }
        }
        prefetchImagePaths($image_requests);
        ?>
        <table class="shout_table">
          <?php foreach ($comments as $comment) : ?>
            <tr class="shout">
              <td class="img user_img">
                <?php if (!empty($comment['user_id'])) : ?>
                  <?=anchor(array('user', url_title($comment['username'])), '<div class="cover user_img img64" style="background-image:url(' . getUserImg(array('user_id' => $comment['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
                <?php else : ?>
                  <div class="cover user_img img64" style="background-image:url(<?=getUserImg(array('user_id' => 0, 'size' => 64))?>)"></div>
                <?php endif; ?>
              </td>
              <td class="text">
                <div>
                  <div class="metainfo">
                    <div><?=timeAgo($comment['created'])?></div>
                    <div>by <?php if (!empty($comment['user_id'])) : ?><?=anchor(array('user', url_title($comment['username'])), html_escape($comment['username']), array('title' => 'Browse to user\'s page'))?><?php else : ?>Unknown user<?php endif; ?></div>
                  </div>
                </div>
                <div class="shout_text">
                  <?=renderMarkdown($comment['text'])?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php endif; ?>
    </div>
  </div>
