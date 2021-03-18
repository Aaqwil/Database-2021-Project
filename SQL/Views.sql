CREATE VIEW sprzedane_gry AS
	SELECT gra.id, tytul, gatunek, wydawca.nazwa AS "wydawca", COUNT(gra.id) AS "sprzedane"
	FROM projekt.wydawca JOIN projekt.gra ON projekt.wydawca.id = projekt.gra.wydawca
	JOIN projekt.zamowione_gry ON projekt.gra.id = projekt.zamowione_gry.id_g
	GROUP BY gra.id, tytul, gatunek, wydawca.nazwa
	ORDER BY gra.id;

CREATE VIEW niezrealizowane_zamowienia AS
	SELECT zamowienie.id, zamowienie.stan AS "stan", dostawa.typ AS "typ dostawy", zamowienie.email AS "klient"
	FROM projekt.zamowienie JOIN projekt.dostawa ON projekt.zamowienie.typ_dostawy = projekt.dostawa.id
	WHERE zamowienie.stan NOT LIKE 'zrealizowane'
	ORDER BY zamowienie.id;

CREATE VIEW oceny_uzytkownikow AS
	SELECT ocena.id_k AS "autor", gra.tytul, ocena
	FROM projekt.ocena JOIN projekt.gra ON projekt.ocena.id_g = projekt.gra.id;