<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getListenedAlbums')) {
  function getListenedAlbums($opts = array()) {
    $json_data = file_get_contents('')
  }   
}

/* 
$data = array(
    'artist' => 'artist name'
    'album' => 'album name'
    'username' => 'username'
    'date' => 'listening date'
) */
if (!function_exists('addListening')) {
  function addListening($data) {
    
  }
}

/*
function addAlbum($artist, $album, $albumyear = 0, $username = 'admin') {
  $artist = stripExtraSpaces($artist);
  $album = stripExtraSpaces($album);
  $albumyear = stripExtraSpaces($albumyear);
  // Empty string
  if($artist == '' || $album == '')
  {
    return TAGS_MISSING;
  }
  if($albumyear != 0 && !checkAlbumyear($albumyear))
  {
    $albumyear = 0;
  }
  $tags_equalize = new TagsEqualizer($c, $flash);
  $data = $tags_equalize->artist_album($artist, $album);
  $artist = $data['artist'];
  $album = $data['album'];
  $user_id = getUserID($username);
  // Jos artistia ei ole luodaan se
  if(!$artist_id = getArtistID($artist)) {
    $q = "INSERT 
            INTO " . TBL_artist . " (`artist_name`, `user_id`, `created`) 
            VALUES (" . $this->db->escape(ucwords($artist)) . ", $user_id, NOW())";
    mysql_query($q,$c);
    $artist_id = mysql_insert_id();
  }
  $q = "INSERT 
          INTO " . TBL_album . " (`artist_id`, `album_name`, `year`, `user_id`, `created`) 
          VALUES ('$artist_id', 
                  " . $this->db->escape(ucwords($artist)) . ", 
                  " . $this->db->escape(ucwords($albumyear)) . ", 
                  $user_id, 
                  NOW())";
  mysql_query($q);
  if(mysql_affected_rows() < 1) {
    return ADDRECORD_FAILURE;
  }
  $album_id = getAlbumID($artist, $album);
  addDecade($artist, $album, $albumyear);
  addNationality($artist, $album, $username);
  return ADDRECORD_SUCCESS;
}
*/
?>