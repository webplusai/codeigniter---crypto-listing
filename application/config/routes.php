<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = 'page/show404';
$route['translate_uri_dashes'] = FALSE;
$route['loadfile/(:any)'] = 'welcome/loadfile';

$route['submit_entry_new'] = 'submit/submitEntryNew';
$route['stats/monthly'] = 'page/statsMonthly';
$route['api'] = 'page/api';
$route['partners'] = 'page/partners';
$route['slack'] = 'page/slack';
$route['link'] = 'page/link';
$route['packages'] = 'page/packages';
$route['icos'] = 'page/icos';
$route['stats'] = 'page/stats';
$route['advertise'] = 'page/advertise';
$route['about'] = 'page/about';
$route['press'] = 'page/press';
$route['terms'] = 'page/terms';
$route['disclaimer'] = 'page/disclaimer';
$route['privacypolicy'] = 'page/privacypolicy';
$route['cookies_policy'] = 'page/cookiespolicy';
$route['apply-filter'] = 'welcome/applyFilter';
$route['ico-list-(:any)'] = 'welcome/index';
$route['icos/(:any)/(:any)'] = 'welcome/projectDetail';
$route['icos/e(:any)'] = 'welcome/projectDetail';
$route['projects/(:any)/(:any)'] = 'welcome/projectDetail';
$route['projects/(:any)'] = 'welcome/projectDetail';
$route['people/(:any)/(:any)'] = 'welcome/personDetail';
$route['people/e(:any)/(:any)'] = 'welcome/personDetail';

$route['submit_entry'] = 'submit/submit_entry';
$route['submission_entry'] = 'submit/submission';
$route['payment'] = 'submit/payment';
$route['listings'] = 'listing/listings';
$route['listing'] = 'listing/edit';

$route['profile'] = 'user/profile';
$route['contact'] = 'user/contact';
$route['alerts'] = 'user/alerts';
$route['login'] = 'user/login';
$route['login/google'] = "user/googleAuthLink";
$route['login/googlecallback'] = "user/googleCallback";
$route['login/facebook'] = "user/facebookAuthLink";
$route['login/facebookcallback'] = "user/facebookCallback";
$route['logout'] = 'user/logout';
$route['register'] = 'user/register';
$route['forgot_password'] = 'user/forgot_password';
$route['change_password'] = "user/change_password_token";

// AJax
$route['ajax/delete_team_member'] = "ajax/deleteTeamMember";
$route['ajax/update_profile'] = "ajax/updateProfile";

$route['ajax/listing_project'] = "ajax/listingSaveProject";
$route['ajax/listing_link'] = "ajax/listingSaveLink";
$route['ajax/listing_distribution'] = "ajax/listingSaveDistribution";
$route['ajax/listing_team'] = "ajax/listingSaveTeam";
$route['ajax/listing_delete_member'] = "ajax/listingDeleteMember";
$route['ajax/scrape_website'] = "ajax/scrapeWebsite";
$route['ajax/scrape_logo'] = "ajax/scrapeLogo";

// Cron
$route['cron/push_payment'] = "cron/pushPayment";
$route['cron/payment'] = "cron/payment";
$route['cron/upgrade_payment'] = "cron/upgradePayment";
$route['cron/send_mail_submit'] = "cron/sendMailSubmit";
$route['cron/clean_cache'] = "cron/cleanAllCache";
$route['cron/clear_twig'] = "cron/clearTwigCache";
$route['cron/lastest_post'] = "cron/updateLastestPost";
$route['cron/btc_usd'] = "cron/btcToUsd";
$route['cron/scrape_event'] = "cron/scrapeEvent";
$route['cron/ico_total'] = "cron/icoTotal";
$route['cron/set_icorank'] = "cron/setIcoRank";
$route['cron/set_filterpanel'] = "cron/setFilterPanel";
$route['cron/scrape_press'] = "cron/scrapePressMention";
$route['cron/clean_cache_lastdb'] = "cron/cleanCacheLastDB";

// Sitemap
$route['sitemap.xml'] = "sitemap/index";
$route['sitemap_project.xml'] = "sitemap/project";
$route['sitemap_blog.xml'] = "sitemap/blog";

// Request API
$route['requestapi'] = "requestapi/index";

// API Endpoint
$route['endpoint/updatePaymentAmount'] = "rest/updatePaymentAmount";
$route['endpoint/createOrder'] = "rest/createOrder";
