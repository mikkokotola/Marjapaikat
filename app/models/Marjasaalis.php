<?php

/**
 * Marjasaalis sisältää tiedon yhdellä käynnillä poimitun tietyn marjan
 * määrästä ja kuvauksen kyseisestä marjasaaliista.
 *
 * @author mkotola
 */

class Marjasaalis extends BaseModel {
    public $marja_id, $kaynti_id, $maara, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis;');
        $query->execute();
        $rows = $query->fetchAll();
        $marjasaaliit = array();

        foreach ($rows as $row) {
            $marjasaaliit[] = new Marjasaalis(array(
                'marja_id' => $row['marja_id'],
                'kaynti_id' => $row['kaynti_id'],
                'maara' => $row['maara'],
                'kuvaus' => $row['kuvaus']
            ));
        }
        
        return $marjasaaliit;
    }
    
    public static function findByMarja($marja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis WHERE marja_id=:marja_id;');
        $query->execute(array('marja_id' => $marja_id));
        $rows = $query->fetchAll();
        $marjasaaliit = array();

        foreach ($rows as $row) {
            $marjasaaliit[] = new Marjasaalis(array(
                'marja_id' => $row['marja_id'],
                'kaynti_id' => $row['kaynti_id'],
                'maara' => $row['maara'],
                'kuvaus' => $row['kuvaus']
            ));
        }
        return $marjasaaliit;
    }
    
    public static function findByKaynti($kaynti_id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis WHERE kaynti_id=:kaynti_id;');
        $query->execute(array('kaynti_id' => $kaynti_id));
        $rows = $query->fetchAll();
        $marjasaaliit = array();

        foreach ($rows as $row) {
            $marjasaaliit[] = new Marjasaalis(array(
                'marja_id' => $row['marja_id'],
                'kaynti_id' => $row['kaynti_id'],
                'maara' => $row['maara'],
                'kuvaus' => $row['kuvaus']
            ));
        }
        return $marjasaaliit;
        
    }
    
    public static function maaraKokohistoriaByMarja($marja_id) {
        $marjasaaliit = self::findByMarja($marja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }
    
    
}
