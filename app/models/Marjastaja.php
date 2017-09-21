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

}
