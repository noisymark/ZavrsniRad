# /Applications/XAMPP/xamppfiles/bin/./mysql -uroot --default_character_set=utf8 < "/Users/marko/Documents/ZavrsniRad/ZavrsniRad/SQL/BlueFreedom.sql"

drop database if exists BlueFreedom;
create database BlueFreedom;
use BlueFreedom;

create table objava(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    opis text not null,
    vrijemeizrade datetime not null,
    ipadresa int(12) not null,
    slika varchar(100),
    svidamise int,
    komentar int,
    osoba int
);

create table svidamise(
    sifra int not null primary key auto_increment,
    vrijemesvidanja datetime not null,
    osoba int
);

create table komentar(
    sifra int not null primary key auto_increment,
    vrijemekomentiranja datetime not null,
    osoba int
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
    administrator boolean not null
);

alter table objava add foreign key (svidamise) references svidamise(sifra);
alter table objava add foreign key (komentar) references komentar(sifra);
alter table objava add foreign key (osoba) references osoba(sifra);

alter table svidamise add foreign key (osoba) references osoba(sifra);
alter table komentar add foreign key (osoba) references osoba(sifra);