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
            $marja->tallenna();
            Redirect::to('/marjat', array('message' => 'Marja lisätty!'));
        } else {
            // Marjassa oli vikaa, ei lisätä.
            $marjadata = self::haeMarjadata();
            View::make('marja/marjat_lisaamarja.html', array('errors' => $errors, 'marjadata' => $marjadata, 'attributes' => $attributes));
        }
    }

    // Marjan muokkaamisnäkymä
    public static function muokkausNakyma($marja_id) {
        self::check_logged_in();
        $marjatiedot = self::haeMarjandata($marja_id);
        $marjastaja = self::get_user_logged_in();

        $onSuosikki = false;
        foreach ($marjatiedot['suosikkikayttajat'] as $suosikkikayttaja) {
            if ($marjastaja->id == $suosikkikayttaja->id) {
                $onSuosikki = true;
            }
        }

        $attributes = array(
            'nimi' => $marjatiedot['marja']->nimi
        );

        View::make('marja/muokkaamarjaa.html', array('marjatiedot' => $marjatiedot, 'marjastaja' => $marjastaja, 'onSuosikki' => $onSuosikki, 'attributes' => $attributes));
    }

    // Marjan uudelleennimeäminen (lomakkeen käsittely)
    public static function muutaNimeaKasittele() {
        // Marjojen nimien muokkausoikeudet on vain ylläpitokäyttäjällä. Ylläpitokäyttäjät on määritelty toistaiseksi manuaalisesti.
        $marjastaja = self::get_user_logged_in();
        if ($marjastaja->id == 3) {

            $params = $_POST;

            $marja_id = $params['marja_id'];
            $attributes = array(
                'nimi' => $params['nimi'],
            );

            // Haetaan Marja, jota käyttäjä muokkasi ja uudelleennimetään.
            $marja = Marja::hae($marja_id);
            $marja->muutaNimea($attributes['nimi']);
            $errors = $marja->errors();

            if (count($errors) > 0) {
                $marjatiedot = self::haeMarjandata($marja_id);
                View::make('marja/muokkaamarjaa.html', array('marjatiedot' => $marjatiedot, 'errors' => $errors, 'attributes' => $attributes));
            } else {
                // Kutsutaan Marjan saveChangedName-metodia, joka päivittää nimen tietokannassa
                $marja->tallennaNimenMuutos();

                Redirect::to('/marja/' . $marja->id, array('message' => 'Marjan nimi muutettu'));
            }
        } else {
            Redirect::to('/marja/' . $marja->id, array('message' => 'Marjan nimeä voi muokata vain ylläpitokäyttäjä.'));
        }
    }

    // Marjan poistaminen
    public static function poista($id) {
        // Marjojen poistamisoikeudet on vain ylläpitokäyttäjällä. Ylläpitokäyttäjät on määritelty toistaiseksi manuaalisesti.
        $marjastaja = self::get_user_logged_in();
        if ($marjastaja->id == 3) {
            $marja = Marja::hae($id);
            // Kutsutaan Marja-luokan metodia delete, joka poistaa marjan sen id:llä
            if ($marja) {
                $marja->poista();

                // Ohjataan käyttäjä marjojen listaussivulle ilmoituksen kera
                Redirect::to('/', array('message' => 'Marja on poistettu.'));
            } else {
                Redirect::to('/', array('message' => 'Marjaa ei löytynyt.'));
            }
        } else {
            Redirect::to('/marja/' . $marja->id, array('message' => 'Marjan voi poistaa vain ylläpitokäyttäjä.'));
        }
    }

    public static function nayta($marja_id) {
        $marjatiedot = self::haeMarjandata($marja_id);
        $marjastaja = self::get_user_logged_in();

        if ($marjastaja) {
            $onSuosikki = false;

            foreach ($marjatiedot['suosikkikayttajat'] as $suosikkikayttaja) {
                if ($suosikkikayttaja && $marjastaja->id == $suosikkikayttaja->id) {
                    $onSuosikki = true;
                }
            }

            View::make('marja/marja.html', array('marjatiedot' => $marjatiedot, 'marjastaja' => $marjastaja, 'onSuosikki' => $onSuosikki));
        } else {
            View::make('marja/marja.html', array('marjatiedot' => $marjatiedot));
        }
    }

    // Marjan asettaminen suosikiksi
    public static function asetaSuosikiksi($marja_id, $marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $attributes = array(
                'marja_id' => $marja_id,
                'marjastaja_id' => $marjastaja_id
            );

            $suosikkimarja = new Suosikkimarja($attributes);
            $errors = $suosikkimarja->errors();

            if (count($errors) == 0) {
                $suosikkimarja->tallenna();
                Redirect::to('/marja/' . $marja_id, array('message' => 'Marja lisätty suosikiksi.'));
            } else {
                Redirect::to('/marja/' . $marja_id);
            }
        } else {
            Redirect::to('/marja/' . $marja_id);
        }
    }

    // Marjan poistaminen suosikeista
    public static function poistaSuosikeista($marja_id, $marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $suosikkimarja = Suosikkimarja::haeMarjanJaMarjastajanMukaan($marja_id, $marjastaja_id);

            if ($suosikkimarja) {
                $suosikkimarja->poista();

                Redirect::to('/marja/' . $marja_id, array('message' => 'Marja on poistettu suosikeista.'));
            } else {
                Redirect::to('/marja/' . $marja_id, array('message' => 'Suosikkimarjaa ei löytynyt.'));
            }
        } else {
            Redirect::to('/marja/' . $marja_id);
        }
    }

    // Apumetodi, joka hakee marjatilasto-näkymän tarvitseman marjadatan (tilastoineen).
    private static function haeMarjadata() {
        $marjadata = array();
        $marjat = Marja::haeKaikki();

        $n = 0;
        foreach ($marjat as $marja) {
            $marjatiedot = array();
            $marjatiedot[] = $marja->id;
            $marjatiedot[] = $marja->nimi;
            $marjatiedot[] = Marjasaalis::maaraMarjanJaVuodenMukaan($marja->id, date('Y'));
            $marjatiedot[] = Marjasaalis::maaraKokoHistoriaMarjanMukaan($marja->id);
            $marjatiedot[] = count(Marjastaja::haeMarjanJaVuodenMukaan($marja->id, date('Y')));
            $marjatiedot[] = count(Marjastaja::haeMarjanMukaan($marja->id));
            $marjadata[$n] = $marjatiedot;
            $n++;
        }
        return $marjadata;
    }

    // Apumetodi, joka hakee yksittäisen marjan näkymän tarvitseman marjadatan.
    private static function haeMarjandata($marja_id) {
        $marja = Marja::hae($marja_id);
        $suosikkikayttajat = Marjastaja::haeSuosikkimarjanMukaan($marja_id);
        $poimineetKayttajatKuluvaVuosi = Marjastaja::haeMarjanJaVuodenMukaan($marja_id, date('Y'));
        $poimineetKayttajatLkmKuluvaVuosi = count($poimineetKayttajatKuluvaVuosi);
        $poimineetKayttajatKokoHistoria = Marjastaja::haeMarjanMukaan($marja_id);
        $poimineetKayttajatLkmKokoHistoria = count($poimineetKayttajatKokoHistoria);
        $marjanMaaraKokoHistoria = Marjasaalis::maaraKokoHistoriaMarjanMukaan($marja_id);
        $marjanMaaraKuluvaVuosi = Marjasaalis::maaraMarjanJaVuodenMukaan($marja_id, date('Y'));
        $marjanTopPoimijat = Marjastaja::karkipoimijatMarjanJaVuodenMukaan($marja_id, date('Y'));

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
