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

}
