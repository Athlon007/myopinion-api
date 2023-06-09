<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// === Topics ===
$router->get('/topics', 'TopicController@getAll');
$router->get('/topics/(\d+)', 'TopicController@getTopic');
$router->post('/topics', 'TopicController@insertTopic');
$router->put('/topics/(\d+)', 'TopicController@update');
$router->delete('/topics/(\d+)', 'TopicController@delete');
$router->get('/topics/today', 'TopicController@getTodayTopic');

// === Login ===
$router->post('/accounts/login', 'LoginController@login');
$router->get('/accounts', 'LoginController@getAll');
$router->get('/accounts/(\d+)', 'LoginController@getById');
$router->post('/accounts', 'LoginController@insert');
$router->put('/accounts/(\d+)', 'LoginController@update');
$router->delete('/accounts/(\d+)', 'LoginController@delete');
$router->get('/accounts/me', 'LoginController@getMe');

// === Account Types ===
$router->get('/account-types', 'AccountTypeController@getAll');
$router->get('/account-types/(\d+)', 'AccountTypeController@getById');
$router->post('/account-types', 'AccountTypeController@insert');
$router->put('/account-types/(\d+)', 'AccountTypeController@update');
$router->delete('/account-types/(\d+)', 'AccountTypeController@delete');

// === Opinions ===
$router->get('/topics/(\d+)/opinions', 'OpinionController@getAllForTopic');
$router->get('/opinions/(\d+)', 'OpinionController@get');
$router->get('/topics/today/opinions', 'OpinionController@getTodayOpinions');
$router->post('/opinions', 'OpinionController@insert');
$router->put('/opinions/(\d+)', 'OpinionController@update');
$router->delete('/opinions/(\d+)', 'OpinionController@delete');

// === Settings ===
$router->get('/settings', 'SettingsController@getAll');
$router->put('/settings', 'SettingsController@update');
$router->patch('/settings/force-next-topic', 'SettingsController@forceNextTopic');

// === Reactions ===
$router->post('/react/(\d+)', 'ReactionController@react');
$router->get('/reactions', 'ReactionController@getAvailableReactions');
$router->post('/reactions', 'ReactionController@insert');
$router->put('/reactions/(\d+)', 'ReactionController@update');
$router->delete('/reactions/(\d+)', 'ReactionController@delete');

// === Reports ===
$router->get('/opinions/reports', 'ReportController@getAll');
$router->get('/reports/types', 'ReportController@getReportTypes');
$router->get('/opinions/(\d+)/reports', 'ReportController@getForOpinion');
$router->post('/opinions/report/(\d+)', 'ReportController@report');
$router->delete('/opinions/report/(\d+)', 'ReportController@pardon');

// ================
$router->run();
