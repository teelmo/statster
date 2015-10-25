<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('getSpotifyResourceId')) {
  function getSpotifyResourceId($artist, $album, &$title = '') {
    try {
      $artist = urldecode($artist);
      $album = urldecode($album);
      $title = '';
      $curl = new Curl(array(CURLOPT_TIMEOUT => SPOTIFY_TIMEOUT));
      if ($album) {
        $q = str_replace(array(':','%23',' '), '%20', $album);
        $q = str_replace(array('Ä','ä'), array('A', 'a'), $q);
        $q = str_replace(array('Ö','ö'), array('O', 'o'), $q);
        $content = $curl->getXML('http://ws.spotify.com/search/1/album?q=' . $q);
        $xml = new SimpleXMLElement($content, LIBXML_NOWARNING);
        if (!empty($xml->album) &&  is_object($xml->album)) {
          foreach ($xml->album as $album_node) {
            $territories = $album_node->availability->territories;
            $artist_name = $album_node->artist->name;
            if (spotifycmp($artist, $artist_name)) {
              // Haettu albumi löytyy
              if (stristr($territories, 'FI') == TRUE || stristr($territories, 'worldwide') == TRUE) {
                // On kuunneltavissa
                $first = each($album_node->attributes());
                $resource = explode(':', $first['value']['href']);
                return $resource[sizeof($resource) - 1];
              }
              else {
                if ($title) {
                  return false;
                }
              }
            }
          }
        }
        if (!$title) {
          // Album is not found.
        }
        return false;
      }
      $q = str_replace(array(':','%23',' '), '%20', $artist);
      $content = $curl->getXML('http://ws.spotify.com/search/1/artist?q=' . $q);
      $xml = new SimpleXMLElement($content, LIBXML_NOWARNING);
      if (!empty($xml->artist) && is_object($xml->artist)) {
        $first = each($xml->artist->attributes());
        $resource = explode(':', $first['value']['href']);
        return $resource[sizeof($resource)-1];
      }
      // $title = _('Artist doesn\'t exist in Spotify');
      return false;
    }
    catch (Exception $e) {
      // $title = sprintf(_('Spotify is currently unavailable: %s'), $e->getMessage());
      return false;
    }
    return false;
  }
}

if (!function_exists('spotifycmp')) {
  function spotifycmp($str_iso, $str_utf) {
    if (strcasecmp($str_iso, $str_utf) != 0) {
      $search = array('é', 'É', 'ii');
      $replace = array('e', 'E', '2');
      $str_iso = str_replace($search, $replace, $str_iso);
      if (strcasecmp(utf8_encode($str_iso), $str_utf) == 0) {
        return true;
      }
      return false;
    }
    return true;
  }
}

class Curl
{
  private $size = 0;
  private $max_file_size = 2000000; // bytes
  private $ch;
  private $fp;
  public $mime;
  public $content;
  public $allowed_mimes;
  public $status_code;
  public $status;
  
  public function __construct($options = array(), $url = '')
  {
    $this->ch = curl_init();
    if ($url) {
      curl_setopt($this->ch, CURLOPT_URL, $url);
    }
    curl_setopt($this->ch, CURLOPT_TIMEOUT, 5);
    
    foreach($options as $name => $value) {
      curl_setopt($this->ch, $name, $value);
    }
    curl_setopt($this->ch, CURLOPT_HEADERFUNCTION, array($this, 'readHeader'));
    
  }
  
  public function __destruct() {
    curl_close($this->ch);
  }
  
  public function downloadToFile($file, $url = '') {
    if ($url) {
      curl_setopt($this->ch, CURLOPT_URL, $url);
    }
    $this->fp = fopen($file, 'w');
    curl_setopt($this->ch, CURLOPT_WRITEFUNCTION, array($this, 'readBodyToFile'));
    curl_exec($this->ch);
    if (curl_errno($this->ch)) {
      unlink($file);
      fclose($this->fp);
      $this->curl_error = curl_error($this->ch);
      return false;
    }
    fclose($this->fp);
    return true;
  }
  
  public function downloadToMemory() {
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
    $this->content = curl_exec($this->ch);
    if (curl_errno($this->ch)) {
      return false;
    }
    return true;
  }
  
  public function getXML($url = '') {
    $this->content = '';
    if ($url) {
      curl_setopt($this->ch, CURLOPT_URL, $url);
    }
    $this->allowed_mimes = array('application/xml' => 'xml', 'text/xml' => 'xml');
    if (!$this->downloadToMemory()) {
      // curl error
      throw new Exception(curl_error($this->ch));
    }
    if ($this->status_code[0] != 2) {
      throw new Exception($this->status_code.' '.$this->status);
    }
    
    return $this->content;
  }

  private function readHeader($ch, $string) {
    //echo "Header: '$string'<br />\n";
    if (preg_match('/^HTTP\/1\..* [0-9]{3}/', $string)) {
      $this->status_code = preg_replace("/^HTTP\/1\..* ([0-9]{3})(.*)/is", "$1", $string);
      $this->status = preg_replace("/^HTTP\/1\..* [0-9]{3} (.*)/is", "$1", $string);
    }
    if (preg_match('/^Content-Type: /', $string)) {
      $this->mime = preg_replace("/^Content-Type: ([a-z\/\-]+)(.*)/is", "$1", $string);
      if (is_array($this->allowed_mimes) && !array_key_exists($this->mime, $this->allowed_mimes)) {
        return 0;
      }
    }
    if (preg_match('/^Content-Length: /', $string)) {
      $length = preg_replace("/^Content-Length: ([0-9]+)(.*)/is", "$1", $string);
      if ($length > $this->max_file_size) {
        return 0;
      }
    }
    return strlen($string);
  }
  
  private function readBodyToFile($ch, $string) {
    $this->size += strlen($string);
    if ($this->size < $this->max_file_size) {
      fwrite($this->fp, $string);
      return strlen($string);
    }
    return 0;
  }
}
?>