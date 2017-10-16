<?php

/**
 * Paikka on marjapaikka. Paikka liittyy aina yhteen Marjastajaan ja sisältää
 * paikan marjastajan tunnisteen, paikan sjjainnin ja paikan nimen.
 * 
 * @author mkotola
 */
class Paikka extends BaseModel {

    public $id, $marjastaja_id, $p, $i, $nimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_p', 'validoi_i', 'validoi_p_i_identtisetPaikat');
    }

    public static function haeKaikki() {
        $query = DB::connection()->prepare('SELECT * FROM Paikka');
        $query->execute();
        $rows = $query->fetchAll();
        $paikat = array();

        foreach ($rows as $row) {
            $paikat[] = new Paikka(array(
                'id' => $row['id'],
                'marjastaja_id' => $row['marjastaja_id'],
                'p' => $row['p'],
                'i' => $row['i'],
                'nimi' => $row['nimi']
            ));
        }
        return $paikat;
    }

    public static function hae($id) {
        $query = DB::connection()->prepare('SELECT * FROM Paikka WHERE id=:id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $paikka = new Paikka(array(
                'id' => $row['id'],
                'marjastaja_id' => $row['marjastaja_id'],
                'p' => $row['p'],
                'i' => $row['i'],
                'nimi' => $row['nimi']
            ));

            return $paikka;
        }
        return null;
    }

    public static function haeKayttajanMukaan($id) {
        $query = DB::connection()->prepare('SELECT * FROM Paikka WHERE marjastaja_id=:id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $paikat = array();

        foreach ($rows as $row) {
            $paikat[] = new Paikka(array(
                'id' => $row['id'],
                'marjastaja_id' => $row['marjastaja_id'],
                'p' => $row['p'],
                'i' => $row['i'],
                'nimi' => $row['nimi']
            ));
        }
        return $paikat;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Paikka (marjastaja_id, p, i, nimi) VALUES (:marjastaja_id, :p, :i, :nimi) RETURNING id');
        $query->execute(array(
            'marjastaja_id' => $this->marjastaja_id,
            'p' => $this->p,
            'i' => $this->i,
            'nimi' => $this->nimi
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function tallennaMuuttunut() {
        $query = DB::connection()->prepare('UPDATE Paikka SET p = :p, i = :i, nimi = :nimi WHERE id = :id;');
        $query->execute(array(
            'id' => $this->id,
            'p' => $this->p,
            'i' => $this->i,
            'nimi' => $this->nimi
        ));
    }

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Paikka WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validoi_nimi() {
        $errors = array();
        $newerrors = $this->validate_string_length($this->nimi, 1, 500);
        if (!empty($newerrors)) {
            $errors = array_merge($errors, $newerrors);
        }
        return $errors;
    }

    public function validoi_p() {
        $errors = array();

        if (!is_double($this->p)) {
            $errors[] = "P-koordinaatin täytyy olla desimaaliluku.";
        }

        if (is_double($this->p) && ($this->p < -90 | $this->p > 90)) {
            $errors[] = "P-koordinaatin täytyy olla välillä -90...90.";
        }

        return $errors;
    }

    public function validoi_i() {
        $errors = array();

        if (!is_double($this->i)) {
            $errors[] = "I-koordinaatin täytyy olla desimaaliluku.";
        }

        if (is_double($this->i) && ($this->i < -180 | $this->i > 180)) {
            $errors[] = "P-koordinaatin täytyy olla välillä -180...180.";
        }

        return $errors;
    }

    public function validoi_p_i_identtisetPaikat() {
        $errors = array();

        if (!is_double($this->i) && is_double($this->i)) {
            $paikat = $this->haeKaikki();
            foreach ($paikat as $paikka) {
                if ($paikka->p == $this->p && $paikka->i == $this->i) {
                    $errors[] = "Paikka näillä koordinaateilla on jo olemassa.";
                }
            }

            $errors[] = "I-koordinaatin täytyy olla desimaaliluku.";
        }
        return $errors;
    }

}
