-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Marjastaja(
    id SERIAL PRIMARY KEY, 
    kayttajatunnus varchar(120) UNIQUE NOT NULL,
    salasana varchar(120) NOT NULL,
    etunimi varchar(120),
    sukunimi varchar(120)
);

CREATE TABLE Marja(
    id SERIAL PRIMARY KEY,
    nimi varchar(500) UNIQUE
);

CREATE TABLE Paikka(
    id SERIAL PRIMARY KEY,
    marjastaja_id INTEGER REFERENCES Marjastaja(id),
    p DECIMAL,
    i DECIMAL,
    nimi varchar(500)
);

CREATE TABLE Kaynti(
    id SERIAL PRIMARY KEY,
    paikka_id INTEGER REFERENCES Paikka(id),
    aika TIMESTAMP
);

-- Liitostaulu Marjastajan ja Marjan välillä --
CREATE TABLE Marjastajamarja(
    marjastaja_id INTEGER REFERENCES Marjastaja(id),
    marja_id INTEGER REFERENCES Marja(id)
);

-- Liitostaulu Marjan ja Kaynnin välillä --
CREATE TABLE Marjakaynti(
    marja_id INTEGER REFERENCES Marja(id),
    kaynti_id INTEGER REFERENCES Kaynti(id),
    maara DECIMAL,
    kuvaus varchar(1000)
);

