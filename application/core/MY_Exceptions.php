<?php
// application/core/MY_Exceptions.php
class MY_Exceptions extends CI_Exceptions {
  public function show_404($page = '', $log_error = true)
  {
    $ci =& get_instance();
    $ci->load->view('site_templates/header');
    $ci->load->view('404_view');
    $ci->load->view('site_templates/footer');
    echo $ci->output->get_output();
    exit;
  }
}