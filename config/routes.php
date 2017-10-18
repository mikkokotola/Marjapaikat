<?php

// Kirjautumislomakkeen esittäminen
$routes->get('/login', function() {
    MarjastajaController::kirjauduSisaan();
});

// Kirjautumisen käsittely
$routes->post('/login', function() {
    MarjastajaController::kasitteleKirjautuminen();
});

// Uloskirjautumisen käsittely
$routes->post('/logout', function(){
    MarjastajaController::kirjauduUlos();
});

// Etusivu, marjojen listausnäkymä.
$routes->get('/', function() {
    MarjaController::index();
});

// Etusivu, marjojen listausnäkymä. Vaihtoehtoinen linkki.
$routes->get('/marjat', function() {
    MarjaController::index();
});

$routes->get('/marjat/uusi', function() {
    MarjaController::lisaaMarja();
});

$routes->post('/marjat/tallennaUusi', function() {
    MarjaController::tallennaMarja();
});


$routes->get('/marja/:id', function($id) {
    MarjaController::nayta($id);
});

$routes->get('/marja/:id/muokkaa', function($id) {
    MarjaController::muokkausNakyma($id);
});

$routes->post('/marja/muokkaa', function() {
    MarjaController::muutaNimeaKasittele();
});

$routes->post('/marja/:id/poista', function($id) {
    MarjaController::poista($id);
});


// Käyttäjän kaikkien paikkojen näyttäminen.
$routes->get('/marjastaja/:id/paikat', function($id) {
    PaikkaController::paikat($id);
});

// Uuden paikan lisäysnäkymä.
$routes->get('/marjastaja/:id/paikat/uusi', function($id) {
    PaikkaController::lisaaPaikka($id);
});

// Uuden paikan tallentaminen.
$routes->post('/marjastaja/:id/paikat/tallennaUusi', function($id) {
    PaikkaController::tallennusKasittele($id);
});

// Paikan näyttäminen
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id', function($marjastaja_id, $paikka_id) {
//    Kint::dump($marjastaja_id);
//    Kint::dump($paikka_id);
    PaikkaController::nayta($marjastaja_id, $paikka_id);
});


// Paikan editointinäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/muokkaa', function($marjastaja_id, $paikka_id) {
    PaikkaController::muokkaaPaikka($marjastaja_id, $paikka_id);
});

// Paikan tietojen muuttamisen käsittely.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/muokkaa', function($marjastaja_id, $paikka_id) {
    
    PaikkaController::paikanMuokkausKasittele($marjastaja_id, $paikka_id);
});

// Paikan poistaminen.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/poista', function($marjastaja_id, $paikka_id) {
    
    PaikkaController::poistaPaikka($marjastaja_id, $paikka_id);
});


// Uuden käynnin lisäysnäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/uusi', function($marjastaja_id, $paikka_id) {
    PaikkaController::lisaaKaynti($marjastaja_id, $paikka_id);
});

// Uuden käynnin tallentaminen
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/tallennaUusi', function($marjastaja_id, $paikka_id) {
    PaikkaController::tallennaUusiKaynti($marjastaja_id, $paikka_id);
});

// Käynnin editointinäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/muokkaa', function($marjastaja_id, $paikka_id, $kaynti_id) {
    PaikkaController::muokkaaKaynti($marjastaja_id, $paikka_id, $kaynti_id);
});

// Käynnin muuttuneen tiedon tallentaminen
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/muokkaa', function($marjastaja_id, $paikka_id, $kaynti_id) {
    PaikkaController::kaynninMuokkausKasittele($marjastaja_id, $paikka_id, $kaynti_id);
});

// Käynnin poistaminen.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/poista', function($marjastaja_id, $paikka_id, $kaynti_id) {
    
    PaikkaController::poistaKaynti($marjastaja_id, $paikka_id, $kaynti_id);
});


// Uuden marjasaaliin lisäysnäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/saalis/uusi', function($marjastaja_id, $paikka_id, $kaynti_id) {
    PaikkaController::lisaaSaalis($marjastaja_id, $paikka_id, $kaynti_id);
});

// Uuden marjasaaliin tallentaminen
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/saalis/tallennaUusi', function($marjastaja_id, $paikka_id, $kaynti_id) {
    PaikkaController::tallennaUusiSaalis($marjastaja_id, $paikka_id, $kaynti_id);
});

// Marjasaaliin muokkausnäkymä
$routes->get('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/saalis/:marja_id/muokkaa', function($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
    PaikkaController::muokkaaSaalis($marjastaja_id, $paikka_id, $kaynti_id, $marja_id);
});

// Marjasaaliin muuttuneen tiedon tallentaminen
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/saalis/:marja_id/muokkaa', function($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
    PaikkaController::saaliinMuokkausKasittele($marjastaja_id, $paikka_id, $kaynti_id, $marja_id);
});

// Marjasaaliin poistaminen.
$routes->post('/marjastaja/:marjastaja_id/paikat/:paikka_id/kaynti/:kaynti_id/saalis/:marja_id/poista', function($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
    
    PaikkaController::poistaSaalis($marjastaja_id, $paikka_id, $kaynti_id, $marja_id);
});




$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

