<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            $newerrors = $this->{$validator}();
            if (!empty($newerrors)) {
                $errors = array_merge($errors, $newerrors);
            }
        }

        return $errors;
    }

    public function validate_string_length($string, $length) {

        $errors = array();
        if ($string == '' || $string == null) {
            $errors[] = 'Syöte ei saa olla tyhjä';
        }
        if (strlen($string) < $length) {
            $errors[] = 'Nimen pituuden tulee olla vähintään ' . $length . ' merkkiä!';
        }

        return $errors;
    }

}
