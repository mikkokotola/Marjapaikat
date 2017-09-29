<?php

$routes->get('/', function() {
    MarjaController::index();
});

$routes->get('/marjat', function() {
    MarjaController::index();
});

// Poistettava lopuksi, testireitti.
//$routes->get('/marjastaja/paikka', function() {
//    PaikkaController::show(1);
//});

$routes->get('/marjastaja/:id/paikat', function($id) {
    PaikkaController::paikat($id);
});

$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id', function($marjastaja_id, $paikka_id) {
    // TO DO: Testattava, että marjastaja on oikea (että paikka liittyy tähän marjastajaan).

    PaikkaController::show($marjastaja_id, $paikka_id);
});


// Poistettava lopuksi, testireitti. Vastaava oikea on toteutettu.
$routes->get('/marja', function() {
    HelloWorldController::marja();
});

$routes->get('/marjat/new', function() {
    MarjaController::lisaaMarja();
});

$routes->post('/marjat/new', function() {
    MarjaController::tallennaMarja();
});


$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

// Kirjautumislomakkeen esittäminen
$routes->get('/login', function() {
    MarjastajaController::login();
});

// Kirjautumisen käsittely
$routes->post('/login', function() {
    MarjastajaController::handle_login();
});


$routes->get('/marja/:id', function($id) {
    MarjaController::show($id);
});

$routes->get('/marja/:id/rename', function($id) {
    MarjaController::rename($id);
});

$routes->post('/marja/:id/delete', function($id) {
    MarjaController::delete($id);
});


$routes->post('/marja/rename', function() {
    MarjaController::renameMarja();
});



