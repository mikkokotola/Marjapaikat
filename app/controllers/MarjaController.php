<?php

/**
 * Marjojen controller.
 *
 * @author mkotola
 */
class MarjaController extends BaseController {

    // Marjalistausnäkymä.
    public static function index() {
        $marjadata = self::haeMarjadata();

        View::make('marja/marjat.html', array('marjadata' => $marjadata));
    }

    // Marjan lisäysnäkymä
    public static function lisaaMarja() {
        // Kuka vain kirjautunut käyttäjä voi tallentaa uusia marjoja.
        self::check_logged_in();
        $marjadata = self::haeMarjadata();
        View::make('marja/marjat_lisaamarja.html', array('marjadata' => $marjadata));
    }

    // Marjan tallentaminen (lomakkeen käsittely)
    public static function tallennaMarja() {
        // Kuka vain kirjautunut käyttäjä voi tallentaa uusia marjoja.
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'nimi' => $params['nimi']
        );
        $marja = new Marja($attributes);
        $errors = $marja->errors();


        if (count($errors) == 0) {
            // Lisättävä marja on validi.
            $marja->save();
            Redirect::to('/marjat', array('message' => 'Marja lisätty!'));
        } else {
            // Marjassa oli vikaa, ei lisätä.
            $marjadata = self::haeMarjadata();
            View::make('marja/marjat_lisaamarja.html', array('errors' => $errors, 'marjadata' => $marjadata, 'attributes' => $attributes));
        }
    }

    // Marjan muokkaamisnäkymä
    public static function rename($marja_id) {
        // Tätä täytyy vielä tiukentaa: oikeudet vain ylläpitokäyttäjälle.
        self::check_logged_in();
        $marjatiedot = self::haeMarjandata($marja_id);
        View::make('marja/muokkaamarjaa.html', array('marjatiedot' => $marjatiedot));
    }

    // Marjan uudelleennimeäminen (lomakkeen käsittely)
    public static function renameMarja() {
        // Tätä täytyy vielä tiukentaa: oikeudet vain ylläpitokäyttäjälle.
        self::check_logged_in();
        $params = $_POST;

        $marja_id = $params['marja_id'];
        $attributes = array(
            'nimi' => $params['nimi'],
        );

        // Haetaan Marja, jota käyttäjä muokkasi ja uudelleennimetään.
        $marja = Marja::find($marja_id);
        $marja->rename($attributes['nimi']);
        $errors = $marja->errors();

        if (count($errors) > 0) {
            $marjatiedot = self::haeMarjandata($marja_id);
            View::make('marja/muokkaamarjaa.html', array('marjatiedot' => $marjatiedot, 'errors' => $errors, 'attributes' => $attributes));
        } else {
            // Kutsutaan Marjan saveChangedName-metodia, joka päivittää nimen tietokannassa
            $marja->saveChangedName();

            Redirect::to('/marja/' . $marja->id, array('message' => 'Marjan nimi muutettu'));
        }
    }

    // Marjan poistaminen
    public static function delete($id) {
        // Tätä täytyy vielä tiukentaa: oikeudet vain ylläpitokäyttäjälle.
        self::check_logged_in();
        $marja = Marja::find($id);
        // Kutsutaan Marja-luokan metodia delete, joka poistaa marjan sen id:llä
        $marja->delete();

        // Ohjataan käyttäjä marjojen listaussivulle ilmoituksen kera
        Redirect::to('/', array('message' => 'Marja on poistettu!'));
    }

    public static function show($marja_id) {
        $marjatiedot = self::haeMarjandata($marja_id);

        View::make('marja/marja.html', array('marjatiedot' => $marjatiedot));
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

    // Apumetodi, joka hakee yksittäisen marjan näkymän tarvitseman marjadatan.
    private static function haeMarjandata($marja_id) {
        $marja = Marja::find($marja_id);
        $suosikkikayttajat = Marjastaja::findBySuosikkimarja($marja_id);
        $poimineetKayttajatKuluvaVuosi = Marjastaja::findByMarjaAndVuosi($marja_id, date('Y'));
        $poimineetKayttajatLkmKuluvaVuosi = count($poimineetKayttajatKuluvaVuosi);
        $poimineetKayttajatKokoHistoria = Marjastaja::findByMarja($marja_id);
        $poimineetKayttajatLkmKokoHistoria = count($poimineetKayttajatKokoHistoria);
        $marjanMaaraKokoHistoria = Marjasaalis::maaraKokohistoriaByMarja($marja_id);
        $marjanMaaraKuluvaVuosi = Marjasaalis::maaraByMarjaAndVuosi($marja_id, date('Y'));
        $marjanTopPoimijat = Marjastaja::karkipoimijatByMarjaAndVuosi($marja_id, date('Y'));

        $marjatiedot = array(
            'marja' => $marja,
            'suosikkikayttajat' => $suosikkikayttajat,
            'poimineetKayttajatLkmKuluvaVuosi' => $poimineetKayttajatLkmKuluvaVuosi,
            'poimineetKayttajatLkmKokoHistoria' => $poimineetKayttajatLkmKokoHistoria,
            'marjanMaaraKokoHistoria' => $marjanMaaraKokoHistoria,
            'marjanMaaraKuluvaVuosi' => $marjanMaaraKuluvaVuosi,
            'marjanTopPoimijat' => $marjanTopPoimijat
        );
        
        return $marjatiedot;
    }

}
