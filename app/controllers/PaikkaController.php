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
        if (self::get_user_logged_in()->id == $marjastaja_id) {
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

    // Yksittäisen paikan näyttäminen.
    public static function show($marjastaja_id, $paikka_id) {
        $paikka = Paikka::find($paikka_id);
        if (self::get_user_logged_in()->id == $paikka->marjastaja_id && self::get_user_logged_in()->id == $marjastaja_id) {
            View::make('paikka/paikka.html', array('paikka' => $paikka));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
        
        
        
    }

}
