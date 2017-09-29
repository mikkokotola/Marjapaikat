<?php

class MarjastajaController extends BaseController {

    public static function login() {
        View::make('marjastaja/kirjaudu.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $marjastaja = Marjastaja::authenticate($params['kayttajatunnus'], $params['salasana']);

        if (!$marjastaja) {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana', 'kayttajatunnus' => $params['kayttajatunnus']));
        } else {
            $_SESSION['user'] = $marjastaja->id;

            Redirect::to('/marjastaja/' . $marjastaja->id . '/paikat', array('message' => 'Tervetuloa takaisin ' . $marjastaja->etunimi . '!'));
        }
    }

}
