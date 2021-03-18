INSERT INTO klient VALUES
('karol@mail', 'Karol', 'Sawicki', 'pass'),
('emilia@mail', 'Emilia', 'Sawicka', 'pass'),
('monika@mail', 'Karol', 'Sawicka', 'pass'),
('grzegorz@mail', 'Karol', 'Sawicki', 'pass');

INSERT INTO zgoda VALUES
(1, TRUE, 'regulamin', 'potwierdzenie zapoznania sie z regulaminem sklepu'),
(2, TRUE, 'RODO', 'zgoda na przetwarzanie danych osobowych'),
(3, FALSE, 'marketing', 'zgoda na przesylanie reklam droga mailowa');

INSERT INTO adres(id, miasto, ulica, nr_dom) VALUES
(1, 'Cycow', 'Wolka Cycowska', 30),
(4, 'Cycow', 'Malinowka', 63);

INSERT INTO adres VALUES
(2, 'Lublin', 'Slowikowskiego', 6, 438),
(3, 'Krakow', 'Reymonta', 17, 456);

INSERT INTO zwrot VALUES
(1, 'karol@mail', 'przyjety'),
(2, 'karol@mail', 'przyjety'),
(3, 'emilia@mail', 'zgloszony');

INSERT INTO dostawa VALUES
(1, 'droga elektroniczna', 0, 0),
(2, 'poczta', 5, 10), 
(3, 'kurier UPS', 15, 5), 
(4, 'paczkomat', 10, 5);

INSERT INTO zamowienie VALUES
(1, 'zrealizowane', 4, 'karol@mail'),
(2, 'zrealizowane', 3, 'karol@mail'),
(3, 'zrealizowane', 1, 'karol@mail'),
(4, 'zrealizowane', 1, 'karol@mail'),
(5, 'zrealizowane', 3, 'karol@mail'),
(6, 'zrealizowane', 2, 'emilia@mail'),
(7, 'wyslane', 4, 'grzegorz@mail'),
(8, 'kompletowane', 4, 'monika@mail'),
(9, 'przyjete', 4, 'karol@mail');

INSERT INTO wydawca VALUES
(1, 'Bethesda', 'wyd1@mail', '111111111'),
(2, 'Sega', 'wyd2@mail', '222222222'),
(3, 'Square Enix', 'wyd3@mail', '333333333'),
(4, '11 bit', 'wyd4@mail', '444444444'),
(5, 'THQ Nordic', 'wyd5@mail', '555555555'),
(6, 'Rockstar Games', 'wyd6@mail', '666666666'),
(7, 'CD Projekt', 'wyd7@mail', '777777777'),
(8, 'Paradox', 'wyd8@mail', '888888888'),
(9, 'Valve', 'wyd9@mail', '999999999');

INSERT INTO gra VALUES
(1, 'DOOM', 'FPS', 99, 'kopia fizyczna', 99, 1),
(2, 'DOOM Eternal', 'FPS', 199, 'kopia fizyczna', 99, 1),
(3, 'Wolfenstein The New Order', 'FPS', 99, 'kopia fizyczna', 99, 1),
(4, 'Wolfenstein The New Collosus', 'FPS', 199, 'kopia fizyczna', 99, 1),
(5, 'Yakuza 0', 'RPG', 99, 'kopia fizyczna', 10, 2),
(6, 'Yakuza Kiwami', 'RPG', 99, 'kopia fizyczna', 10, 2),
(7, 'Yakuza Kiwami 2', 'RPG', 149, 'kopia fizyczna', 10, 2),
(8, 'Nier Automata', 'RPG', 149, 'kopia fizyczna', 1, 3),
(9, 'Final Fantasy XV', 'RPG', 149, 'kod', 100, 3),
(10, 'Octopath Traveler', 'RPG', 149, 'kod', 100, 3),
(11, 'Moonlighter', 'RPG', 50, 'kod', 100, 4),
(12, 'Elex', 'RPG', 75, 'kod', 100, 5),
(13, 'GTA V', 'Action', 129, 'kod', 1000, 6),
(14, 'Cyberpunk 2077', 'RPG', 199, 'kopia fizyczna', 100, 7),
(15, 'Tyranny', 'RPG', 79, 'kod', 100, 8),
(16, 'Pillars of Eternity', 'RPG', 79, 'kod', 100, 8),
(17, 'Portal', 'Puzzle', 79, 'kod', 100, 9),
(18, 'Portal 2', 'Puzzle', 79, 'kod', 100, 9);

INSERT INTO adres_klienta VALUES
('karol@mail', 1, FALSE),
('karol@mail', 2, FALSE),
('karol@mail', 3, FALSE),
('karol@mail', 4, FALSE),
('monika@mail', 4, FALSE),
('monika@mail', 1, FALSE),
('emilia@mail', 1, FALSE),
('emilia@mail', 3, FALSE),
('grzegorz@mail', 1, FALSE);

INSERT INTO zgody_klienta VALUES
(1, 'karol@mail'),
(2, 'karol@mail'),
(1, 'emilia@mail'),
(2, 'emilia@mail'),
(1, 'monika@mail'),
(2, 'monika@mail'),
(1, 'grzegorz@mail'),
(2, 'grzegorz@mail');

INSERT INTO zamowione_gry VALUES
(1, 1),
(1, 4),
(1, 8),
(1, 7),
(2, 5),
(3, 18),
(3, 17),
(4, 15),
(4, 16),
(5, 2),
(6, 1),
(7, 14),
(8, 3),
(9, 14),
(9, 2),
(9, 1);

INSERT INTO zwrocone_gry VALUES
(1, 1),
(2, 5), 
(3, 1);

INSERT INTO ocena VALUES
('karol@mail', 4, 'w porzadku'),
('karol@mail', 5, 'Genialna'),
('karol@mail', 8, 'Niesamowita'),
('emilia@mail', 1, 'W sumie to mi sie nie spodobala');