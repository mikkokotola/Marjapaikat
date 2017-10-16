<?php

class BaseController {

    public static function get_user_logged_in() {
        // Katsotaan onko user-avain sessiossa
        if (isset($_SESSION['user'])) {
            $marjastaja_id = $_SESSION['user'];
            $user = Marjastaja::hae($marjastaja_id);

            return $user;
        }

        // Käyttäjä ei ole kirjautunut sisään
        return null;
    }

    public static function check_logged_in() {
        // Kirjautumisen tarkistus, onko käyttäjä kirjautunut millä tahansa tunnuksella.

        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään'));
        }
    }
    
    public static function check_logged_in_user($user_id) {
        // Kirjautumisen tarkistus, onko käyttäjä kirjautunut tietyllä tunnuksella.

        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään'));
        } else {
            $currentUser = self::get_user_logged_in();
            if ($currentUser->id == $user_id) {
                return true;
            } else {
                Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään'));
            }
            
        }
    }

}
