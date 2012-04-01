<?php
class AddListening extends CI_Controller {
  public function index() {
    if !($album_id = getAlbumID($data['artist'], $data['album']) && $user_id = getUserID($data['username']);)
      json_encode('')
    }
    $q = "INSERT 
            INTO ".TBL_listening." (`user_id`, `album_id`, `date`) 
            VALUES ($user_id, $album_id, '$date')";
    mysql_query ($q, $c);    
    if(mysql_affected_rows() < 1) {

    }
    $listening_id = mysql_insert_id();
  }
}
?>