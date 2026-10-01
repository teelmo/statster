  <div class="right_container">
    <div class="container">
      <h2>Folders</h2>
      <ul class="no_bullets folder_list">
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
          ?>
          <li><?=anchor($segments, $label, array('class' => ($folder === $tab_folder) ? 'active' : ''))?></li>
          <?php
        }
        ?>
      </ul>
    </div>
  </div>
