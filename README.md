# Tietokantasovelluksen esittelysivu

Yleisiä linkkejä:

* [Linkki sovellukseeni](http://mkotola.users.cs.helsinki.fi/marjapaikat/)
* [Linkki dokumentaatiooni](https://github.com/mikkokotola/Marjapaikat/blob/master/doc/dokumentaatio.pdf)

## Työn aihe

Marjapaikat. Sovellus tarjoaa käyttäjille palvelun marja- ja sienipaikkojensa
tallentamiseen. Tunnistettu käyttäjä voi karttakäyttöliittymän kautta
tallentaa paikan ja liittää paikkaan käyntejä. Käyntiin liittyy poimittuja
marjoja tai sieniä ja niiden määriä sekä käynnin ajankohta. Käyttäjä voi myös
selailla järjestelmässä olevia marjoja ja muiden käyttäjien kommentteja
marjoista sekä muiden käyttäjien poimintamääriä.

Näkymät:
Marjat:
/index.html
Esimerkkinäkymä: http://mkotola.users.cs.helsinki.fi/marjapaikat/
Kuvaus: Aloitusnäkymä, jossa listataan kaikki marjat ja näytetään poimintatilastot. 
Ei edellytä kirjautumista. Sivulla on kirjautuneelle käyttäjälle myös "Lisää marja"-nappi,
jonka kautta voi lisätä järjestelmään uuden marjan.

Marja:
/marja/[Marja.id]
Esimerkkinäkymä: http://mkotola.users.cs.helsinki.fi/marjapaikat/marja
Kuvaus: Yksittäisen marjan näkymä, jossa näytetään marjan tilastot ja kärkipoimijat. 
Ei edellytä kirjautumista.

Kirjautuminen: 
/login
Esimerkkinäkymä: http://mkotola.users.cs.helsinki.fi/marjapaikat/login
Kuvaus: Kirjautumisnäkymä.

Paikkojen selailu:
/[Marjastaja.id]/
Esimerkkinäkymä: http://mkotola.users.cs.helsinki.fi/marjapaikat/marjastaja/paikat
Kuvaus: Käyttäjän omien paikkojen selailusivu. Google Maps -APIa käyttävä karttanäkymä. 
Käyttäjä voi selailla omia paikkojaan ja lisätä uusia paikkoja.

Paikan näkymä:
/[Marjastaja.id]/[Paikka.id]/
Esimerkkinäkymä: http://mkotola.users.cs.helsinki.fi/marjapaikat/marjastaja/paikka
Kuvaus: Käyttäjän marjapaikan sivu. Näyttää paikan koordinaatit sekä käynnit ja 
käynneillä poimitut marjat sekä marjakohtaiset määrät ja kommentit. Käyntejä ja 
käynnin marjamerkintöjä voi lisätä ja koordinaatteja, käyntien ajankohtia ja käyntien 
marjakohtaisia tietoja voi muokata. Marjamerkintöjä ja käyntejä voi poistaa. 
Käyttäjä voi myös poistaa koko marjapaikan.
