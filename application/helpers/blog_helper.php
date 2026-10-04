<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!defined('BLOG_PAGE_SIZE')) {
  define('BLOG_PAGE_SIZE', 20);
}

/**
  * Gets one page of blog entries, newest first.
  *
  * @param array $opts.
  *          'page' => 1-indexed page number.
  *
  * @return array {rows, has_more}.
  */
if (!function_exists('getBlogEntries')) {
  function getBlogEntries($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $page = isset($opts['page']) ? max(1, (int) $opts['page']) : 1;
    $limit = BLOG_PAGE_SIZE;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT " . TBL_blog . ".`id`,
                   " . TBL_blog . ".`subject`,
                   " . TBL_blog . ".`created`,
                   " . TBL_blog . ".`user_id`,
                   " . TBL_user . ".`username`,
                   (SELECT COUNT(*) FROM " . TBL_blog_comment . " WHERE " . TBL_blog_comment . ".`blog_id` = " . TBL_blog . ".`id`) AS `comment_count`
            FROM " . TBL_blog . "
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_blog . ".`user_id`
            WHERE " . TBL_blog . ".`hide` = 0
            ORDER BY " . TBL_blog . ".`created` DESC, " . TBL_blog . ".`id` DESC
            LIMIT ? OFFSET ?";
    $rows = $ci->db->query($sql, array($limit + 1, $offset))->result_array();
    $has_more = count($rows) > $limit;
    $rows = array_slice($rows, 0, $limit);

    $results = array();
    foreach ($rows as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'subject' => $row['subject'],
        'created' => $row['created'],
        'user_id' => (int) $row['user_id'],
        'username' => $row['username'],
        'comment_count' => (int) $row['comment_count']
      );
    }
    return array('rows' => $results, 'has_more' => $has_more);
  }
}

/**
  * Gets a single visible blog entry.
  *
  * @param int $id Blog entry id.
  *
  * @return array|null Entry (id, subject, text, created, updated, user_id,
  *                     username), or null if missing/hidden.
  */
if (!function_exists('getBlogEntry')) {
  function getBlogEntry($id) {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_blog . ".`id`,
                   " . TBL_blog . ".`subject`,
                   " . TBL_blog . ".`text`,
                   " . TBL_blog . ".`created`,
                   " . TBL_blog . ".`updated`,
                   " . TBL_blog . ".`user_id`,
                   " . TBL_user . ".`username`
            FROM " . TBL_blog . "
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_blog . ".`user_id`
            WHERE " . TBL_blog . ".`id` = ?
              AND " . TBL_blog . ".`hide` = 0";
    $row = $ci->db->query($sql, array($id))->row_array();
    if (!$row) {
      return null;
    }
    return array(
      'id' => (int) $row['id'],
      'subject' => $row['subject'],
      'text' => $row['text'],
      'created' => $row['created'],
      'updated' => $row['updated'],
      'user_id' => (int) $row['user_id'],
      'username' => $row['username']
    );
  }
}

/**
  * Gets every comment on a blog entry, oldest first.
  *
  * @param int $blog_id Blog entry id.
  *
  * @return array Comments (id, text, created, user_id, username - username
  *               is null for a comment whose user has since been deleted).
  */
if (!function_exists('getBlogComments')) {
  function getBlogComments($blog_id) {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_blog_comment . ".`id`,
                   " . TBL_blog_comment . ".`text`,
                   " . TBL_blog_comment . ".`created`,
                   " . TBL_blog_comment . ".`user_id`,
                   " . TBL_user . ".`username`
            FROM " . TBL_blog_comment . "
            LEFT JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_blog_comment . ".`user_id`
            WHERE " . TBL_blog_comment . ".`blog_id` = ?
            ORDER BY " . TBL_blog_comment . ".`created` ASC, " . TBL_blog_comment . ".`id` ASC";
    $rows = $ci->db->query($sql, array($blog_id))->result_array();

    $results = array();
    foreach ($rows as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'text' => $row['text'],
        'created' => $row['created'],
        'user_id' => ($row['user_id'] !== NULL) ? (int) $row['user_id'] : null,
        'username' => $row['username']
      );
    }
    return $results;
  }
}
?>
