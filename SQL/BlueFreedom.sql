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
    objava int,
    osoba int
);

create table svidamise_komentar(
    sifra int not null primary key auto_increment,
    vrijemesvidanja datetime,
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


alter table objava add foreign key (osoba) references osoba(sifra);

alter table komentar add foreign key (objava) references objava(sifra);
alter table komentar add foreign key (osoba) references osoba(sifra);

alter table svidamise add foreign key (objava) references objava(sifra);
alter table svidamise add foreign key (osoba) references osoba(sifra);

alter table svidamise_komentar add foreign key (osoba) references osoba(sifra);

create table prijateljstvo(
	sifra int not null primary key auto_increment,
	osoba1 int,
	osoba2 int,
	prijatelji boolean
);

alter table prijateljstvo add foreign key (osoba1) references osoba(sifra);
alter table prijateljstvo add foreign key (osoba2) references osoba(sifra);

select * from osoba;

insert into osoba(sifra,ime,prezime,datumrodenja,email,lozinka,administrator)
values
(null,'Marko','Pavlović','2001-02-09','markopavlovic316@gmail.com','AA22BB33',True),
(null,'Rebeka','Ivanković','2002-04-08','rebeka.ivankovic33@gmail.com','CC44DD55',False),
(null,'Valentin','Mijatović','2000-02-07','valentin.mijau22@gmail.com','FFFF0000',False);

select * from prijateljstvo;

select * from osoba;

insert into prijateljstvo(sifra,osoba1,osoba2,prijatelji)
values
(null,1,2,True),
(null,3,1,False),
(null,3,2,True);

# Ovako ce se prikazivati lista svih prijatelja osobe koja je prijavljena na mrezu

select * from prijateljstvo where (osoba1 = 1 or osoba2 = 1) and prijatelji = 1;