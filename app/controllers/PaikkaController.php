<?php

/**
 * Paikkojen controller.
 * 
 * @author mkotola
 */
class PaikkaController extends BaseController {

//    public static function paikat() {
//        View::make('paikka/paikat.html');
//    }
//    public static function paikka() {
//        View::make('paikka/paikka.html');
//    }
    // Tietyn marjastajan paikkojen listaaminen.
    public static function paikat($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

            View::make('paikka/paikat.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja));
        } else {
//            Kint::dump(self::get_user_logged_in()->kayttajatunnus);
//            Kint::dump(self::get_user_logged_in());
//            Kint::dump($marjastaja_id);


            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikanlisäämisnäkymään ohjaus.
    public static function lisaaPaikka($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

            View::make('paikka/paikat_lisaapaikka.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

//    // Uuden paikan tallentaminen tehty get-komennolla ja parametreillä. EI TOIMI VIELÄ.
//    public static function tallennaPaikka($marjastaja_id) {
//        if (self::check_logged_in_user($marjastaja_id)) {
//            $params = $_GET;
//            $attributes = array(
//                'marjastaja_id' => $marjastaja_id,
//                'p' => doubleval($params['lat']),
//                'i' => doubleval($params['lng']),
//                'nimi' => $params['nimi']
//            );
//
//            $paikka = new Paikka($attributes);
//            $errors = $paikka->errors();
//
//            if (count($errors) == 0) {
//                // Lisättävä paikka on validi.
//                $paikka->tallenna();
//                Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat', array('message' => 'Paikka lisätty!'));
//            } else {
//                // Paikassa oli vikaa, ei lisätä.
//                $marjastaja = Marjastaja::hae($marjastaja_id);
//                $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);
//
//                View::make('paikka/paikat.html', array('errors' => $errors, 'paikat' => $paikat, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
//            }
//        } else {
//            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
//        }
//    }

    // Uuden paikan tallentaminen tehty postilla.
    public static function tallennusKasittele($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;
            $attributes = array(
                'marjastaja_id' => $marjastaja_id,
                'p' => doubleval($params['lat']),
                'i' => doubleval($params['lng']),
                'nimi' => $params['nimi']
            );

            $paikka = new Paikka($attributes);
            $errors = $paikka->errors();

//            Kint::dump($paikka);
//            Kint::dump($errors);
            
            if (count($errors) == 0) {
                // Lisättävä paikka on validi.
                $paikka->tallenna();
                //Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat', array('message' => 'Paikka lisätty!'));
            } else {
                // Paikassa oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

                View::make('paikka/paikat.html', array('errors' => $errors, 'paikat' => $paikat, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Yksittäisen paikan näyttäminen.
    public static function nayta($marjastaja_id, $paikka_id) {
//        Kint::dump($marjastaja_id);
//        Kint::dump($paikka_id);
        
        $paikka = Paikka::hae($paikka_id);
        $marjastaja = Marjastaja::hae($marjastaja_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            View::make('paikka/paikka.html', array('paikka' => $paikka, 'marjastaja' => $marjastaja));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikan poistaminen
    public static function poista($marjastaja_id, $paikka_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $paikka = Paikka::hae($paikka_id);
            // Kutsutaan Paikka-luokan metodia delete, joka poistaa marjan sen id:llä
            $paikka->poista();

            // Ohjataan käyttäjä paikkojen listaussivulle ilmoituksen kera
            Redirect::to('/marjastaja/'. $marjastaja_id .'/paikat', array('message' => 'Paikka on poistettu'));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }
    
    // Paikan muokkaamisnäkymä
    public static function muokkaa($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $attributes = array(
                'marjastaja_id' => $marjastaja_id,
                'p' => $paikka->p,
                'i' => $paikka->i,
                'nimi' => $paikka->nimi
            );
            View::make('paikka/muokkaapaikkaa.html', array('paikka' => $paikka, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
        
    }
    
    // Paikan muokkaamislomakkeen käsittely.
    public static function muokkausKasittele($marjastaja_id, $paikka_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;
            $attributes = array(
                'id' => $paikka_id,
                'marjastaja_id' => $marjastaja_id,
                'p' => doubleval($params['p']),
                'i' => doubleval($params['i']),
                'nimi' => $params['nimi']
            );

            // Parsitaan stringeistä p ja i doublet.
            $paikka = new Paikka($attributes);
            $errors = $paikka->errors();

            //Kint::dump($paikka);

            if (count($errors) == 0) {
                // Muutettava paikka on validi.
                $paikka->tallennaMuuttunut();
                Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Paikan tiedot tallennettu'));
            } else {
                // Paikassa oli vikaa, ei muuteta.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikka = Paikka::hae($paikka_id);

                View::make('paikka/muokkaapaikkaa.html', array('paikka' => $paikka, 'marjastaja' => $marjastaja, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }

//        $paikka = Paikka::find($paikka_id);
//        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
//            View::make('paikka/muokkaapaikkaa.html', array('paikka' => $paikka, 'marjastaja' => $marjastaja));
//        } else {
//            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
//        }
        
    }


}
