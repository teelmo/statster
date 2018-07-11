<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'main';
$route['404_override'] = '';

/*
 * Statster defined routes
 * 
 * http://ellislab.com/codeigniter/user-guide/general/routing.html
 *
 * You can match literal values or you can use two wildcard types:
 *
 * (:num) will match a segment containing only numbers.
 * (:any) will match a segment containing any character.
 */

/* Artist and albums page's routes */
$route['artist/(:num)'] = 'artist/stats/$1';
$route['artist/(:num)/(:num)'] = 'artist/stats/$1/$2';
$route['artist/(:num)/(:num)/(:num)'] = 'artist/stats/$1/$2/$3';

$route['album/(:num)'] = 'album/stats/$1';
$route['album/(:num)/(:num)'] = 'album/stats/$1/$2';
$route['album/(:num)/(:num)/(:num)'] = 'album/stats/$1/$2/$3';

/* Artist and albums page's routes */
$route['music/(:any)/(:any)'] = 'music/album_or_month/$1/$2';
$route['music/(:any)'] = 'music/artist_or_year/$1';

/* Genres, tags and release years page's routes */
$route['genre'] = 'tag/genre';
$route['genre/(:any)'] = 'tag/genre/$1';
$route['genre/(:any)/album'] = 'tag/genre/$1/album';
$route['genre/(:any)/artist'] = 'tag/genre/$1/artist';
$route['keyword'] = 'tag/keyword';
$route['keyword/(:any)'] = 'tag/keyword/$1';
$route['keyword/(:any)/album'] = 'tag/keyword/$1/album';
$route['keyword/(:any)/artist'] = 'tag/keyword/$1/artist';
$route['nationality'] = 'tag/nationality';
$route['nationality/(:any)'] = 'tag/nationality/$1';
$route['nationality/(:any)/album'] = 'tag/nationality/$1/album';
$route['nationality/(:any)/artist'] = 'tag/nationality/$1/artist';
$route['year'] = 'tag/year';
$route['year/(:any)'] = 'tag/year/$1';
$route['year/(:any)/album'] = 'tag/year/$1/album';
$route['year/(:any)/artist'] = 'tag/year/$1/artist';

/* Recent page's routes */
$route['recent'] = 'music/recent';
$route['recent/(:any)/(:any)'] = 'music/recent/$1/$2';
$route['recent/(:any)'] = 'music/recent/$1';

/* Like page's routes */
$route['fan'] = 'like/fan';
$route['love'] = 'like/love';

/* Listener page's routes */
$route['listener'] = 'music/listener';
$route['listener/(:any)/(:any)'] = 'music/listener/$1/$2';
$route['listener/(:any)'] = 'music/listener/$1';

/* Tag page's routes */
$route['tag/(:any)/(:any)'] = 'tag/index/$1/$2';
$route['tag/(:any)'] = 'tag/index/$1';

/* Listening format page's routes */
$route['format/(:any)/(:any)'] = 'format/index/$1/$2';
$route['format/(:any)'] = 'format/index/$1';

/* Like page's routes */
$route['like/(:any)/(:any)'] = 'like/index/$1/$2';
$route['like/(:any)'] = 'like/index/$1';

/* Shout page's routes */
$route['shout/(:any)/(:any)'] = 'shout/index/$1/$2';
$route['shout/(:any)'] = 'shout/index/$1';

/* User page's routes */
$route['user/profile/(:any)'] = 'user/profile/$1';
$route['user/profile'] = 'user';
$route['user/edit'] = 'user/edit';
$route['user/(:any)'] = 'user/profile/$1';

/* Meta page's routes */
$route['about'] = 'main/about';
$route['career'] = 'main/career';
$route['developers'] = 'main/developers';
$route['privacy'] = 'main/privacy';
$route['terms'] = 'main/terms';

/* End of file routes.php */
/* Location: ./application/config/routes.php */