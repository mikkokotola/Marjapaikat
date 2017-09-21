<?php

/**
 * Marjojen controller.
 *
 * @author mkotola
 */

class MarjaController extends BaseController {
       
    public static function index(){
        $marjat = Marja::all();
        $marjadata = array();
        
        //$poimineetKayttajatKuluvaVuosi = array();
        //$poimineetKayttajatKokoHistoria = array();
        $n = 0;
        foreach ($marjat as $marja) {
            $marjatiedot = array();
            $marjatiedot[] = $marja->id;
            $marjatiedot[] = $marja->nimi;
            $marjatiedot[] = Marjasaalis::maaraByMarjaAndVuosi($marja->id, date('Y'));
            $marjatiedot[] = Marjasaalis::maaraKokohistoriaByMarja($marja->id);
            $marjatiedot[] = count(Marjastaja::findByMarjaAndVuosi($marja->id, date('Y')));
            $marjatiedot[] = count(Marjastaja::findByMarja($marja->id));
            $marjadata[$n] = $marjatiedot;
            $n++;
        }
        
        
        View::make('marja/marjat.html', array('marjadata' => $marjadata));
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
    
    public static function show($marja_id){
        $marja = Marja::find($marja_id);
        $suosikkikayttajat = Marjastaja::findBySuosikkimarja($marja_id);
        $poimineetKayttajatKuluvaVuosi = Marjastaja::findByMarjaAndVuosi($marja_id, date('Y'));
        $poimineetKayttajatLkmKuluvaVuosi = count($poimineetKayttajatKuluvaVuosi);
        $poimineetKayttajatKokoHistoria = Marjastaja::findByMarja($marja_id);
        $poimineetKayttajatLkmKokoHistoria = count($poimineetKayttajatKokoHistoria);
        $marjanMaaraKokoHistoria = Marjasaalis::maaraKokohistoriaByMarja($marja_id);
        $marjanMaaraKuluvaVuosi = Marjasaalis::maaraByMarjaAndVuosi($marja_id, date('Y'));
        View::make('marja/marja.html', array(
            'marja' => $marja, 
            'suosikkikayttajat' => $suosikkikayttajat,
            'poimineetKayttajatLkmKuluvaVuosi' => $poimineetKayttajatLkmKuluvaVuosi,
            'poimineetKayttajatLkmKokoHistoria' => $poimineetKayttajatLkmKokoHistoria,
            'marjanMaaraKokoHistoria' => $marjanMaaraKokoHistoria,
            'marjanMaaraKuluvaVuosi' => $marjanMaaraKuluvaVuosi
        ));
    }
    
    
}
