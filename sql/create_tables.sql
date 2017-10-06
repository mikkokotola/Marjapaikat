
CREATE TABLE Marjastaja(
    id SERIAL PRIMARY KEY, 
    kayttajatunnus varchar(120) UNIQUE NOT NULL,
    salasana varchar(120) NOT NULL,
    etunimi varchar(120) NOT NULL,
    sukunimi varchar(120) NOT NULL
);

CREATE TABLE Marja(
    id SERIAL PRIMARY KEY,
    nimi varchar(500) UNIQUE
);

CREATE TABLE Paikka(
    id SERIAL PRIMARY KEY,
    marjastaja_id INTEGER REFERENCES Marjastaja(id),
    p DECIMAL NOT NULL,
    i DECIMAL NOT NULL,
    nimi varchar(500)
);

CREATE TABLE Kaynti(
    id SERIAL PRIMARY KEY,
    paikka_id INTEGER REFERENCES Paikka(id),
    aika TIMESTAMP NOT NULL
);

-- Liitostaulu Marjastajan ja Marjan välillä --
CREATE TABLE Suosikkimarja(
    marjastaja_id INTEGER REFERENCES Marjastaja(id),
    marja_id INTEGER REFERENCES Marja(id)
);

CREATE TABLE Marjasaalis(
    marja_id INTEGER REFERENCES Marja(id),
    kaynti_id INTEGER REFERENCES Kaynti(id),
    maara DECIMAL NOT NULL,
    kuvaus varchar(1000)
);

