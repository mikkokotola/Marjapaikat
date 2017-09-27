<?php

/**
 * Paikkojen controller.
 * 
 * @author mkotola
 */


class PaikkaController extends BaseController{
        
    public static function paikat() {
        View::make('paikka/paikat.html');
    }
    
    public static function paikka() {
        View::make('paikka/paikka.html');
    }


    // TO DO: Tietyn marjastajan paikkojen listaaminen index-funktiolla.
    public static function show($id){
        $paikka = Paikka::find($id);
        View::make('paikka/paikka.html', array('paikka' => $paikka));
    }
}
