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
    
    public function saveChanged(){
        $query = DB::connection()->prepare('UPDATE Paikka SET p = :p, i = :i, nimi = :nimi WHERE id = :id;');
        $query->execute(array(
            'id' => $this->id,
            'p' => $this->p,
            'i' => $this->i,
            'nimi' => $this->nimi
        ));
        
    }
    
    
    
    public function delete(){
        // TOTEUTTAMATTA
        
        // Poistettava Marjasaaliit, Käynnit, Paikat ja Marjastajien viitteet
        
               
        //$query = DB::connection()->prepare('DELETE FROM Paikka WHERE id = :id');
        //$query->execute(array('id' => $this->id));
        
        
        
    }
    
    public function validate_name() {
        $errors = array();
        $newerrors = $this->validate_string_length($this->nimi, 1, 500);
        if (!empty($newerrors)) {
            $errors = array_merge($errors, $newerrors);
        }
        return $errors;
    }
    
    public function validate_p() {
        $errors = array();
        
        if (!is_double($this->p)) { 
            $errors[] = "P-koordinaatin täytyy olla desimaaliluku.";
        }
        
        if (is_double($this->p) && ($this->p < -90 | $this->p > 90)) { 
            $errors[] = "P-koordinaatin täytyy olla välillä -90...90.";
        }

        return $errors;
        
    }
    
    public function validate_i() {
        $errors = array();
        
        if (!is_double($this->i)) { 
            $errors[] = "I-koordinaatin täytyy olla desimaaliluku.";
        }
        
        if (is_double($this->i) && ($this->i < -180 | $this->i > 180)) { 
            $errors[] = "P-koordinaatin täytyy olla välillä -180...180.";
        }

        return $errors;
        
    }

}
