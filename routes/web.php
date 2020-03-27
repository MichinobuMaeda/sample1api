<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('items', 'ItemController@index');
$router->get('items/{id}', 'ItemController@show');
$router->post('items', 'ItemController@store');
$router->put('items/{id}', 'ItemController@update');
$router->delete('items/{id}', 'ItemController@destroy');
