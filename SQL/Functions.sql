CREATE OR REPLACE FUNCTION zmniejsz_ilosc_sztuk()
RETURNS TRIGGER AS
$$
DECLARE
	amount INTEGER := (SELECT ilosc - 1 FROM projekt.gra WHERE projekt.gra.id = NEW.id_g);
BEGIN
	UPDATE projekt.gra
	SET ilosc = amount
	WHERE projekt.gra.id = NEW.id_g;
	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER zmniejsz_ilosc_gier
	AFTER INSERT ON projekt.zamowione_gry
	FOR EACH ROW
	EXECUTE PROCEDURE zmniejsz_ilosc_sztuk();



CREATE OR REPLACE FUNCTION dodaj_zgody_wymagane()
RETURNS TRIGGER AS
$$
BEGIN
	INSERT INTO zgody_klienta VALUES (1, NEW.email), (2, NEW.email);
	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER dodaj_zgody_po_rejestracji
	AFTER INSERT ON projekt.klient
	FOR EACH ROW
	EXECUTE PROCEDURE dodaj_zgody_wymagane();




CREATE OR REPLACE FUNCTION zwieksz_ilosc_sztuk_po_zwrocie()
RETURNS TRIGGER AS
$$
DECLARE
	amount INTEGER := (SELECT ilosc + 1 FROM projekt.gra WHERE projekt.gra.id = NEW.id_g);
BEGIN
	UPDATE projekt.gra
	SET ilosc = amount
	WHERE projekt.gra.id = NEW.id_g;
	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER zwieksz_ilosc
	AFTER INSERT ON projekt.zwrocone_gry
	FOR EACH ROW
	EXECUTE PROCEDURE zwieksz_ilosc_sztuk_po_zwrocie();



CREATE OR REPLACE FUNCTION sprawdz_istnienie_adresu()
RETURNS TRIGGER AS
$$
BEGIN
	IF EXISTS(
		SELECT 1 FROM projekt.adres 
		WHERE adres.miasto = NEW.miasto
		AND adres.ulica = NEW.ulica
		AND adres.nr_dom = NEW.nr_dom
		AND adres.nr_mieszkanie = NEW.nr_mieszkanie) THEN
		RETURN NULL;
	ELSIF EXISTS(
		SELECT 1 FROM projekt.adres 
		WHERE adres.miasto = NEW.miasto
		AND adres.ulica = NEW.ulica
		AND adres.nr_dom = NEW.nr_dom
		AND NEW.nr_mieszkanie IS NULL
		AND adres.nr_mieszkanie IS NULL) THEN
		RETURN NULL;
	ELSE
		RETURN NEW;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER sprawdz_czy_dodac_adres
	BEFORE INSERT ON projekt.adres
	FOR EACH ROW
	EXECUTE PROCEDURE sprawdz_istnienie_adresu();




CREATE OR REPLACE FUNCTION sprawdz_legalnosc_oceny()
RETURNS TRIGGER AS
$$
BEGIN
	IF EXISTS(
		SELECT 1
		FROM projekt.zamowienie
		JOIN projekt.zamowione_gry ON projekt.zamowienie.id = projekt.zamowione_gry.id_z
		JOIN projekt.gra ON projekt.zamowione_gry.id_g = projekt.gra.id
		WHERE projekt.zamowienie.email = NEW.id_k
		AND projekt.zamowione_gry.id_g = NEW.id_g
		GROUP BY 1) THEN
		RETURN NEW;
	ELSE
		RETURN NULL;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER dodaj_ocene
	BEFORE INSERT ON projekt.ocena
	FOR EACH ROW
	EXECUTE PROCEDURE sprawdz_legalnosc_oceny();



