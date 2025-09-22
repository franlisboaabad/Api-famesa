<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*
|--------------------------------------------------------------------------
| HGLevel API Routes
|--------------------------------------------------------------------------
|
| Rutas para recibir y procesar datos de HGLevel
|
*/

// Endpoint para recibir datos de HGLevel
$router->post('/api/hglevel/receive', 'HGLevelController@receiveData');
$router->post('/hglevel/receive', 'HGLevelController@receiveData'); // Sin /api

// Endpoints para consultar datos
$router->get('/api/contacts', 'HGLevelController@getContacts');
$router->get('/contacts', 'HGLevelController@getContacts'); // Sin /api
$router->get('/api/contacts/{contactId}', 'HGLevelController@getContact');
$router->get('/contacts/{contactId}', 'HGLevelController@getContact'); // Sin /api

// Endpoint de prueba
$router->get('/api/test', function () {
    return response()->json([
        'message' => 'API funcionando correctamente',
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ]);
});

$router->get('/test', function () {
    return response()->json([
        'message' => 'API funcionando correctamente',
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ]);
});
