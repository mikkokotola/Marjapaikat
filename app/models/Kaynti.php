<?php

/**
 * Kaynti on käynti tietyllä marjapaikalla. Käynti liittyy aina yhteen paikkaan.
 * Käynnillä on aika.
 *
 * @author mkotola
 */

class Kaynti {
    public $id, $paikka_id, $aika;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
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
    
    public static function find($id){
        $query = DB::connection()->prepare('SELECT * FROM Kaynti WHERE id=:id LIMIT 1');
        $query->execute(array('id'=> $id));
        $row = $query->fetch();
        
        if ($row){
            $kaynti = new Kaynti(array(
                'id' => $row['id'],
                'paikka_id' => $row['paikka_id'],
                'aika' => $row['aika']
            ));
        
            return $kaynti;
        }
        return null;
    }

}
