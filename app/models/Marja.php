<?php

/**
 * Marja on poimittavissa oleva marja. 
 *
 * @author mkotola
 */
class Marja extends BaseModel {

    public $id, $nimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi');
    }

    public static function haeKaikki() {
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

    public static function hae($id) {
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

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Marja (nimi) VALUES (:nimi) RETURNING id');
        $query->execute(array('nimi' => $this->nimi));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Marja WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }
    
    public function muutaNimea($nimi) {
        $this->nimi = $nimi;
    }
    
    public function tallennaNimenMuutos() {
        $query = DB::connection()->prepare('UPDATE Marja SET nimi = :nimi WHERE id = :id;');
        $query->execute(array('nimi' => $this->nimi, 'id' => $this->id));
    }
    
    public function validoi_nimi() {
        $errors = array();
        $newerrors = $this->validate_string_length($this->nimi, 2, 500);
        if (!empty($newerrors)) {
            $errors = array_merge($errors, $newerrors);
        }

        $marjat = $this->haeKaikki();
        foreach ($marjat as $marja) {
            if ($marja->nimi === $this->nimi) {
                $errors[] = "Tämänniminen marja on jo olemassa.";
            }
        }

        return $errors;
    }

}
