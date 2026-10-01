<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Inbox extends MY_ReadOnly_Controller {

  const FOLDERS = array('inbox', 'sent', 'notices', 'notifications', 'shares', 'trash');

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List messages/notifications in a folder */
  public function get() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      show_404();
    }
    $folder = isset($_GET['folder']) ? $_GET['folder'] : 'inbox';
    if (!in_array($folder, self::FOLDERS, TRUE)) {
      header('HTTP/1.1 400 Bad Request');
      return;
    }
    // Load helpers
    $this->load->helper(array('inbox_helper'));

    echo getBulletins(array(
      'folder' => $folder,
      'user_id' => $this->session->userdata('user_id')
    ));
  }

  /* Add a message */
  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Mark a message or notification as read */
  public function update($bulletin_id = FALSE) {
    if ($this->session->userdata('logged_in') !== TRUE) {
      show_404();
    }
    if (!is_numeric($bulletin_id)) {
      header('HTTP/1.1 400 Bad Request');
      return;
    }
    $type = isset($_POST['type']) ? $_POST['type'] : 'message';
    if (!in_array($type, array('message', 'notification'), TRUE)) {
      header('HTTP/1.1 400 Bad Request');
      return;
    }
    // Load helpers
    $this->load->helper(array('inbox_helper'));

    $opts = array(
      'id' => (int) $bulletin_id,
      'user_id' => $this->session->userdata('user_id')
    );
    if ($type === 'notification') {
      updateNotificationState($opts);
    }
    else {
      updateMessageRecipientState($opts);
    }
  }

  /* Delete a message */
  public function delete() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }
}
?>
