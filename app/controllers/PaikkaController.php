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
            $marjastaja = Marjastaja::find($marjastaja_id);
            $paikat = Paikka::findByKayttaja($marjastaja_id);

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
            $marjastaja = Marjastaja::find($marjastaja_id);
            $paikat = Paikka::findByKayttaja($marjastaja_id);

            View::make('paikka/paikat_lisaapaikka.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Uuden paikan tallentaminen tehty get-komennolla ja parametreillä. EI TOIMI VIELÄ.
    public static function tallennaPaikka($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $params = $_GET;
            $attributes = array(
                'marjastaja' => $marjastaja_id,
                'p' => $params['lat'],
                'i' => $params['lng'],
                'nimi' => $params['nimi']
            );

            // Parsitaan stringeistä p ja i doublet.
            $paikka = new Paikka($attributes);
            $errors = $paikka->errors();

            //Kint::dump($attributes);

            if (count($errors) == 0) {
                // Lisättävä paikka on validi.
                $paikka->save();
                Redirect::to('/marjastaja/'.$marjastaja_id.'/paikat', array('message' => 'Paikka lisätty!'));
            } else {
                // Paikassa oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::find($marjastaja_id);
                $paikat = Paikka::findByKayttaja($marjastaja_id);

                View::make('paikka/paikat.html', array('errors' => $errors, 'paikat' => $paikat, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
            }

        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Uuden paikan tallentaminen tehty postilla.
    public static function tallennaPaikkaLomake($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;
            $attributes = array(
                'nimi' => $params['nimi'],
                'p' => $params['lat'],
                'i' => $params['lng'],
            );

            Kint::dump($attributes);
            //$marjastaja = Marjastaja::find($marjastaja_id);
            //$paikat = Paikka::findByKayttaja($marjastaja_id);
            //View::make('paikka/paikat_lisaapaikka.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja));
        } else {
            //View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Yksittäisen paikan näyttäminen.
    public static function show($marjastaja_id, $paikka_id) {
        $paikka = Paikka::find($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            View::make('paikka/paikka.html', array('paikka' => $paikka));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

}
