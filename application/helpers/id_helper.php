<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get artist's ID.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *
  * @return int Artist ID or boolean FALSE.
  */
if (!function_exists('getArtistID')) {
  function getArtistID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '';
    $sql = "SELECT " . TBL_artist . ".`id`
            FROM " . TBL_artist . "
            WHERE " . TBL_artist . ".`artist_name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($artist_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }
}

/**
  * Get album's ID.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          'album_name'  => Album name
  *          'year'  => Year
  *
  * @return int Album ID or boolean FALSE.
  */
if (!function_exists('getAlbumID')) {
  function getAlbumID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '';
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '';
    $year = isset($opts['year']) ? $opts['year'] : '%';
    $sql = "SELECT " . TBL_album . ".`id`
            FROM " . TBL_album . ",
                 " . TBL_artists . ",
                 " . TBL_artist . "
            WHERE " . TBL_album . ".`id` = " . TBL_artists . ".`album_id`
              AND " . TBL_artist . ".`id` = " . TBL_artists . ".`artist_id`
              AND " . TBL_album . ".`year` LIKE ?
              AND " . TBL_artist . ".`artist_name` = ?
              AND " . TBL_album . ".`album_name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($year, $artist_name, $album_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }
}

/**
  * Get artist's album IDs.
  *
  * @param array $opts.
  *          'artist_id'  => Artist ID
  *
  * @return array Album ID or boolean FALSE.
  */
if (!function_exists('getAlbumIDs')) {
  function getAlbumIDs($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = isset($opts['artist_id']) ? $opts['artist_id'] : '';
    $sql = "SELECT " . TBL_album . ".`id` as `album_id`
            FROM " . TBL_album . ",
                 " . TBL_artists . " 
            WHERE " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`artist_id` = ?";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? $query->result() : FALSE;
  }
}

/**
  * Get user's ID.
  *
  * @param array $opts.
  *          'username'  => Username
  *
  * @return int User ID or boolean FALSE.
  */
if (!function_exists('getUserID')) {
  function getUserID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = isset($opts['username']) ? $opts['username'] : '';
    $sql = "SELECT " . TBL_user . ".`id`
            FROM " . TBL_user . "
            WHERE " . TBL_user . ".`username` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($username));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }
}

/**
  * Get format's ID.
  *
  * @param array $opts.
  *          'format'  => Format name
  *
  * @return int Format ID or boolean FALSE.
  */
if (!function_exists('getFormatID')) {
  function getFormatID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_name = isset($opts['format_name']) ? $opts['format_name'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`id`
            FROM " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($format_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }   
}

/**
  * Get format type's ID.
  *
  * @param array $opts.
  *          'format_type'  => Format type name
  *
  * @return int Format type ID or boolean FALSE.
  */
if (!function_exists('getFormatTypeID')) {
  function getFormatTypeID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_name = isset($opts['format_name']) ? $opts['format_name'] : '';
    $format_type_name = isset($opts['format_type_name']) ? $opts['format_type_name'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`id`
            FROM " . TBL_listening_format . ",
                 " . TBL_listening_format_type . "
            WHERE " . TBL_listening_format . ".`id` = " . TBL_listening_format_type . ".`listening_format_id`
              AND " . TBL_listening_format . ".`name` = ?
              AND " . TBL_listening_format_type . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($format_name, $format_type_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }   
}

/**
  * Get genre's ID.
  *
  * @param array $opts.
  *          'tag_name'  => Genre name
  *
  * @return int Genre ID or boolean FALSE.
  */
if (!function_exists('getGenreID')) {
  function getGenreID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_name = isset($opts['tag_name']) ? $opts['tag_name'] : '';
    $sql = "SELECT " . TBL_genre . ".`id`
            FROM " . TBL_genre . "
            WHERE " . TBL_genre . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($tag_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }   
}

/**
  * Get keyword's ID.
  *
  * @param array $opts.
  *          'tag_name'  => Keyword name
  *
  * @return int Keyword ID or boolean FALSE.
  */
if (!function_exists('getKeywordID')) {
  function getKeywordID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_name = isset($opts['tag_name']) ? $opts['tag_name'] : '';
    $sql = "SELECT " . TBL_keyword . ".`id`
            FROM " . TBL_keyword . "
            WHERE " . TBL_keyword . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($tag_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }   
}

/**
  * Get nationality's ID.
  *
  * @param array $opts.
  *          'tag_name'  => Nationality name
  *
  * @return int nationality ID or boolean FALSE.
  */
if (!function_exists('getNationalityID')) {
  function getNationalityID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_name = isset($opts['tag_name']) ? $opts['tag_name'] : '';
    
    $sql = "SELECT " . TBL_nationality . ".`id`
            FROM " . TBL_nationality . "
            WHERE " . TBL_nationality . ".`country` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($tag_name));
    return ($query->num_rows() > 0) ? $query->result()[0]->id : FALSE;
  }   
}
?>