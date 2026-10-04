<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container">
<?php $this->load->view('templates/page_links_main'); ?>
  <div class="full_container">
    <div class="container">
      <h2>Blog</h2>
    </div>
    <?php if (empty($entries)) : ?>
      <div class="container">
        <?=ERR_NO_RESULTS?>
      </div>
    <?php else : ?>
      <?php
      $image_requests = array();
      foreach ($entries as $entry) {
        $image_requests[] = array('type' => 'user', 'size' => 64, 'id' => $entry['user_id']);
      }
      prefetchImagePaths($image_requests);
      ?>
      <div class="container">
        <table class="shout_table">
          <?php foreach ($entries as $idx => $entry) : ?>
            <?php $zebra = ($idx % 2 === 0) ? 'zebra-even' : 'zebra-odd'; ?>
            <tr class="shout blog_list_item <?=$zebra?>">
              <td class="img user_img">
                <?=anchor(array('user', url_title($entry['username'])), '<div class="cover user_img img64" style="background-image:url(' . getUserImg(array('user_id' => $entry['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
              </td>
              <td class="text">
                <h3><?=anchor(array('blog', $entry['id'], url_title($entry['subject']), ''), html_escape($entry['subject']))?></h3>
                <div class="metainfo">
                  by <?=anchor(array('user', url_title($entry['username'])), html_escape($entry['username']), array('title' => 'Browse to user\'s page'))?>
                  &nbsp;&middot;&nbsp;<?=timeAgo($entry['created'])?>
                  &nbsp;&middot;&nbsp;<?=$entry['comment_count']?> comment<?=($entry['comment_count'] === 1) ? '' : 's'?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if ($page > 1 || !empty($has_more)) : ?>
            <tr class="pagination_row">
              <td colspan="2">
                <div class="pagination">
                  <?php if (!empty($has_prev)) : ?>
                    <?=anchor(array('blog', 'page', $page - 1), '&larr; Newer', array('class' => 'pagination_link'))?>
                  <?php endif; ?>
                  <span class="pagination_page">Page <?=$page?></span>
                  <?php if (!empty($has_more)) : ?>
                    <?=anchor(array('blog', 'page', $page + 1), 'Older &rarr;', array('class' => 'pagination_link'))?>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </table>
      </div>
    <?php endif; ?>
  </div>
