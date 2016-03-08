<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Statster defined constants
|--------------------------------------------------------------------------
|
| These constants are defined by Statster and used only by Statster
|
*/
date_default_timezone_set('Europe/Helsinki');

define('TITLE', 'Statster');
//define('DASH', '–');
define('DASH', '–');

// Error msgs
define('ERR_BAD_REQUEST', '400: Bad request.');
define('ERR_NO_RESULTS', 'No results.');
define('ERR_NO_ARTIST', 'Artist error.');
define('ERR_NO_ALBUM', 'Album error.');
define('ERR_NO_USER', 'Album error.');
define('ERR_NO_EVENT', 'No events.');
define('ERR_NO_DATA', '<div class="empty_data_container">No data.</div>');
define('ERR_GENERAL', 'General error.');
define('ERR_INCORRECT_CREDENTIALS', 'Username or password error.');

// Autocomplete
define('AUTOCOMPLETE_MAX_HEIGHT', 312); #px
define('AUTOCOMPLETE_MIN_CHARS', 3); #px

// Time constants
define('BIO_UPDATE_TIME', 31622399); #One year.
define('MSG_FADEOUT', 3000);
define('JUST_LISTENED_INTERVAL', 1800);
define('CUR_DATE', date('Y-m-d')); #2009-12-15
define('CUR_YEAR', date('Y')); #2009
define('CUR_MONTH', date('m')); #12
define('SPOTIFY_TIMEOUT', 3); #seconds

// Database variables
define('TBL_album', 'album');
define('TBL_album_biography', 'album_biography');
define('TBL_album_comment', 'album_comment');
define('TBL_album_equalize', 'album_equalize');
define('TBL_artist', 'artist');
define('TBL_artist_biography', 'artist_biography');
define('TBL_artist_comment', 'artist_comment');
define('TBL_artist_equalize', 'artist_equalize');
define('TBL_associated_artist', 'assosiated_artist');
define('TBL_blog', 'blog');
define('TBL_blog_comment', 'blog_comment');
define('TBL_bmi', 'bmi');
define('TBL_bulletin', 'bulletin');
define('TBL_bulletins', 'bulletins');
define('TBL_fan', 'fan');
define('TBL_fan_log', 'fan_log');
define('TBL_foodbill', 'foodbill');
define('TBL_genre', 'genre');
define('TBL_genres', 'genres');
define('TBL_genre_equalize', 'genre_equalize');
define('TBL_keyword', 'keyword');
define('TBL_keywords', 'keywords');
define('TBL_listening', 'listening');
define('TBL_listening_comment', 'listening_comment');
define('TBL_listening_format', 'listening_format');
define('TBL_listening_format_type', 'listening_format_type');
define('TBL_listening_format_types', 'listening_format_types');
define('TBL_listening_formats', 'listening_formats');
define('TBL_listening_info', 'listening_info');
define('TBL_love', 'love');
define('TBL_love_log', 'love_log');
define('TBL_nationalities', 'nationalities');
define('TBL_nationality', 'nationality');
define('TBL_similar_artists', 'similar_artists');
define('TBL_store', 'store');
define('TBL_user', 'user');
define('TBL_user_album', 'user_album');
define('TBL_user_comment', 'user_comment');
define('TBL_user_info', 'user_info');

/* API keys */
define('LASTFM_API_KEY', 'b43a6b2eed67fe3672dbe43c102be8e6');

/* End of file constants.php */
/* Location: ./application/config/constants.php */