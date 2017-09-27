<?php

/**
 * Marjojen controller.
 *
 * @author mkotola
 */
class MarjaController extends BaseController {

    public static function index() {
        $marjadata = self::haeMarjadata();

        View::make('marja/marjat.html', array('marjadata' => $marjadata));
    }

    public static function lisaaMarja() {
        $marjadata = self::haeMarjadata();
        View::make('marja/marjat_lisaamarja.html', array('marjadata' => $marjadata));
    }

    public static function tallennaMarja() {
        $params = $_POST;
        $attributes = array(
            'nimi' => $params['nimi']
        );
        $marja = new Marja($attributes);
        $errors = $marja->errors();

        
        if (count($errors) == 0) {
            // Lisättävä marja on validi.
            $marja->save();
            
            Redirect::to('/');
            //Redirect::to('/marjat', array('message' => 'Marja lisätty!'));
        } else {
            // Marjassa oli vikaa, ei lisätä.
            Kint::dump($marja);
        Kint::dump($errors);
//            $marjadata = self::haeMarjadata();
//            View::make('marja/marjat_lisaamarja.html', array('errors' => $errors, 'marjadata' => $marjadata, 'attributes' => $attributes));
            //Redirect::to('/');
        }

        
    }

    public static function show($marja_id) {
        $marja = Marja::find($marja_id);
        $suosikkikayttajat = Marjastaja::findBySuosikkimarja($marja_id);
        $poimineetKayttajatKuluvaVuosi = Marjastaja::findByMarjaAndVuosi($marja_id, date('Y'));
        $poimineetKayttajatLkmKuluvaVuosi = count($poimineetKayttajatKuluvaVuosi);
        $poimineetKayttajatKokoHistoria = Marjastaja::findByMarja($marja_id);
        $poimineetKayttajatLkmKokoHistoria = count($poimineetKayttajatKokoHistoria);
        $marjanMaaraKokoHistoria = Marjasaalis::maaraKokohistoriaByMarja($marja_id);
        $marjanMaaraKuluvaVuosi = Marjasaalis::maaraByMarjaAndVuosi($marja_id, date('Y'));


        $marjanTopPoimijat = Marjastaja::karkipoimijatByMarjaAndVuosi($marja_id, date('Y'));

//        // Tehdään taulukko tänä vuonna poimineiden marjastajien poimituista marjamääristä näkymää varten.
//        $marjanTopPoimijat = array();
//        $n = 0;
//        foreach ($poimineetKayttajatKuluvaVuosi as $kayttaja) {
//            $topListaRivi = array();
//            $topListaRivi[] = $kayttaja->etunimi . " " . $kayttaja->sukunimi;
//            $topListaRivi[] = Marjasaalis::maaraByMarjaAndVuosiAndKayttaja($marja_id, date('Y'), $kayttaja->id);
//            $topListaRivi[] = Marjasaalis::maaraByMarjaAndKayttaja($marja_id, $kayttaja->id);
//            $marjanTopPoimijat[$n] = $topListaRivi;
//            $n++;
//        }
//        // Sortataan lista kuluvan vuoden poimittujen määrän mukaiseen järjestykseen, isoin ensin.
//        //sorttaaja::sortArrayByKey($marjanTopPoimijat, "", false, false);
//        
        View::make('marja/marja.html', array(
            'marja' => $marja,
            'suosikkikayttajat' => $suosikkikayttajat,
            'poimineetKayttajatLkmKuluvaVuosi' => $poimineetKayttajatLkmKuluvaVuosi,
            'poimineetKayttajatLkmKokoHistoria' => $poimineetKayttajatLkmKokoHistoria,
            'marjanMaaraKokoHistoria' => $marjanMaaraKokoHistoria,
            'marjanMaaraKuluvaVuosi' => $marjanMaaraKuluvaVuosi,
            'marjanTopPoimijat' => $marjanTopPoimijat
        ));
    }

    // Apumetodi, joka hakee marjatilasto-näkymän tarvitseman marjadatan (tilastoineen).
    private static function haeMarjadata() {
        $marjadata = array();
        $marjat = Marja::all();

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
        return $marjadata;
    }

}
