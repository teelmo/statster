<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends MY_Controller {

  public function index($page = 1) {
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'blog_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = decodeFirstOrDefault(getArtists($opts), array('name' => 'No data', 'count' => 0));

    $page_result = getBlogEntries(array('page' => $page));
    $data['entries'] = $page_result['rows'];
    $data['page'] = (int) $page;
    $data['has_more'] = $page_result['has_more'];
    $data['has_prev'] = ((int) $page > 1);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('blog/blog_list_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function entry($id = FALSE, $slug = null) {
    if (!is_numeric($id)) {
      show_404();
      return;
    }

    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'blog_helper', 'markdown_helper', 'output_helper'));

    $entry = getBlogEntry((int) $id);
    if (empty($entry)) {
      show_404();
      return;
    }

    $data = array();
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = decodeFirstOrDefault(getArtists($opts), array('name' => 'No data', 'count' => 0));

    $data['entry'] = $entry;
    $data['comments'] = getBlogComments($entry['id']);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('blog/blog_entry_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
