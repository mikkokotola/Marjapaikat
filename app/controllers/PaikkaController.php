<?php

/**
 * Paikkojen controller.
 * 
 * @author mkotola
 */

// TO DO: Tietyn marjastajan paikkojen listaaminen index-funktiolla.

class PaikkaController extends BaseController{
        
    public static function show($id){
        $paikka = Paikka::find($id);
        View::make('paikka/paikka.html', array('paikka' => $paikka));
    }
}
