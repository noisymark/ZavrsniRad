# /Applications/XAMPP/xamppfiles/bin/./mysql -uroot --default_character_set=utf8 < "/Users/marko/Documents/ZavrsniRad/ZavrsniRad/SQL/BlueFreedom.sql"
# THIS IS A COMMAND SPECIFIC FOR MY MACBOOK WHERE I CODE ON, PLEASE CHANGE THE COMMAND ACCORDINGLY TO YOUR OS AND WORK PATH


# ADDING "AUTOMATIC UPDATE" FUNCTION SO WE CAN EASILY OVERWRITE THE OLD DATABASE IF IT DOES EXIST

drop database if exists BlueFreedom;
create database BlueFreedom;
use BlueFreedom;

# CREATING TABLES

create table objava(
    sifra int not null primary key auto_increment,
    naslov varchar(50) not null,
    upis varchar(250) not null,
    vrijemeizrade datetime not null,
    ipadresa varchar(20) not null,
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
    ime varchar(10) not null,
    prezime varchar(25) not null,
    datumrodenja datetime,
    radnomjesto varchar(50),
    skolovanje varchar(50),
    mjesto varchar(25),
    email varchar(50) not null,
    lozinka varchar(20) not null,
    brojtel int(15),
    slika varchar(100),
    administrator boolean not null,
    stanje boolean not null
);

create table prijateljstvo(
	sifra int not null primary key auto_increment,
	osoba1 int,
	osoba2 int,
	prijatelji boolean
);

# ADDING ENTITY RELATIONSHIPS

alter table objava add foreign key (osoba) references osoba(sifra);

alter table komentar add foreign key (objava) references objava(sifra);
alter table komentar add foreign key (osoba) references osoba(sifra);

alter table svidamise_komentar add foreign key (komentar) references komentar(sifra);

alter table svidamise add foreign key (objava) references objava(sifra);
alter table svidamise add foreign key (osoba) references osoba(sifra);

alter table svidamise_komentar add foreign key (osoba) references osoba(sifra);

alter table prijateljstvo add foreign key (osoba1) references osoba(sifra);
alter table prijateljstvo add foreign key (osoba2) references osoba(sifra);

# SAMPLE INSERT INTO osoba

insert into osoba(sifra,ime,prezime,datumrodenja,email,lozinka,administrator,stanje)
values
(null,'Marko','Pavlović','2001-02-09','markopavlovic316@gmail.com','AA22BB33',True,True),
(null,'Rebeka','Ivanković','2002-04-08','rebeka.ivankovic33@gmail.com','CC44DD55',False,True),
(null,'Valentin','Mijatović','2000-02-07','valentin.mijau22@gmail.com','FFFF0000',False,True);

# SAMPLE INSERT INTO prijateljstvo

insert into prijateljstvo(sifra,osoba1,osoba2,prijatelji)
values
(null,1,2,True),
(null,3,1,False),
(null,3,2,True);

# THIS IS A COMMAND TO SHOW "FRIEND LIST" OF THE PERSON WITH THE ID(SIFRA) THAT IS 1 (IN THIS EXAMPLE ME, MARKO)

# select * from prijateljstvo where (osoba1 = 1 or osoba2 = 1) and prijatelji = 1;

# SAMPLE INSERT INTO objava

insert into objava(sifra,naslov,upis,vrijemeizrade,ipadresa,slika,osoba)
values
(null,'Hello','This is my first post on the BlueFreedom social network.','2022-12-01 18:45:22','192.168.1.1','/Users/3/postimg/001.png',3),
(null,'Hello','This is my first post on the BlueFreedom social network.','2022-12-02 12:42:11','192.168.2.5','/Users/2/postimg/001.png',2),
(null,'Hello','This is my first post on the BlueFreedom social network.','2022-12-04 12:33:33','192.168.3.4','/Users/1/postimg/001.png',1);

# SAMPLE INSERT INTO komentar

insert into komentar (sifra,vrijemekomentiranja,opis,objava,osoba)
values
(null,'2022-12-07 14:00:00','Welcome to the community',1,1),
(null,'2022-12-08 13:00:00','Have fun',2,3),
(null,'2022-12-08 15:00:00','I''m buying bitcoin on btccheap.spam',3,2);

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
