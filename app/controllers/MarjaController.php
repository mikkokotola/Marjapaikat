<?php

/**
 * Marjojen controller.
 *
 * @author mkotola
 */

class MarjaController extends BaseController {
    public static function index(){
        $marjat = Marja::all();
        View::make('marja/marjat.html', array('marjat' => $marjat));
    }
    
    public static function lisaaMarja(){
        $marjat = Marja::all();
        View::make('marja/marjat_lisaamarja.html', array('marjat' => $marjat));
    }
    
    public static function tallennaMarja(){
        $params = $_POST;
        $marja = new Marja(array(
           'nimi' => $params['nimi'] 
        ));
        $marja->save();
        Redirect::to('/marjat', array('message' => 'Marja lisätty!'));
    }
    
    public static function show($id){
        $marja = Marja::find($id);
        //$suosikkimarjat = Suosikkimarja::findByMarja($id);
        //KESKEN $suosikkikayttajat = Suosikkikayttajat::
        //KESKEN View::make('marja/marja.html', array('marja' => $marja, 'suosikkikayttajat' => $suosikkikayttajat));
    }
    
    
}
