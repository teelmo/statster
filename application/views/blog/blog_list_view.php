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
      <?php foreach ($entries as $entry) : ?>
        <div class="container blog_list_item">
          <h3><?=anchor(array('blog', $entry['id'], url_title($entry['subject']), ''), html_escape($entry['subject']))?></h3>
          <div class="metainfo">
            by <?=anchor(array('user', url_title($entry['username'])), html_escape($entry['username']), array('title' => 'Browse to user\'s page'))?>
            &nbsp;&middot;&nbsp;<?=timeAgo($entry['created'])?>
            &nbsp;&middot;&nbsp;<?=$entry['comment_count']?> comment<?=($entry['comment_count'] === 1) ? '' : 's'?>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if ($page > 1 || !empty($has_more)) : ?>
        <div class="container">
          <div class="pagination">
            <?php if (!empty($has_prev)) : ?>
              <?=anchor(array('blog', 'page', $page - 1), '&larr; Newer', array('class' => 'pagination_link'))?>
            <?php endif; ?>
            <span class="pagination_page">Page <?=$page?></span>
            <?php if (!empty($has_more)) : ?>
              <?=anchor(array('blog', 'page', $page + 1), 'Older &rarr;', array('class' => 'pagination_link'))?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
