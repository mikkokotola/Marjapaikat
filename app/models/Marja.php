<?php

/**
 * Marja on poimittavissa oleva marja. Marja voi liittyä useisiin marjastajiin
 * (liitostaulu Marjastajamarja) ja useisiin käynteihin (liitostaulu Marjakaynti).
 *
 * @author mkotola
 */
class Marja extends BaseModel {

    public $id, $nimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Marja;');
        $query->execute();
        $rows = $query->fetchAll();
        $marjat = array();

        foreach ($rows as $row) {
            $marjat[] = new Marja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
            ));
        }
        return $marjat;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Marja WHERE id=:id LIMIT 1;');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $marja = new Marja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
            ));

            return $marja;
        }
        return null;
    }

    public function save() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Marja (nimi) VALUES (:nimi) RETURNING id');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('nimi' => $this->nimi));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['id'];
    }

}
