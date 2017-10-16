<?php

/**
 * Kaynti on käynti tietyllä marjapaikalla. Käynti liittyy aina yhteen paikkaan.
 * Käynnillä on aika.
 *
 * @author mkotola
 */
class Kaynti extends BaseModel {

    public $id, $paikka_id, $aika;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_aika');
    }

    public static function haeKaikki() {
        $query = DB::connection()->prepare('SELECT * FROM Kaynti');
        $query->execute();
        $rows = $query->fetchAll();
        $kaynnit = array();

        foreach ($rows as $row) {
            $kaynnit[] = new Kaynti(array(
                'id' => $row['id'],
                'paikka_id' => $row['paikka_id'],
                'aika' => $row['aika']
            ));
        }
        return $kaynnit;
    }

    public static function hae($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kaynti WHERE id=:id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kaynti = new Kaynti(array(
                'id' => $row['id'],
                'paikka_id' => $row['paikka_id'],
                'aika' => $row['aika']
            ));

            return $kaynti;
        }
        return null;
    }

    public static function haePaikanMukaan($paikka_id) {
        $query = DB::connection()->prepare('SELECT * FROM Kaynti WHERE paikka_id=:paikka_id');
        $query->execute(array('paikka_id' => $paikka_id));
        $rows = $query->fetchAll();
        $kaynnit = array();

        foreach ($rows as $row) {
            $kaynnit[] = new Kaynti(array(
                'id' => $row['id'],
                'paikka_id' => $row['paikka_id'],
                'aika' => $row['aika']
            ));
        }
        return $kaynnit;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Kaynti (paikka_id, aika) VALUES (:paikka_id, :aika) RETURNING id');
        $query->execute(array(
            'paikka_id' => $this->paikka_id,
            'aika' => $this->aika
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function tallennaMuuttunut() {
        $query = DB::connection()->prepare('UPDATE Kaynti SET aika = :aika WHERE id = :id;');
        $query->execute(array(
            'id' => $this->id,
            'aika' => $this->aika
        ));
    }

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Kaynti WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function validoi_aika() {
        $errors = array();
        
        // Tarkastetaan, että on timestamp.
        $format = 'Y-m-d H:i:s';
        $d = DateTime::createFromFormat($format, $this->aika);
        
        if (!($d && $d->format($format) == $this->aika)) {
            $errors[] = "Käynnin aika ei ole validi. Syötä muodossa YYYY-MM-DD HH:MM:SS.SSSS";
        }
        
        return $errors;
    }

}
