<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('/suunnitelmat/marjat.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $marja1 = new Marja(array(
            'nimi' => ''
        ));
        $errors = $marja1->errors();

        

        $marja2 = new Marja(array(
            'nimi' => 'Puolukka'
        ));
        $errors2 = $marja2->errors();

        Kint::dump($errors);
        Kint::dump($errors2);
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
        View::make('paikka/paikka.html');
    }

}
