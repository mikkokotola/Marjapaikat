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
    
    public static function findByMarjaAndKayntivuosi($marja_id, $vuosi) {
        $query = DB::connection()->prepare('SELECT ms.marja_id, ms.kaynti_id, ms.maara, ms.kuvaus FROM Marjasaalis ms, Kaynti k WHERE ms.marja_id=:marja_id AND ms.kaynti_id=k.id AND extract(year from k.aika)=:vuosi;');
        $query->execute(array('marja_id' => $marja_id, 'vuosi' => $vuosi));
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
    
    public static function findByMarjaAndKayntivuosiAndKayttaja($marja_id, $vuosi, $marjastaja_id) {
        $query = DB::connection()->prepare('SELECT ms.marja_id, ms.kaynti_id, ms.maara, ms.kuvaus '
                . 'FROM Marjasaalis ms, Kaynti k, Paikka p '
                . 'WHERE ms.marja_id=:marja_id AND ms.kaynti_id=k.id '
                . 'AND extract(year from k.aika)=:vuosi AND k.paikka_id=p.id '
                . 'AND p.marjastaja_id=:marjastaja_id;');
        $query->execute(array('marja_id' => $marja_id, 'vuosi' => $vuosi, 'marjastaja_id' => $marjastaja_id));
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
    
    public static function findByMarjaAndKayttaja($marja_id, $marjastaja_id) {
        $query = DB::connection()->prepare('SELECT ms.marja_id, ms.kaynti_id, ms.maara, ms.kuvaus '
                . 'FROM Marjasaalis ms, Kaynti k, Paikka p '
                . 'WHERE ms.marja_id=:marja_id AND ms.kaynti_id=k.id '
                . 'AND k.paikka_id=p.id '
                . 'AND p.marjastaja_id=:marjastaja_id;');
        $query->execute(array('marja_id' => $marja_id, 'marjastaja_id' => $marjastaja_id));
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
    
    public static function maaraByMarjaAndVuosi($marja_id, $vuosi) {
        $marjasaaliit = self::findByMarjaAndKayntivuosi($marja_id, $vuosi) ;
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }
    
    public static function maaraByMarjaAndVuosiAndKayttaja($marja_id, $vuosi, $marjastaja_id) {
        $marjasaaliit = self::findByMarjaAndKayntivuosiAndKayttaja($marja_id, $vuosi, $marjastaja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }
    
    public static function maaraByMarjaAndKayttaja($marja_id, $marjastaja_id) {
        $marjasaaliit = self::findByMarjaAndKayttaja($marja_id, $marjastaja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }
    
    
}
