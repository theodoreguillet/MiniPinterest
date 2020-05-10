INSERT INTO User (`email`,`pwd`,`pseudo`,`rights`) VALUES ('admin@corp.com','21232f297a57a5a743894a0e4a801fc3','Admin','ADMIN'); -- pwd: 'admin'
INSERT INTO User (`email`,`pwd`,`pseudo`,`rights`) VALUES ('theodoreguillet@gmx.fr','5f4dcc3b5aa765d61d8327deb882cf99','Theodore','USER'); -- pwd: 'password'
INSERT INTO User (`email`,`pwd`,`pseudo`,`rights`) VALUES ('toto@tata.com','5f4dcc3b5aa765d61d8327deb882cf99','Toto','USER'); -- pwd: 'password'

INSERT INTO Theme (`name`,`userId`) VALUES ('Maison','1');
INSERT INTO Theme (`name`,`userId`) VALUES ('Maison','2');
INSERT INTO Theme (`name`,`userId`) VALUES ('Jardin','1');
INSERT INTO Theme (`name`,`userId`) VALUES ('Anime','2');
INSERT INTO Theme (`name`,`userId`) VALUES ('Chiens','2');
INSERT INTO Theme (`name`,`userId`) VALUES ('Chats','2');
INSERT INTO Theme (`name`,`userId`) VALUES ('Dessin','2');
INSERT INTO Theme (`name`,`userId`) VALUES ('Photos','2');

INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic1','pic1.png','test','1');
INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic2','pic2.png','test','1');
INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic3','pic3.png','test','1');
INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic4','pic4.png','test','2');
INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic5','pic5.png','test','3');
INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES ('pic6','pic6.png','test','3');
