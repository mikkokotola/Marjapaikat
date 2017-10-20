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
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);
            
            $karttasijainti = self::kartanSijainti($paikat);
            
            View::make('paikka/paikat.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja, 'karttasijainti' => $karttasijainti));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikanlisäämisnäkymä.
    public static function lisaaPaikka($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

            $karttasijainti = self::kartanSijainti($paikat);
            
            View::make('paikka/paikat_lisaapaikka.html', array('paikat' => $paikat, 'marjastaja' => $marjastaja, 'karttasijainti' => $karttasijainti));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Uuden paikan tallentaminen.
    public static function tallennusKasittele($marjastaja_id) {
        if (self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;
            $attributes = array(
                'marjastaja_id' => $marjastaja_id,
                'p' => doubleval($params['lat']),
                'i' => doubleval($params['lng']),
                'nimi' => $params['nimi']
            );

            $paikka = new Paikka($attributes);
            $errors = $paikka->errors();

//            Kint::dump($paikka);
//            Kint::dump($errors);

            if (count($errors) == 0) {
                // Lisättävä paikka on validi.
                // Tarkastetaan vielä, onko paikka samoilla koordinaateilla jo olemassa.
                $errors = $paikka->validoi_p_i_identtisetPaikat_uusi();

                if (count($errors) == 0) {
                    $paikka->tallenna();
                    Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat', array('message' => 'Paikka lisätty!'));
                } else {
                    // Kannassa oli jo paikka samoilla koordinaateilla, ei lisätä.
                    $marjastaja = Marjastaja::hae($marjastaja_id);
                    $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

                    View::make('paikka/paikat.html', array('errors' => $errors, 'paikat' => $paikat, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
                }
            } else {
                // Paikassa oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikat = Paikka::haeKayttajanMukaan($marjastaja_id);

                View::make('paikka/paikat.html', array('errors' => $errors, 'paikat' => $paikat, 'marjastaja' => $marjastaja, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Yksittäisen paikan näyttäminen.
    public static function nayta($marjastaja_id, $paikka_id) {

        $marjastaja = Marjastaja::hae($marjastaja_id);
        $paikkatiedot = self::haePaikanData($paikka_id);
        

        if ($marjastaja_id == $paikkatiedot['paikka']->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            View::make('paikka/paikka.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikan poistaminen
    public static function poistaPaikka($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $paikka->poista();

            // Ohjataan käyttäjä paikkojen listaussivulle ilmoituksen kera
            Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat', array('message' => 'Paikka on poistettu'));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikan muokkaamisnäkymä
    public static function muokkaaPaikka($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikkatiedot = self::haePaikanData($paikka_id);
            $attributes = array(
                'marjastaja_id' => $marjastaja_id,
                'p' => $paikka->p,
                'i' => $paikka->i,
                'nimi' => $paikka->nimi
            );
            View::make('paikka/muokkaapaikka.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'muokkaaPaikka' => true,'attributes' => $attributes));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Paikan muokkaamislomakkeen käsittely.
    public static function paikanMuokkausKasittele($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;
            $attributes = array(
                'id' => $paikka_id,
                'marjastaja_id' => $marjastaja_id,
                'p' => doubleval($params['p']),
                'i' => doubleval($params['i']),
                'nimi' => $params['nimi']
            );

            // Parsitaan stringeistä p ja i doublet.
            $muokattuPaikka = new Paikka($attributes);
            $errors = $muokattuPaikka->errors();

            //Kint::dump($paikka);

            if (count($errors) == 0) {
                // Muutettava paikka on validi.
                // Tarkastetaan vielä, että kannassa ei ole paikkoja samoilla koordinaateilla (paitsi jos se on juuri muokattava paikka).
                $errors = $muokattuPaikka->validoi_p_i_identtisetPaikat_muokattava($paikka);

                if (count($errors) == 0) {
                    $muokattuPaikka->tallennaMuuttunut();
                    Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Paikan tiedot tallennettu'));
                } else {
                    // Kannassa oli jo toinen paikka samoilla koordinaateilla, ei muuteta.
                    $marjastaja = Marjastaja::hae($marjastaja_id);
                    $muokattuPaikka = Paikka::hae($paikka_id);

                    View::make('paikka/muokkaapaikka.html', array('paikka' => $muokattuPaikka, 'marjastaja' => $marjastaja, 'errors' => $errors, 'attributes' => $attributes));
                }
            } else {
                // Paikassa oli vikaa, ei muuteta.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $muokattuPaikka = Paikka::hae($paikka_id);

                View::make('paikka/muokkaapaikka.html', array('paikka' => $muokattuPaikka, 'marjastaja' => $marjastaja, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }

//        $paikka = Paikka::find($paikka_id);
//        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
//            View::make('paikka/muokkaapaikkaa.html', array('paikka' => $paikka, 'marjastaja' => $marjastaja));
//        } else {
//            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
//        }
    }

    // Käynnin lisäämisnäkymä.
    public static function lisaaKaynti($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikkatiedot = self::haePaikanData($paikka_id);

            View::make('paikka/lisaakaynti.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'lisaaKaynti' => true));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Käynnin lisäämislomakkeen käsittely.
    public static function tallennaUusiKaynti($marjastaja_id, $paikka_id) {
        $paikka = Paikka::hae($paikka_id);
        if ($marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;

            $pvm = $params['pvm'];
            $kellonaika = $params['kellonaika'];
            // Yhdistetään stringeistä pvm ja kellonaika timestamp.
            $aikaleima = $pvm . " " . $kellonaika;
            $attributes = array(
                'paikka_id' => $paikka_id,
                'aika' => $aikaleima
            );

            $kaynti = new Kaynti($attributes);
            $errors = $kaynti->errors();

            if (count($errors) == 0) {
                // Lisättävä käynti on validi.
                $kaynti->tallenna();
                Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Uusi käynti lisätty.'));
            } else {
                // Käynnissä oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikkatiedot = self::haePaikanData($paikka_id);

                $attributes = array(
                    'pvm' => $pvm,
                    'kellonaika' => $kellonaika
                );
                
//                Kint::dump($paikkatiedot);
//                Kint::dump($attributes);
                View::make('paikka/lisaakaynti.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Käynnin poistaminen
    public static function poistaKaynti($marjastaja_id, $paikka_id, $kaynti_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        if ($paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $kaynti->poista();

            //$marjastaja = Marjastaja::hae($marjastaja_id);
            //$paikkatiedot = self::haePaikanData($paikka_id);
            // Ohjataan käyttäjä kyseisen paikan sivulle ilmoituksen kera
            Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Käynti on poistettu'));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Käynnin muokkaamisnäkymä
    public static function muokkaaKaynti($marjastaja_id, $paikka_id, $kaynti_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        if ($paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {

            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikkatiedot = self::haePaikanData($paikka_id);
            $muokattavanKaynninNro = 0;
            $pvm = '2017-01-01';
            $kellonaika = '00:00';

            foreach ($paikkatiedot['kaynnit'] as $key => $muokattavaKaynti) {
                if ($muokattavaKaynti->id == $kaynti->id) {
                    $muokattavanKaynninNro = $key+1;
                    $pvm = $paikkatiedot['kaynnitJaSaaliit'][$key]['pvm'];
                    $kellonaika = $paikkatiedot['kaynnitJaSaaliit'][$key]['kellonaika'];
                }
            }

            $attributes = array(
                'pvm' => $pvm,
                'kellonaika' => $kellonaika
            );

//            Kint::dump($kaynti);
//            Kint::dump($paikkatiedot);
//            Kint::dump($muokattavanKaynninNro);
//            Kint::dump($attributes);
            View::make('paikka/muokkaakaynti.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'muokattavanKaynninNro' => $muokattavanKaynninNro, 'attributes' => $attributes));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Käynnin muokkaamislomakkeen käsittely.
    public static function kaynninMuokkausKasittele($marjastaja_id, $paikka_id, $kaynti_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        if ($paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;

            $pvm = $params['pvm'];
            $kellonaika = $params['kellonaika'];
            // Yhdistetään stringeistä pvm ja kellonaika timestamp.
            $aikaleima = $pvm . " " . $kellonaika;
            $attributes = array(
                'id' => $kaynti_id,
                'paikka_id' => $paikka_id,
                'aika' => $aikaleima
            );

            $kaynti = new Kaynti($attributes);
            $errors = $kaynti->errors();

            if (count($errors) == 0) {
                // Muutettava käynti on validi.
                $kaynti->tallennaMuuttunut();
                Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Käynnin tiedot tallennettu'));
            } else {
                // Käynnissä oli vikaa, ei muuteta.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikkatiedot = self::haePaikanData($paikka_id);

                $muokattavanKaynninNro = 0;

                foreach ($paikkatiedot['kaynnit'] as $key => $muokattavaKaynti) {
                    if ($muokattavaKaynti->id == $kaynti->id) {
                        $muokattavanKaynninNro = $key;
                    }
                }

                $attributes = array(
                    'pvm' => $pvm,
                    'kellonaika' => $kellonaika
                );

                View::make('paikka/muokkaakaynti.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'muokattavanKaynninNro' => $muokattavanKaynninNro, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Marjasaaliin lisäämisnäkymä.
    public static function lisaaSaalis($marjastaja_id, $paikka_id, $kaynti_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        if ($paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikkatiedot = self::haePaikanData($paikka_id);
            $kaikkiMarjat = Marja::haeKaikki();
            View::make('paikka/lisaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'kayntiJohonLisataan' => $kaynti, 'kaikkiMarjat' => $kaikkiMarjat));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Marjasaaliin lisäämislomakkeen käsittely.
    public static function tallennaUusiSaalis($marjastaja_id, $paikka_id, $kaynti_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        if ($paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;

            $attributes = array(
                'marja_id' => $params['marja_id'],
                'kaynti_id' => $kaynti_id,
                'maara' => doubleval($params['maara']),
                'kuvaus' => $params['kuvaus']
            );

            $marjasaalis = new Marjasaalis($attributes);

            $errors = $marjasaalis->errors();

            if (count($errors) == 0) {
                // Lisättävä marjasaalis on validi.
                // Tarkastetaan vielä, ettei kannassa ole jo marjasaaliita samalle käynnille ja marjalle.
                $errors = $marjasaalis->validoi_identiteetti_uusi();

                if (count($errors) == 0) {
                    // Ei ollut samaan käyntiin ja marjaan liittyvää saalista. Lisätään kantaan.
                    $marjasaalis->tallenna();
                    Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Uusi marjasaalis lisätty.'));
                } else {
                    // Kannassa oli jos samaan käyntiin ja marjaan liittyvä saalis, ei lisätä.
                    $marjastaja = Marjastaja::hae($marjastaja_id);
                    $paikkatiedot = self::haePaikanData($paikka_id);
                    $kaikkiMarjat = Marja::haeKaikki();

                    View::make('paikka/lisaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'kayntiJohonLisataan' => $kaynti, 'kaikkiMarjat' => $kaikkiMarjat, 'errors' => $errors, 'attributes' => $attributes));
                }
            } else {
                // Marjasaaliissa oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikkatiedot = self::haePaikanData($paikka_id);
                $kaikkiMarjat = Marja::haeKaikki();

                View::make('paikka/lisaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'kayntiJohonLisataan' => $kaynti, 'kaikkiMarjat' => $kaikkiMarjat, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Marjasaaliin muokkausnäkymä.
    public static function muokkaaSaalis($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        $saalis = Marjasaalis::haeMarjanJaKaynninMukaan($marja_id, $kaynti_id);
        if ($kaynti_id == $saalis->kaynti_id && $paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $marjastaja = Marjastaja::hae($marjastaja_id);
            $paikkatiedot = self::haePaikanData($paikka_id);
            $kaikkiMarjat = Marja::haeKaikki();

            $attributes = array(
                'marja_id' => $marja_id,
                'kaynti_id' => $kaynti_id,
                'maara' => $saalis->maara,
                'kuvaus' => $saalis->kuvaus
            );

            View::make('paikka/muokkaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'saalisJotaMuokataan' => $saalis, 'kaikkiMarjat' => $kaikkiMarjat, 'attributes' => $attributes));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Marjasaaliin muokkauslomakkeen käsittely.
    public static function saaliinMuokkausKasittele($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        $saalis = Marjasaalis::haeMarjanJaKaynninMukaan($marja_id, $kaynti_id);
        if ($kaynti_id == $saalis->kaynti_id && $paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $params = $_POST;

            $attributes = array(
                'marja_id' => $params['marja_id'],
                'kaynti_id' => $kaynti_id,
                'maara' => doubleval($params['maara']),
                'kuvaus' => $params['kuvaus']
            );

            $marjasaalis = new Marjasaalis($attributes);

            $errors = $marjasaalis->errors();

            if (count($errors) == 0) {
                // Muutettava marjasaalis on validi.
                // Tarkastetaan vielä, ettei kannassa ole muita samaan käyntiin ja marjaan liittyvää saalista.

                $errors = $marjasaalis->validoi_identiteetti_muokattava($saalis);

                if (count($errors) == 0) {
                    // Poistetaan kannasta vanha kyseisiin käynteihin ja marjaan liittyvä saalis.
                    $saalis->poista();

                    // Tallennetaan kantaan uusi, muokattu marjasaalis (joka saattaa liittyä samaan marjaan tai toiseen marjaan).
                    $marjasaalis->tallenna();

                    Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Muokattu marjamerkintä tallennettu.'));
                } else {
                    // Kannassa oli toinen samaan käyntiin ja marjaan liittyvä saalis. Ei lisätä.
                    $marjastaja = Marjastaja::hae($marjastaja_id);
                    $paikkatiedot = self::haePaikanData($paikka_id);
                    $kaikkiMarjat = Marja::haeKaikki();

                    View::make('paikka/muokkaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'saalisJotaMuokataan' => $saalis, 'kaikkiMarjat' => $kaikkiMarjat, 'errors' => $errors, 'attributes' => $attributes));
                }
            } else {
                // Marjasaaliissa oli vikaa, ei lisätä.
                $marjastaja = Marjastaja::hae($marjastaja_id);
                $paikkatiedot = self::haePaikanData($paikka_id);
                $kaikkiMarjat = Marja::haeKaikki();

                View::make('paikka/muokkaasaalis.html', array('paikkatiedot' => $paikkatiedot, 'marjastaja' => $marjastaja, 'saalisJotaMuokataan' => $saalis, 'kaikkiMarjat' => $kaikkiMarjat, 'errors' => $errors, 'attributes' => $attributes));
            }
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Marjasaaliin poistaminen
    public static function poistaSaalis($marjastaja_id, $paikka_id, $kaynti_id, $marja_id) {
        $paikka = Paikka::hae($paikka_id);
        $kaynti = Kaynti::hae($kaynti_id);
        $saalis = Marjasaalis::haeMarjanJaKaynninMukaan($marja_id, $kaynti_id);
        if ($kaynti_id == $saalis->kaynti_id && $paikka_id == $kaynti->paikka_id && $marjastaja_id == $paikka->marjastaja_id && self::check_logged_in_user($marjastaja_id)) {
            $saalis->poista();

            Redirect::to('/marjastaja/' . $marjastaja_id . '/paikat/' . $paikka_id, array('message' => 'Marjasaalis on poistettu'));
        } else {
            View::make('marjastaja/kirjaudu.html', array('error' => 'Kirjaudu sisään'));
        }
    }

    // Apumetodi yksittäisen paikan näkymän muodostamiseen.
    private static function haePaikanData($paikka_id) {

        $paikka = Paikka::hae($paikka_id);
        $kaynnit = Kaynti::haePaikanMukaan($paikka_id);

        $format = 'Y-m-d H:i:s';



        $kaynnitJaSaaliit = array();
        foreach ($kaynnit as $kaynti) {
            $pvmKellonaika = $kaynti->aika;
            $pvm = substr($pvmKellonaika, 0, 10);
            $kellonaika = substr($pvmKellonaika, 11, 5);

            //$dateTime = DateTime::createFromFormat($format, $pvmKellonaika);
//            $pvm = DateTime::createFromFormat('Y-m-d', $pvmKellonaika);
//            $kellonaika = DateTime::createFromFormat('H:i', $pvmKellonaika);
//            $pvm = $dateTime->format('Y-m-d'); 
//            $kellonaika = $dateTime->format('H:i');
            $marjasaaliit = Marjasaalis::haeKaynninMukaan($kaynti->id);
            $marjat = array();
            foreach ($marjasaaliit as $marjasaalis) {
                $marjannimi = Marja:: hae($marjasaalis->marja_id)->nimi;
                array_push($marjat, $marjannimi);
            }
            $kayntiJaMarjasaaliit = array(
                'id' => $kaynti->id,
                'pvm' => $pvm,
                'kellonaika' => $kellonaika,
                'marjat' => $marjat,
                'marjasaaliit' => $marjasaaliit
            );
            $kaynnitJaSaaliit[] = $kayntiJaMarjasaaliit;
        }

        $paikkatiedot = array(
            'paikka' => $paikka,
            'kaynnit' => $kaynnit,
            'kaynnitJaSaaliit' => $kaynnitJaSaaliit
        );

        return $paikkatiedot;
    }
    
    // Määritellään kaikkien paikkojen näkymän rajat paikat-näkymää varten.
    private static function kartanSijainti($paikat) {
        
            $pohjoisinP = -90;
            $etelaisinP = 90;
            $itaisinI = -180;
            $lantisinI = 180;
            foreach ($paikat as $paikka) {
                if ($paikka->p > $pohjoisinP) {
                    $pohjoisinP = $paikka->p;
                }
                if ($paikka->p < $etelaisinP) {
                    $etelaisinP = $paikka->p;
                }
                
                if ($paikka->i > $itaisinI) {
                    $itaisinI = $paikka->i;
                }
                if ($paikka->i < $lantisinI) {
                    $lantisinI = $paikka->i;
                }
            }
            $leveys = $itaisinI - $lantisinI;
            $korkeus = $pohjoisinP - $etelaisinP;
            $leveyskeskus = $itaisinI - 0.5 * $leveys;
            $korkeuskeskus = $pohjoisinP - 0.5 * $korkeus;
            $zoom = 5;
            if ($korkeus < 3 && $leveys < 10) {
                $zoom = 7;
            }
            if ($korkeus < 0.3 && $leveys < 1.3) {
                $zoom = 9;
            }
            if (($korkeus < 0.3 && $leveys < 0.2) || count($paikat) == 1 ) {
                $zoom = 11;
            }
            $karttasijainti = array(
                'korkeuskeskus' => $korkeuskeskus,
                'leveyskeskus' => $leveyskeskus,
                'zoom' => $zoom
            );
            return $karttasijainti;
    }

}
