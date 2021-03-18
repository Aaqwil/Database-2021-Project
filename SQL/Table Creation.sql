CREATE SCHEMA projekt;
SET SEARCH_PATH to projekt;

CREATE TABLE klient(email varchar(30), imie varchar(30), nazwisko varchar(30), haslo varchar(30));
CREATE TABLE zgoda(id int, wymagana boolean, nazwa varchar(30), opis text);
CREATE TABLE adres(id int, miasto varchar(30), ulica varchar(30), nr_dom int, nr_mieszkanie int);
CREATE TABLE zwrot(id int, email varchar(30), stan varchar(30));
CREATE TABLE dostawa(id int, typ varchar(30), cena int, czas int);
CREATE TABLE zamowienie(id int, stan varchar(30), typ_dostawy int, email varchar(30));
CREATE TABLE gra(id int, tytul varchar(30), gatunek varchar(30), cena int, typ varchar(30), ilosc int, wydawca int);
CREATE TABLE wydawca(id int, nazwa varchar(30), email varchar(30), telefon varchar(9));

ALTER TABLE klient ADD PRIMARY KEY (email);
ALTER TABLE zgoda ADD PRIMARY KEY (id);
ALTER TABLE adres ADD PRIMARY KEY (id);
ALTER TABLE zwrot ADD PRIMARY KEY (id);
ALTER TABLE dostawa ADD PRIMARY KEY (id);
ALTER TABLE zamowienie ADD PRIMARY KEY (id);
ALTER TABLE gra ADD PRIMARY KEY (id);
ALTER TABLE wydawca ADD PRIMARY KEY (id);

ALTER TABLE zwrot ADD FOREIGN KEY (email) REFERENCES klient(email);
ALTER TABLE zamowienie ADD FOREIGN KEY (typ_dostawy) REFERENCES dostawa(id);
ALTER TABLE zamowienie ADD FOREIGN KEY (email) REFERENCES klient(email);
ALTER TABLE gra ADD FOREIGN KEY (wydawca) REFERENCES wydawca(id);

CREATE TABLE adres_klienta(email varchar(30), id_a int, domyslny boolean);
CREATE TABLE zgody_klienta(id_z int, email varchar(30));
CREATE TABLE zamowione_gry(id_z int, id_g int);
CREATE TABLE zwrocone_gry(id_z int, id_g int);
CREATE TABLE ocena(id_k varchar(30), id_g int, ocena text);

ALTER TABLE adres_klienta ADD PRIMARY KEY (email, id_a);
ALTER TABLE zgody_klienta ADD PRIMARY KEY (id_z, email);
ALTER TABLE zamowione_gry ADD PRIMARY KEY (id_z, id_g);
ALTER TABLE zwrocone_gry ADD PRIMARY KEY (id_z, id_g);
ALTER TABLE ocena ADD PRIMARY KEY (id_k, id_g);

ALTER TABLE adres_klienta ADD FOREIGN KEY (email) REFERENCES klient(email);
ALTER TABLE adres_klienta ADD FOREIGN KEY (id_a) REFERENCES adres(id);
ALTER TABLE zgody_klienta ADD FOREIGN KEY (id_z) REFERENCES zgoda(id);
ALTER TABLE zgody_klienta ADD FOREIGN KEY (email) REFERENCES klient(email);
ALTER TABLE zamowione_gry ADD FOREIGN KEY (id_z) REFERENCES zamowienie(id);
ALTER TABLE zamowione_gry ADD FOREIGN KEY (id_g) REFERENCES gra(id);
ALTER TABLE zwrocone_gry ADD FOREIGN KEY (id_z) REFERENCES zwrot(id);
ALTER TABLE zwrocone_gry ADD FOREIGN KEY (id_g) REFERENCES gra(id);
ALTER TABLE ocena ADD FOREIGN KEY (id_k) REFERENCES klient(email);
ALTER TABLE ocena ADD FOREIGN KEY (id_g) REFERENCES gra(id);
