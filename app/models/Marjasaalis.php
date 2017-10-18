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
        // Validointi tekemättä.
        $this->validators = array('validoi_maara', 'validoi_kuvaus');
    }

    public static function haeKaikki() {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis ORDER BY marja_id ASC;');
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

    public static function haeMarjanMukaan($marja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis WHERE marja_id=:marja_id ORDER BY kaynti_id ASC;');
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

    public static function haeKaynninMukaan($kaynti_id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis WHERE kaynti_id=:kaynti_id ORDER BY marja_id ASC;');
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

    public static function haeMarjanJaKaynninMukaan($marja_id, $kaynti_id) {
        $query = DB::connection()->prepare('SELECT * FROM Marjasaalis WHERE marja_id=:marja_id AND kaynti_id=:kaynti_id LIMIT 1;');
        $query->execute(array('marja_id' => $marja_id, 'kaynti_id' => $kaynti_id));
        $row = $query->fetch();

        if ($row) {
            $marjasaalis = new Marjasaalis(array(
                'marja_id' => $row['marja_id'],
                'kaynti_id' => $row['kaynti_id'],
                'maara' => $row['maara'],
                'kuvaus' => $row['kuvaus']
            ));

            return $marjasaalis;
        }
        return null;
    }

    public static function haeMarjanJaKayntivuodenMukaan($marja_id, $vuosi) {
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

    public static function haeMarjanJaKayntivuodenJaMarjastajanMukaan($marja_id, $vuosi, $marjastaja_id) {
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

    public static function haeMarjanJaMarjastajanMukaan($marja_id, $marjastaja_id) {
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

    public static function maaraKokoHistoriaMarjanMukaan($marja_id) {
        $marjasaaliit = self::haeMarjanMukaan($marja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }

    public static function maaraMarjanJaVuodenMukaan($marja_id, $vuosi) {
        $marjasaaliit = self::haeMarjanJaKayntivuodenMukaan($marja_id, $vuosi);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }

    public static function maaraMarjanJaVuodenJaMarjastajanMukaan($marja_id, $vuosi, $marjastaja_id) {
        $marjasaaliit = self::haeMarjanJaKayntivuodenJaMarjastajanMukaan($marja_id, $vuosi, $marjastaja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }

    public static function maaraMarjanJaMarjastajanMukaan($marja_id, $marjastaja_id) {
        $marjasaaliit = self::haeMarjanJaMarjastajanMukaan($marja_id, $marjastaja_id);
        $poimittuSumma = 0.0;
        foreach ($marjasaaliit as $saalis) {
            $poimittuSumma += $saalis->maara;
        }
        return $poimittuSumma;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (:marja_id, :kaynti_id, :maara, :kuvaus)');
        $query->execute(array(
            'marja_id' => $this->marja_id,
            'kaynti_id' => $this->kaynti_id,
            'maara' => $this->maara,
            'kuvaus' => $this->kuvaus,
        ));
    }

    public function tallennaMuuttunut() {
        $query = DB::connection()->prepare('UPDATE Marjasaalis SET maara = :maara, kuvaus = :kuvaus WHERE marja_id = :marja_id AND kaynti_id = :kaynti_id;');
        $query->execute(array(
            'marja_id' => $this->marja_id,
            'kaynti_id' => $this->kaynti_id,
            'maara' => $this->maara,
            'kuvaus' => $this->kuvaus,
        ));
    }

    public function poista() {
        $query = DB::connection()->prepare('DELETE FROM Marjasaalis WHERE marja_id=:marja_id AND kaynti_id=:kaynti_id;');
        $query->execute(array('marja_id' => $this->marja_id, 'kaynti_id' => $this->kaynti_id));
    }

    // TEKEMÄTTÄ
    public function validoi_maara() {
        $errors = array();

        if (is_double($this->maara) && ($this->maara < 0)) {
            $errors[] = "Poimitun määrän täytyy olla numero ja vähintään 0.";
        }

        return $errors;
    }

    public function validoi_kuvaus() {
        $errors = array();
        $newerrors = $this->validate_string_length($this->kuvaus, 0, 1000);
        if (!empty($newerrors)) {
            $errors = array_merge($errors, $newerrors);
        }
        return $errors;
    }

    public function validoi_identiteetti_uusi() {
        $errors = array();

        $kaikkiMarjasaaliit = $this->haeKaikki();
        foreach ($kaikkiMarjasaaliit as $marjasaalis) {
            if ($marjasaalis->marja_id == $this->marja_id && $marjasaalis->kaynti_id == $this->kaynti_id) {
                $errors[] = "Tässä käynnissä on jo merkintä samasta marjasta. Käyntiin ei voi tehdä useita merkintöjä samalle marjalle. Muokkaa vanhaa merkintää.";
            }
        }

        return $errors;
    }

    public function validoi_identiteetti_muokattava($muokattavasaalis) {
        $errors = array();

        $kaikkiMarjasaaliit = $this->haeKaikki();
        foreach ($kaikkiMarjasaaliit as $marjasaalis) {
            // Tarkastetaan, onko tarkastelussa muokattava marjasaaliis. Tehdään erroreita vain jos kyse on jostakin muusta.
            if (!($muokattavasaalis->marja_id == $marjasaalis->marja_id && $muokattavasaalis->kaynti_id == $marjasaalis->kaynti_id)) {
                if ($marjasaalis->marja_id == $this->marja_id && $marjasaalis->kaynti_id == $this->kaynti_id) {
                    $errors[] = "Tässä käynnissä on jo merkintä samasta marjasta. Käyntiin ei voi tehdä useita merkintöjä samalle marjalle. Muokkaa vanhaa merkintää.";
                }
            }
        }

        return $errors;
    }

}
