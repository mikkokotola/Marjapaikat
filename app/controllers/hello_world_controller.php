<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('/suunnitelmat/marjat.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $marja1 = Marja::find(1);
        $marjat = Marja::all();
        Kint::dump($marja1);
        Kint::dump($marjat);

        //View::make('helloworld.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function marja() {
        View::make('suunnitelmat/marja.html');
    }

    public static function paikat() {
        View::make('suunnitelmat/paikat.html');
    }
    
    public static function paikka() {
        View::make('suunnitelmat/paikka.html');
    }

}