CREATE OR REPLACE FUNCTION sprawdz_legalnosc_zwrotu()
RETURNS TRIGGER AS
$$
DECLARE
	kupione bigint := (
		SELECT COUNT(gra.id)
		FROM projekt.zamowione_gry JOIN projekt.gra ON projekt.gra.id = projekt.zamowione_gry.id_g 
		JOIN projekt.zamowienie ON projekt.zamowione_gry.id_z = projekt.zamowienie.id 
		JOIN projekt.klient ON projekt.zamowienie.email = projekt.klient.email
		WHERE projekt.klient.email = 
			(SELECT zwrot.email FROM projekt.zwrot WHERE projekt.zwrot.id = NEW.id_z)
		AND projekt.gra.id = NEW.id_g
		GROUP BY gra.id);
	zwrocone bigint := (
		SELECT COUNT(gra.id)
		FROM projekt.zwrocone_gry JOIN projekt.gra ON projekt.gra.id = projekt.zwrocone_gry.id_g 
		JOIN projekt.zwrot ON projekt.zwrocone_gry.id_z = projekt.zwrot.id 
		JOIN projekt.klient ON projekt.zwrot.email = projekt.klient.email WHERE projekt.klient.email =
			(SELECT zwrot.email FROM projekt.zwrot WHERE projekt.zwrot.id = NEW.id_z)
		AND gra.id = NEW.id_g
		GROUP BY gra.id);
BEGIN
	IF kupione <= zwrocone THEN
		RETURN NULL;
	ELSE
		RETURN NEW;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER dodaj_zwrot_jesli_legalny
	BEFORE INSERT ON projekt.zwrocone_gry
	FOR EACH ROW
	EXECUTE PROCEDURE sprawdz_legalnosc_zwrotu();




CREATE OR REPLACE FUNCTION pobierz_gry_klienta(mail varchar(30))
RETURNS TABLE (ID int, Tytul varchar(30), Gatunek varchar(30), Wydawca varchar(30), KupioneEgzemplarze bigint) AS
$$
BEGIN
	RETURN QUERY
		SELECT gra.id, gra.tytul, gra.gatunek, wydawca.nazwa, COUNT(gra.id) AS "Kupione Egzemplarze"
		FROM projekt.wydawca JOIN projekt.gra ON projekt.wydawca.id = projekt.gra.wydawca 
		JOIN projekt.zamowione_gry ON projekt.gra.id = projekt.zamowione_gry.id_g 
		JOIN projekt.zamowienie ON projekt.zamowione_gry.id_z = projekt.zamowienie.id 
		JOIN projekt.klient ON projekt.zamowienie.email = projekt.klient.email WHERE projekt.klient.email = mail
		GROUP BY gra.id, gra.tytul, wydawca.nazwa;
END;
$$
LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION pobierz_zwroty_klienta(mail varchar(30))
RETURNS TABLE (IDZwrot int, Stan varchar(30), Tytul varchar(30), ZwroconeEgzemplarze bigint) AS
$$
BEGIN
	RETURN QUERY
		SELECT projekt.zwrot.id, projekt.zwrot.stan, projekt.gra.tytul, COUNT(*)
		FROM projekt.zwrot JOIN projekt.zwrocone_gry ON projekt.zwrot.id = projekt.zwrocone_gry.id_z
		JOIN projekt.gra ON projekt.gra.id = projekt.zwrocone_gry.id_g
		WHERE projekt.zwrot.email = mail
		GROUP BY projekt.zwrot.id, projekt.gra.tytul;
END;
$$
LANGUAGE 'plpgsql';




CREATE OR REPLACE FUNCTION przypisane_adresy(mail varchar(30))
RETURNS TABLE (ID int, Miasto varchar(30), Ulica varchar(30), Dom int, Mieszkanie int, Domyslny boolean) AS
$$
BEGIN
	RETURN QUERY
		SELECT adres.id, adres.miasto, adres.ulica, adres.nr_dom, adres.nr_mieszkanie, adres_klienta.domyslny
		FROM projekt.adres JOIN projekt.adres_klienta ON projekt.adres.id = projekt.adres_klienta.id_a
		JOIN projekt.klient USING(email)
		WHERE projekt.klient.email = mail
		ORDER BY adres.id;
END;
$$
LANGUAGE 'plpgsql';
