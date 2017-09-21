<?php

/**
 * Suosikkimarja kertoo marjastajien asettamat suosikkimarjat.
 *
 * @author mkotola
 */
class Suosikkimarja extends BaseModel{
    public $marjastaja_id, $marja_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Suosikkimarja;');
        $query->execute();
        $rows = $query->fetchAll();
        $suosikkimarjat = array();

        foreach ($rows as $row) {
            $suosikkimarjat[] = new Suosikkimarja(array(
                'marjastaja_id' => $row['marjastaja_id'],
                'marja_id' => $row['marja_id']
            ));
        }
        return $suosikkimarjat;
    }

    public static function findByMarja($marja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Suosikkimarja WHERE marja_id=:id;');
        $query->execute(array('id' => $marja_id));
        $rows = $query->fetchAll();
        $suosikkimarjat = array();

        foreach ($rows as $row) {
            $suosikkimarjat[] = new Suosikkimarja(array(
                'marjastaja_id' => $row['marjastaja_id'],
                'marja_id' => $row['marja_id']
            ));
        }
        return $suosikkimarjat;
        
    }
    
    // Ei taideta tarvita tässä lainkaan.
    public static function findByMarjastaja($marjastaja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Suosikkimarja WHERE marjastaja_id=:id;');
        $query->execute(array('id' => $marjastaja_id));
        $rows = $query->fetchAll();
        $suosikkimarjat = array();

        foreach ($rows as $row) {
            $suosikkimarjat[] = new Suosikkimarja(array(
                'marjastaja_id' => $row['marjastaja_id'],
                'marja_id' => $row['marja_id']
            ));
        }
        return $suosikkimarjat;
        
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (:marjastaja_id, :marja_id);');
        $query->execute(array(
            'marjastaja_id' => $this->marjastaja_id,
            'marja_id' => $this->marja_id
        ));
        
    }

}
