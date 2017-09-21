<?php

/**
 * Marjasaalis sisältää tiedon yhdellä käynnillä poimitun tietyn marjan
 * määrästä ja kuvauksen kyseisestä marjasaaliista.
 *
 * @author mkotola
 */

class Marjasaalis {
    public $marja_id, $kaynti_id, $maara, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis;');
        $query->execute();
        $rows = $query->fetchAll();
        $suosikkimarjat = array();

        foreach ($rows as $row) {
            $marjasaaliit[] = new Marjasaalis(array(
                'marja_id' => $row['marja_id'],
                'kaynti_id' => $row['kaynti_id'],
                'maara' => $row['maara'],
                'kuvaus' => $row['kuvaus']
            ));
        }
        // TÄHÄN JÄIN.
        return $suosikkimarjat;
    }
}
