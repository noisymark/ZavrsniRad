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
    stanje boolean not null,
    aktivan boolean not null,
    uniqueid varchar(255)
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

insert into osoba(sifra,ime,prezime,datumrodenja,email,lozinka,administrator,stanje,aktivan)
values
(null,'Marko','Pavlović','2001-02-09','markopavlovic316@gmail.com','$2y$10$raIEitAaxTkfaZT4ZGONqOTXlx/bmtT/Du6XOc0TcNRU1rW93bYJO',True,True,True);
