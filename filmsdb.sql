CREATE DATABASE filmdb;
USE filmdb;

--
-- Table structure for table `film`
--
CREATE TABLE `film` (
    `id` INT NOT NULL UNIQUE AUTO_INCREMENT,
    `titre` varchar(255) NOT NULL,
    `date_sortie` INT,
    `img` longtext NOT NULL,
    `synopsis` longtext,
    CONSTRAINT pk_film PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Table structure for table `comment`
--
CREATE TABLE `comment` (
    `id` INT NOT NULL UNIQUE AUTO_INCREMENT,
    `movie_id` INT NOT NULL,
    `email` varchar(255) NOT NULL,
    `pseudo` varchar(255) NOT NULL,
    `commentaire` longtext NOT NULL,
    CONSTRAINT pk_comment PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

--
-- Table structure for table `user`
--
CREATE TABLE `user` (
    `id` INT NOT NULL UNIQUE AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    CONSTRAINT pk_user PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE `comment`
    ADD CONSTRAINT fk_comment_film FOREIGN KEY (`movie_id`) REFERENCES `film` (`id`);

INSERT INTO `user` (`username`, `password`) VALUES ('nanard', '$2y$10$MKLuxzHSD5kkkoOPJPLeSuwOaku1sRbhngQxj/bSaxMvZO6jrNC..');

--
-- Dumping data for table `games`
--
INSERT INTO
  `film` (`titre`, `date_sortie`, `img`, `synopsis`)
VALUES
  (
    'Jaguar Force',
    2008,
    '5e8b56057aaac-jaguar-force-jaquette.jpg',
    'Suite à une série de règlement de compte visant à conquérir le marché de la drogue, la police de Hong Kong fait appel à un spécialiste du crime organisé Chin Yung. L’opération Jaguar débute…'
  ),
  (
    'Hitman le Cobra',
    2007,
    '5e8b55dcf205a-hitman-le-cobra-jaquette.jpg',
    'Après une course effrénée (filmée en mettant bout à bout trois fois le même plan), Phillip (Richard Harrison) tue Roger, ayant vendu des informations aux Japonais. Mike (Mike Abbott), le frère de Roger, veut se venger de Phillip. Il envoie Bob, Blackie (Nathan Mutanda Chukueke) et un autre sbire retrouver Phillip. Ce dernier, rompu à l’utilisation des armes à feu, tue le personnage sans nom, puis Blackie et encore un autre non-identifié. Enfin, Phillip abat rapidement Bob.'
  ),
  (
    'Mars Needs Women',
    1968,
    'Mars_Needs_Women_FilmPoster.jpeg',
    'La gent féminine se faisant rare sur la Planète Mars, une expédition scientifique composé de cinq martiens est dépêchée sur Terre afin de ramener cinq jeunes femmes pour tenter de remédier à la situation. Une approche pacifique auprès des autorités américaines échoue, les martiens décident alors de se fondre parmi la population terrienne et de choisir leurs cibles, ce sera une strip-teaseuse, une hôtesse de l’air, une reine de beauté et une artiste peintre. Le chef de la délégation, Dop va tomber amoureux de sa cible, l’exobiologiste Marjorie Bolen.'
  ),
  (
    'Piège Mortel à Hawaï',
    1987,
    '5f9540700a949-piegemortelahawai.jpg',
    'À Hawaï, un agent infiltré de la DEA et son ami civil tombent sur une opération de trafic de drogue et doivent demander l’aide de tous leurs collègues pour s’attaquer au vicieux baron de la drogue.'
  ),
  (
    'New York Ninja',
    2021,
    '61ed1be4d6c39-1910310.jpg',
    'Un technicien du son d’une chaîne d’information devient un justicier ninja à New York après le meurtre de sa femme enceinte.'
  ),
  (
    'L’homme qui sauva le monde',
    1982,
    '5e8b55634dbea-turkish-star-wars-jaquette.jpg',
    'Deux cadets de l’espace s’écrasent sur une planète désertique, où un sorcier maléfique cherche le pouvoir ultime pour conquérir le monde. Bien que le film emprunte des images de fond à Star Wars, l’intrigue est pour la plupart sans rapport.'
  ),
  (
      'Crocodile Fury',
      1988,
      '5e8b555530228-crocodile-fury-jaquette.jpg',
      'Dans un petit village de Thailande, un monstrueux crocodile dévore un à un les habitants...'
  ),
  (
      'Kill for Love',
      2009,
      '5e8b564662848-kill-for-love-jaquette.jpg',
      'Gaspard De La Roche est retrouvé mort dans son château, laissant un héritage colossal de 50 millions. Est-ce un accident ou un meurtre ? Sa jeune et jolie femme, seule héritière, est à son tour en danger. Quelqu’un semble prêt à tout, même à tuer, pour s’emparer de l’immense fortune.'
  ),
  (
      'Baaghi 3',
      2020,
      '6252c75b87af2-affiche.jpg',
      'Anveer "Ronnie" Charan Chaturvedi vit avec son frère aîné Vikram Charan Chaturvedi. Ronnie a été protecteur envers lui depuis son enfance, surtout après la mort de leur père Charan Chaturvedi. Ronnie se voit proposer un emploi dans la police, mais refuse car il a 33 affaires enregistrées contre lui, toutes pour avoir sauvé Vikram et le convainc d’accepter le poste. Un Vikram timide et réticent devient flic.'
  ),
  (
      'Black Cobra',
      1987,
      'MV5BMjA2OTMxNDQ1MV5BMl5BanBnXkFtZTcwNDExNTIyMQ@@._V1_.jpg',
      'Un flic solitaire se bat pour protéger une femme photographe d’un gang de motards psychopathes.'
  ),
  (
      'Commando Massacre',
      1986,
      '5e8b55751a8dd-commando-massacre-jaquette.jpg',
      'Un lieutenant américain est transformé en cobaye pour des expériences meurtrières du KGB.'
  ),
  (
      'White Fire',
      1984,
      '5e8b555370fff-white-fire-jaquette.jpg',
      'Deux frères et sœurs russes vivant à Istanbul, en Turquie, qui travaillent dans le secteur des clôtures en diamant, complotent pour voler le diamant légendaire récemment découvert White Fire, mais leurs rivaux ont d’autres plans en tête.'
  );
