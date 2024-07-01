<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

$router->post('/login', 'UserController@login');
$router->post('/register', 'UserController@register');

$router->get('/vehicles/user', 'VehicleController@getVehiclesByUserId');
$router->post('/vehicle/register', 'VehicleController@register');
$router->post('/vehicle/check-in', 'VehicleController@checkIn');
$router->post('/vehicle/check-out', 'VehicleController@checkOut');
$router->get('/vehicles', 'VehicleController@getAllVehicles');

$router->post('/parking/allocate', 'ParkingController@allocateSpot');
$router->post('/parking/add', 'ParkingController@addParkingSpot');
$router->get('/parking/spots', 'ParkingController@getAllSpots'); 
$router->put('/parking/update', 'ParkingController@updateParkingSpot');
$router->delete('/parking/delete', 'ParkingController@deleteParkingSpot');
$router->post('/parking/remove', 'ParkingController@removeVehicle');

$router->post('/payments/get', 'PaymentController@getPaymentInfo');
$router->post('/payments/process', 'PaymentController@processPayment');
$router->get('/payments/all', 'PaymentController@getAllPayments');
$router->get('/payments/user', 'PaymentController@getAllPaymentsByUser');

// Run it!
$router->run();