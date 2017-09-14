<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });

  $routes->get('/marja', function() {
    HelloWorldController::marja();
  });
  
  $routes->get('/marjastaja/paikat', function() {
    HelloWorldController::paikat();
  });
  
  $routes->get('/marjastaja/paikka', function() {
    HelloWorldController::paikka();
  });
