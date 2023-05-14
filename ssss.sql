create table Etablissement(
id    INTEGER  primary key not null ,
code  INTEGER  unique not null ,
Nom   varchar(100) not null,
Telephone varchar(14),
Faxe      varchar(30),
ville    varchar(30),
Nbr_enseignants  INTEGER);

ALTER COLUMN Nbr_enseignants SET DEFAULT 0;
ALTER COLUMN Ville SET DEFAULT 'TANGER';

create table Grade(
id_Grade    INTEGER  primary key not null ,
designation varchar(100) not null,
charge_statutaire INTEGER ,
Taux_horaire_vacation INTEGER );

-------------------------
create table Enseignant(
id              INTEGER  primary key not null ,
PPR             varchar(100)  not null Unique,
Nom             varchar(200)  not null,
prenom          varchar(200) not null,
Date_Naissance  date,
Etablissement   INTEGER references etablissement(id) ,
id_Grade        INTEGER references Grade(id_Grade) ,
id_User         Integer references Users(id_User)
);

create table Users(
id_User    INTEGER  primary key not null  ,
email      varchar(100) not null Unique,
password   varchar(100) not null ,
Type  varchar(30) not null);
------------------------

create table Intervention(
id_Intervention    INTEGER  primary key not null  ,
id_Intervenant     INTEGER  references enseignant (id),
id_Etab            INTEGER references etablissement(id) ,
Intitule_intervention varchar(30) ,
Annee_univ        INTEGER,
Semestre          char(2),
Date_debut        date ,
Date_fin          date ,
Nbr_heures        INTEGER );

ALTER Table Intervention
add constraint nbr_positif  check (Nbr_heures >= 0);

ALTER Table Etablissement
add constraint nbr_ens_positif  check (Nbr_enseignants >= 0);

ALTER COLUMN Nbr_heures SET DEFAULT 0;



create table Paiement(
id                 INTEGER  primary key not null  ,
id_Intervenant     INTEGER  references enseignant (id),
id_etab            INTEGER references etablissement(id) ,
VH                 INTEGER   ,
Taux_H             INTEGER,
Brut               INTEGER,
IR                 INTEGER ,
Net                INTEGER,
Annee_Univ         INTEGER,
Semestre           char(2));

------------------------
ALTER Table Intervention
add constraint annee_uv  check (Annee_univ >= 1989 and Annee_univ<9999);


ALTER Table Paiement
add constraint annee_uv  check (Annee_univ >= 1989 and Annee_univ<9999);
----------------------------
ALTER Table Intervention
add constraint sms  check ( Semestre in ('S1' ,'S2' ,'S3' , 'S4' ));
ALTER Table Paiement
add constraint sms  check ( Semestre in ('S1' ,'S2' ,'S3' , 'S4' ));

ALTER Table Users
add constraint typ  check (upper(type) in ('ENSEIGNANT','ADMINISTRATEUR','PRESIDENT'));
create table Administrateur(
id              INTEGER  primary key not null ,
PPR             varchar(100)  not null Unique,
Nom             varchar(200)  not null,
prenom          varchar(200) not null,
Etablissement   INTEGER references etablissement(id) ,
id_User         Integer references Users(id_User)
);
ALTER TABLE Intervention
  ADD COLUMN Visa_etb INTEGER default 0,
ADD COLUMN Visa_uae  INTEGER default 0 ;




------------------peuplement---

insert into etablissement
 values (01,01,'Faculté des lettres et des sciences humaines'
,'+212539979053','+212539979128','Martil');

insert into etablissement
 values (02,02,'Faculté des sciences'
,'+212539996432','+212539994500','Tétouan');
insert into etablissement
values (03,03,'Ecole supérieur Roi Fahd de traduction'
,'+212539942813','+212539940835');

insert into etablissement
values (04,04,'Ecole normale supérieure'
,'+212539942813','+212539940835','Martil');

insert into etablissement
values (05,05,'Ecole nationalede commerce et de gestion de Tanger'
,'+212539313487','+212539313488');

insert into etablissement
values (06,06,'Faculté des sciences juridiques économiques et sociales'
,'+212 539687086','');
insert into etablissement
values (07,07,'Faculté des sciences et techniques'
,'+212539393954','+212539393953');

insert into etablissement
values (08,08,'Ecole nationale des sciences appliquées'
,'+212539393744','');


insert into etablissement
values (09,09,'Ecole nationale des sciences appliquées'
,'+212539688027','','Tétouan');

insert into etablissement
values (10,10,'Ecole nationale des sciences appliquées'
,'+212539805712','+212539805713','AL-Hoceima');

insert into etablissement
values (11,11,'Faculté polydisciplinaire'
,'+212539523960','+212539523961','Larache');

insert into etablissementvalues (12,12,'Faculté des sciences et techniques'
,'+212539807172','+212539807173','AL-Hoceima');



insert into etablissement
values (13,13,'Faculté de médecine et de pharmacie'
,'+212 674895164','');

insert into etablissement
values (14,14,'Faculté Ossoul Eddine'
,'+212 539971107','+212539973969','Tetouan');


insert into etablissement
values (14,14,'Faculté des sciences juridiques économiques et sociales'
,'+212 539687086','','Tetouan-Martil');

