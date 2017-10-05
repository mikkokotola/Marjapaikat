<?php

/**
 * Paikka on marjapaikka. Paikka liittyy aina yhteen Marjastajaan ja sisältää
 * paikan marjastajan tunnisteen, paikan sjjainnin ja paikan nimen.
 * 
 * @author mkotola
 */
class Paikka extends BaseModel{
    
    public $id, $marjastaja_id, $p, $i, $nimi;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_p', 'validate_i');
    }
    
    public static function all() {
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
    
    public static function find($id){
        $query = DB::connection()->prepare('SELECT * FROM Paikka WHERE id=:id LIMIT 1');
        $query->execute(array('id'=> $id));
        $row = $query->fetch();
        
        if ($row){
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
    
    public static function findByKayttaja($id) {
        $query = DB::connection()->prepare('SELECT * FROM Paikka WHERE marjastaja_id=:id');
        $query->execute(array('id'=> $id));
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
    
    public function save(){
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
    
    public function validate_name() {
        $errors = array();
        $newerrors = $this->validate_string_length($this->nimi, 1);
        if (!empty($newerrors)) {
            $errors = array_merge($errors, $newerrors);
        }

        return $errors;
    }
    
    public function validate_p() {
        $errors = array();
        // Tarkastetaan, että koordinaatti oikeassa muodossa.
        // Tarkastetaan, että koordinaatti oikealla välillä.
        return $errors;
        
    }
    
    public function validate_i() {
        $errors = array();
        // Tarkastetaan, että koordinaatti oikeassa muodossa.
        // Tarkastetaan, että koordinaatti oikealla välillä.
        return $errors;
    }

}
