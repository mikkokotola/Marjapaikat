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
        $this->validators = array('validoi_identtiset');
    }

    public static function haeKaikki() {
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

    public static function haeMarjanMukaan($marja_id) {
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
    
    public static function haeMarjastajanMukaan($marjastaja_id) {
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

    public static function haeMarjanJaMarjastajanMukaan($marja_id, $marjastaja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Suosikkimarja WHERE marjastaja_id=:marjastaja_id AND marja_id=:marja_id LIMIT 1;');
        $query->execute(array('marjastaja_id' => $marjastaja_id, 'marja_id' => $marja_id));
        $row = $query->fetch();

        if ($row) {
            $suosikkimarja = new Suosikkimarja(array(
                'marjastaja_id' => $row['marjastaja_id'],
                'marja_id' => $row['marja_id']
            ));

            return $suosikkimarja;
        }
        return null;
        
    }
    
    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (:marjastaja_id, :marja_id);');
        $query->execute(array(
            'marjastaja_id' => $this->marjastaja_id,
            'marja_id' => $this->marja_id
        ));
        
    }
    
    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Suosikkimarja WHERE marjastaja_id=:marjastaja_id AND marja_id=:marja_id');
        $query->execute(array('marjastaja_id' => $this->marjastaja_id, 'marja_id' => $this->marja_id));
    }
    
    public function validoi_identtiset() {
        $errors = array();
        
        $suosikkimarjat = self::haeKaikki();
        
        foreach ($suosikkimarjat as $vanhasuosikki) {
            if ($vanhasuosikki->marja_id == $this->marja_id && $vanhasuosikki->marjastaja_id == $this->marjastaja_id) {
                $errors[] = "Marja on jo suosikkimarjasi.";
            }
        }
    }

}
