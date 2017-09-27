<?php

/**
 * Paikkojen controller.
 * 
 * @author mkotola
 */


class PaikkaController extends BaseController{
        
//    public static function paikat() {
//        View::make('paikka/paikat.html');
//    }
    
    public static function paikka() {
        View::make('paikka/paikka.html');
    }

    public static function paikat($marjastaja_id) {
        $marjastaja = Marjastaja::find($marjastaja_id);
        $paikat = Paikka::findByKayttaja($marjastaja_id);
        //Kint::dump($marjastaja);
        //Kint::dump($paikat);
        
        View::make('paikka/paikat.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja));
    }

    // TO DO: Tietyn marjastajan paikkojen listaaminen index-funktiolla.
    public static function show($id){
        $paikka = Paikka::find($id);
        View::make('paikka/paikka.html', array('paikka' => $paikka));
    }
}
