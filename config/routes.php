<?php

// Kirjautumislomakkeen esittäminen
$routes->get('/login', function() {
    MarjastajaController::login();
});

// Kirjautumisen käsittely
$routes->post('/login', function() {
    MarjastajaController::handle_login();
});

// Uloskirjautumisen käsittely
$routes->post('/logout', function(){
    MarjastajaController::logout();
});


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

$routes->get('/marjastaja/:id/paikat/new', function($id) {
    PaikkaController::addPaikka($id);
});

// Uuden paikan tallentaminen googlemaps-kartalta tehty get-komennolla ja parametreillä.
$routes->get('/marjastaja/:id/paikat/tallenna', function($id) {
    PaikkaController::savePaikka($id);
});

// Uuden paikan tallentaminen lomakkeella.
$routes->post('/marjastaja/:id/paikat/save', function($id) {
    PaikkaController::savePaikkaForm($id);
});

//$routes->get('/marjastaja/:id/paikat/save', function($id) {
//    PaikkaController::savePaikkaForm($id);
//});


// Paikan poistaminen.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/delete', function($marjastaja_id, $paikka_id) {
    
    PaikkaController::delete($marjastaja_id, $paikka_id);
});

// Paikan editointinäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/edit', function($marjastaja_id, $paikka_id) {
    PaikkaController::edit($marjastaja_id, $paikka_id);
});

$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id', function($marjastaja_id, $paikka_id) {
//    Kint::dump($marjastaja_id);
//    Kint::dump($paikka_id);
    PaikkaController::show($marjastaja_id, $paikka_id);
});

// Paikan tietojen muuttaminen.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/saveChanged', function($marjastaja_id, $paikka_id) {
    
    PaikkaController::saveChanged($marjastaja_id, $paikka_id);
});


// Poistettava lopuksi, testireitti. Vastaava oikea on toteutettu.
$routes->get('/marja', function() {
    HelloWorldController::marja();
});

$routes->get('/marjat/new', function() {
    MarjaController::addMarja();
});

$routes->post('/marjat/new', function() {
    MarjaController::saveMarja();
});


$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
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



