<?php

class MarjastajaController extends BaseController {

    public static function kirjauduSisaan() {
        View::make('marjastaja/kirjaudu.html');
    }

    public static function kirjauduUlos() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function kasitteleKirjautuminen() {
        $params = $_POST;
        $attributes = array(
            'kayttajatunnus' => $params['kayttajatunnus'],
            'salasana' => $params['salasana']
        );
        $marjastaja = new Marjastaja($attributes);
        $errors = $marjastaja->errors();

        if (!count($errors) == 0) {
            View::make('marjastaja/kirjaudu.html', array('errors' => $errors, 'kayttajatunnus' => $params['kayttajatunnus']));
        }

        $marjastaja = Marjastaja::tunnistaKayttaja($params['kayttajatunnus'], $params['salasana']);
        
        if (!$marjastaja) {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana', 'kayttajatunnus' => $params['kayttajatunnus']));
        }

        $_SESSION['user'] = $marjastaja->id;

        Redirect::to('/marjastaja/' . $marjastaja->id . '/paikat', array('message' => 'Tervetuloa takaisin ' . $marjastaja->etunimi . '!'));
    }

}
