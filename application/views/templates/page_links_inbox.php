  <div class="page_links">
    <?php
    $inbox_tabs = array(
      'inbox' => 'Inbox',
      'sent' => 'Sent',
      'notices' => 'Notices',
      'notifications' => 'Notifications',
      'shares' => 'Shares',
      'trash' => 'Trash'
    );
    foreach ($inbox_tabs as $tab_folder => $label) {
      $segments = ($tab_folder === 'inbox') ? array('inbox') : array('inbox', $tab_folder);
      echo anchor($segments, $label, array('class' => ($folder === $tab_folder) ? 'active' : ''));
    }
    ?>
  </div>
