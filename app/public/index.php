<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the products endpoint
// === Topics ===
$router->get('/topics', 'TopicController@getAll');
$router->get('/topics/(\d+)', 'TopicController@getTopic');
$router->post('/topics', 'TopicController@insertTopic');
$router->put('/topics/(\d+)', 'TopicController@update');
$router->delete('/topics/(\d+)', 'TopicController@delete');

// === Login ===
$router->post('/login', 'LoginController@login');
$router->get('/accounts', 'LoginController@getAll');
$router->get('/accounts/(\d+)', 'LoginController@getById');
$router->post('/accounts', 'LoginController@insert');
$router->put('/accounts/(\d+)', 'LoginController@update');

// Account Types
// === Account Types ===
$router->get('/account-types', 'AccountTypeController@getAll');
$router->get('/account-types/(\d+)', 'AccountTypeController@getById');
$router->post('/account-types', 'AccountTypeController@insert');
$router->put('/account-types/(\d+)', 'AccountTypeController@update');
$router->delete('/account-types/(\d+)', 'AccountTypeController@delete');

// ================
$router->run();
