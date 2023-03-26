# /Applications/XAMPP/xamppfiles/bin/./mysql -uroot --default_character_set=utf8 < "/Users/marko/Documents/ZavrsniRad/ZavrsniRad/SQL/BlueFreedom.sql"
# THIS IS A COMMAND SPECIFIC FOR MY MACBOOK WHERE I CODE ON, PLEASE CHANGE THE COMMAND ACCORDINGLY TO YOUR OS AND WORK PATH
# c:\xampp\mysql\bin\mysql -uroot --default_character_set=utf8 < "C:\Users\P\Documents\ZavrsniRad\SQL\BlueFreedom.sql"


# ADDING "AUTOMATIC UPDATE" FUNCTION SO WE CAN EASILY OVERWRITE THE OLD DATABASE IF IT DOES EXIST

drop database if exists BlueFreedom;
create database BlueFreedom default charset utf8mb4;
use BlueFreedom;

# CREATING TABLES

create table operater(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    email varchar(50) not null,
    lozinka char(60) not null,
    uloga varchar(20) not null
);

insert into operater (ime,prezime,email,lozinka,uloga)
values ('Edunova','Operater','oper@edunova.hr',
'$2y$10$m8IvoBWyKZSqv149xoB//eEd/nPB56JGlRYM0Vann7X2cPUMKvXc2',
'oper'
);

insert into operater (ime,prezime,email,lozinka,uloga)
values ('Admin','Operater','admin@edunova.hr',
'$2y$10$m8IvoBWyKZSqv149xoB//eEd/nPB56JGlRYM0Vann7X2cPUMKvXc2',
'admin'
);


create table objava(
    sifra int not null primary key auto_increment,
    naslov varchar(50) not null,
    upis varchar(250) not null,
    vrijemeizrade datetime not null,
    ipadresa varchar(20),
    slika varchar(100),
    osoba int
);

create table svidamise(
    sifra int not null primary key auto_increment,
    vrijemesvidanja datetime not null,
    objava int,
    osoba int
);

create table komentar(
    sifra int not null primary key auto_increment,
    vrijemekomentiranja datetime not null,
    opis varchar(250),
    objava int,
    osoba int
);

create table svidamise_komentar(
    sifra int not null primary key auto_increment,
    vrijemesvidanja datetime,
    svidanje boolean not null,
    osoba int,
    komentar int
);

create table osoba(
    sifra int not null primary key auto_increment,
    ime varchar(25) not null,
    prezime varchar(25) not null,
    datumrodenja datetime,
    email varchar(50) not null,
    lozinka varchar(60) not null,
    brojtel int(15),
    slika varchar(100),
    administrator boolean not null,
    stanje boolean not null
);


# ADDING ENTITY RELATIONSHIPS

alter table objava add foreign key (osoba) references osoba(sifra) on delete cascade;

alter table komentar add foreign key (objava) references objava(sifra) on delete cascade;
alter table komentar add foreign key (osoba) references osoba(sifra) on delete cascade;

alter table svidamise_komentar add foreign key (komentar) references komentar(sifra) on delete cascade;

alter table svidamise add foreign key (objava) references objava(sifra) on delete cascade;
alter table svidamise add foreign key (osoba) references osoba(sifra) on delete cascade;

alter table svidamise_komentar add foreign key (osoba) references osoba(sifra) on delete cascade;

# SAMPLE INSERT INTO osoba

insert into osoba(sifra,ime,prezime,datumrodenja,email,lozinka,administrator,stanje)
values
(null,'Marko','Pavlović','2001-02-09','markopavlovic316@gmail.com','$2y$10$raIEitAaxTkfaZT4ZGONqOTXlx/bmtT/Du6XOc0TcNRU1rW93bYJO',True,True),
(null,'Rebeka','Ivanković','2002-04-08','rebeka.ivankovic33@gmail.com','$2y$10$raIEitAaxTkfaZT4ZGONqOTXlx/bmtT/Du6XOc0TcNRU1rW93bYJO',False,True),
(null,'Test','Test','2000-01-01','test@test.hr','$2y$10$raIEitAaxTkfaZT4ZGONqOTXlx/bmtT/Du6XOc0TcNRU1rW93bYJO',False,True),
(null,'Valentin','Mijatović','2000-02-07','valentin.mijau22@gmail.com','$2y$10$raIEitAaxTkfaZT4ZGONqOTXlx/bmtT/Du6XOc0TcNRU1rW93bYJO',False,True);

# SAMPLE INSERT INTO objava

insert into objava(sifra,naslov,upis,vrijemeizrade,ipadresa,slika,osoba)
values
(null,'Hello','This is Test Test user.','2022-12-01 18:45:22','192.168.1.1','/Users/3/postimg/001.png',3),
(null,'Hello','This is user Rebeka.','2022-12-02 12:42:11','192.168.2.5','/Users/2/postimg/001.png',2),
(null,'Hello','This is user Marko.','2022-12-04 12:33:33','192.168.3.4','/Users/1/postimg/001.png',1);

# SAMPLE INSERT INTO komentar

insert into komentar (sifra,vrijemekomentiranja,opis,objava,osoba)
values
(null,'2022-12-07 14:00:00','Welcome to the community',1,1),
(null,'2022-12-07 14:00:00','Welcome to the community',1,2),
(null,'2022-12-07 14:00:00','Welcome to the community',1,3),
(null,'2022-12-08 13:00:00','Have fun',2,3),
(null,'2022-12-08 13:00:00','Have fun',2,2),
(null,'2022-12-08 13:00:00','Have fun',2,1),
(null,'2022-12-08 15:00:00','I''m buying bitcoin on btccheap.spam',3,2),
(null,'2022-12-08 15:00:00','I''m buying bitcoin on btccheap.spam',3,1),
(null,'2022-12-08 15:00:00','I''m buying bitcoin on btccheap.spam',3,3);

# SAMPLE INSERT INTO svidamise

insert into svidamise (sifra,vrijemesvidanja,objava,osoba)
values
(null,'2022-12-08 18:29:00',1,1),
(null,'2022-12-08 18:29:00',1,2),
(null,'2022-12-08 18:29:00',1,3),
(null,'2022-12-08 18:29:00',2,1),
(null,'2022-12-08 18:29:00',2,2),
(null,'2022-12-08 18:29:00',2,3),
(null,'2022-12-08 18:29:00',3,1),
(null,'2022-12-08 18:29:00',3,2),
(null,'2022-12-08 18:29:00',3,3);

# SAMPLE INSERT INTO svidamise_komentar 

insert into svidamise_komentar (sifra,vrijemesvidanja,svidanje,osoba,komentar)
values
(null,'2022-12-08 21:00:00',1,1,1),
(null,'2022-12-08 21:00:00',1,3,2),
(null,'2022-12-08 21:00:00',1,2,3);
