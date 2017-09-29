<?php

/**
 * Marjastaja on järjestelmän käyttäjän malli. Sisältää henkilö- ja käyttäjätiedot.
 *
 * @author mkotola
 */
class Marjastaja extends BaseModel {

    public $id, $kayttajatunnus, $salasana, $etunimi, $sukunimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function authenticate($kayttajatunnus, $salasana) {

        $query = DB::connection()->prepare('SELECT * FROM Marjastaja WHERE kayttajatunnus = :kayttajatunnus AND salasana = :salasana LIMIT 1');
        $query->execute(array('kayttajatunnus' => $kayttajatunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            // Käyttäjä löytyi, palautetaan löytynyt käyttäjä oliona
            $marjastaja = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
            return $marjastaja;
        } else {
            // Käyttäjää ei löytynyt, palautetaan null
            return null;
        }
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Marjastaja');
        $query->execute();
        $rows = $query->fetchAll();
        $marjastajat = array();

        foreach ($rows as $row) {
            $marjastajat[] = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        }
        return $marjastajat;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjastaja WHERE id=:id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $marjastaja = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));

            return $marjastaja;
        }
        return null;
    }

    public static function findBySuosikkimarja($marja_id) {
        // Kysely, jossa haetaan tietyn marjan suokiksi lisänneet käyttäjät Suosikkimarja- ja Marja-taulujen perusteella.
        $query = DB::connection()->prepare('SELECT mj.id, mj.kayttajatunnus, mj.salasana, mj.etunimi, mj.sukunimi FROM Marjastaja mj, Suosikkimarja sm, Marja m WHERE m.id = :marja_id AND m.id = sm.marja_id AND sm.marjastaja_id = mj.id');
        $query->execute(array('marja_id' => $marja_id));
        $rows = $query->fetchAll();
        $marjastajat = array();

        foreach ($rows as $row) {
            $marjastajat[] = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        }
        return $marjastajat;
    }

    public static function findByMarja($marja_id) {
        // Kysely, jossa haetaan tiettyä marjaa poimineet uniikit käyttäjät.
        $query = DB::connection()->prepare('SELECT DISTINCT mj.id, mj.kayttajatunnus, mj.salasana, mj.etunimi, mj.sukunimi FROM Marjastaja mj, Paikka p, Kaynti k, Marjasaalis ms '
                . 'WHERE mj.id = p.marjastaja_id AND p.id = k.paikka_id AND k.id = ms.kaynti_id AND ms.marja_id = :marja_id');
        $query->execute(array('marja_id' => $marja_id));
        $rows = $query->fetchAll();
        $marjastajat = array();

        foreach ($rows as $row) {
            $marjastajat[] = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        }
        return $marjastajat;
    }

    public static function findByMarjaAndVuosi($marja_id, $vuosi) {
        // Kysely, jossa haetaan tiettyä marjaa tiettynä vuonna poimineet uniikit käyttäjät.
        $query = DB::connection()->prepare('SELECT DISTINCT mj.id, mj.kayttajatunnus, mj.salasana, mj.etunimi, mj.sukunimi FROM Marjastaja mj, Paikka p, Kaynti k, Marjasaalis ms '
                . 'WHERE mj.id = p.marjastaja_id AND p.id = k.paikka_id AND k.id = ms.kaynti_id AND ms.marja_id = :marja_id AND extract(year from k.aika)=:vuosi');
        $query->execute(array('marja_id' => $marja_id, 'vuosi' => $vuosi));
        $rows = $query->fetchAll();
        $marjastajat = array();

        foreach ($rows as $row) {
            $marjastajat[] = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        }
        return $marjastajat;
    }

    public static function karkipoimijatByMarjaAndVuosi($marja_id, $vuosi) {
        // Kysely, jossa haetaan tiettyä marjaa tiettynä vuonna poimineet uniikit käyttäjät saaliin määrän mukaisessa järjestyksessä. Palauttaa taulukon, jossa etunimi, sukunimi, poimittu määrä 
        // annettuna vuonna ja poimittu määrä koko historian aikana.
        $query = DB::connection()->prepare('SELECT mj.etunimi AS etun, mj.sukunimi AS sukun, SUM(ms.maara) AS saalis FROM Marjastaja mj, Paikka p, Kaynti k, Marjasaalis ms '
                . 'WHERE mj.id = p.marjastaja_id AND p.id = k.paikka_id AND k.id = ms.kaynti_id AND ms.marja_id = :marja_id AND extract(year from k.aika)=:vuosi '
                . 'GROUP BY mj.id ORDER BY SUM(ms.maara) DESC');
        $query->execute(array('marja_id' => $marja_id, 'vuosi' => $vuosi));
        $rows = $query->fetchAll();
        $karkipoimijat = array();

        foreach ($rows as $row) {
            $karkipoimijat[] = (array(
                'etunimi' => $row['etun'],
                'sukunimi' => $row['sukun'],
                'saalis' => $row['saalis']
            ));
        }
        return $karkipoimijat;
    }

}
