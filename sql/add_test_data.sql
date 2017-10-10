-- Lisää INSERT INTO lauseet tähän tiedostoon

-- Marjastaja-taulun testidata --
INSERT INTO Marjastaja (id, kayttajatunnus, salasana, etunimi, sukunimi) VALUES (1, 'kalle1', 'ahven', 'Kalle', 'Aaltonen');
INSERT INTO Marjastaja (id, kayttajatunnus, salasana, etunimi, sukunimi) VALUES (2, 'jukka1', 'lahna', 'Jukka', 'Tatti');
INSERT INTO Marjastaja (id, kayttajatunnus, salasana, etunimi, sukunimi) VALUES (3, 'mikko1', 'hauki', 'Mikko', 'Marjastaja');
ALTER SEQUENCE Marjastaja_id_seq RESTART WITH 4;

-- Marja-taulun testidata --
INSERT INTO Marja (id, nimi) VALUES (1, 'Puolukka');
INSERT INTO Marja (id, nimi) VALUES (2, 'Mustikka');
INSERT INTO Marja (id, nimi) VALUES (3, 'Kanttarelli');
INSERT INTO Marja (id, nimi) VALUES (4, 'Suppilovahvero');
INSERT INTO Marja (id, nimi) VALUES (5, 'Herkkutatti');
ALTER SEQUENCE Marja_id_seq RESTART WITH 6;

-- Paikka-taulun testidata --
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (1, 1, 61.707724, 25.705254, 'Itäjärvi');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (2, 1, 61.807724, 25.805254, 'Kumpare');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (3, 1, 62.007724, 25.705254, 'Kangas');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (4, 2, 61.107724, 25.105254, 'Hirvikangas');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (5, 2, 61.207724, 25.205254, 'Isomäki');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (6, 3, 61.307724, 25.305254, 'Suppatie');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (7, 3, 61.407724, 25.405254, 'Louhikko');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (8, 1, 61.507724, 25.505254, 'Kivikko');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (9, 1, 61.607724, 25.605254, 'Soralampi');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (10, 1, 62.307724, 25.705254, 'Mustikkapaikka');
INSERT INTO Paikka (id, marjastaja_id, p, i, nimi) VALUES (11, 1, 62.407724, 25.805254, 'Tienpää');
ALTER SEQUENCE Paikka_id_seq RESTART WITH 12;

-- Kaynti-taulun testidata --
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (1, 1, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (2, 1, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (3, 1, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (4, 2, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (5, 2, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (6, 3, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (7, 3, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (8, 4, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (9, 4, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (10, 4, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (11, 4, NOW());
INSERT INTO Kaynti (id, paikka_id, aika) VALUES (12, 7, NOW());
ALTER SEQUENCE Kaynti_id_seq RESTART WITH 13;

-- Suosikkimarja-taulun testidata --
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (1, 1);
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (1, 2);
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (1, 3);
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (2, 1);
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (2, 2);
INSERT INTO Suosikkimarja (marjastaja_id, marja_id) VALUES (3, 3);

-- Marjasaalis-taulun testidata --
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 1, 2.0, 'Erittäin hyviä marjoja!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (2, 1, 3.0, 'Todella hyviä marjoja!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (3, 1, 4.0, 'Aika sateista oli, joten marja aika märkiä.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 2, 2.1, 'Isoja olivat');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (2, 2, 0.2, 'Todella suuri esiintymä!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (3, 2, 0.3, 'Pieniä marjoja.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 3, 1.4, 'Taas oli hyvin.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 8, 3.3, 'Jatkoin siitä mihin edellisellä käynnillä jäin.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (2, 8, 4.0, 'Erittäin hyviä marjoja!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 9, 0.0, 'En poiminut ollenkaan, olivat raakoja.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (4, 3, 10.4, 'Paljon sieniä!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (5, 3, 5.0, 'Todella kiinteitä olivat.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 8, 15.0, 'Runsas saalis.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 8, 1.4, 'Olivat aika märkiä.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 8, 2.4, 'Nyt pakastimeen.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (1, 12, 5.4, 'Taas oli hyvin.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (2, 12, 6.4, 'Hyvää marjaa!');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (4, 12, 3.3, 'Nälkä meinasi tulla kun poimi.');
INSERT INTO Marjasaalis (marja_id, kaynti_id, maara, kuvaus) VALUES (5, 12, 1.1, 'Hyvä saalis.');
