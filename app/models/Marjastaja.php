<?php

/**
 * Marjastaja on järjestelmän käyttäjän malli. Sisältää henkilö- ja käyttäjätiedot.
 *
 * @author mkotola
 */

class Marjastaja extends BaseModel {
    
    public $id, $kayttajatunnus, $salasana, $etunimi, $sukunimi;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Marjastaja');
        $query->execute();
        $rows = $query->fetchAll();
        $marjastajat = array();
        
        foreach ($rows as $row) {
            $marjastajat[] = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        }
        return $marjastajat;
    }
    
    public static function find($id){
        $query = DB::connection()->prepare('SELECT * FROM Marjastaja WHERE id=:id LIMIT 1');
        $query->execute(array('id'=> $id));
        $row = $query->fetch();
        
        if ($row){
            $marjastaja = new Marjastaja(array(
                'id' => $row['id'],
                'kayttajatunnus' => $row['kayttajatunnus'],
                'salasana' => $row['salasana'],
                'etunimi' => $row['etunimi'],
                'sukunimi' => $row['sukunimi']
            ));
        
            return $marjastaja;
        }
        return null;
    }
    
}
