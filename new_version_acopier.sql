[21:28, 13/06/2023] +212 674-566170: CREATE OR REPLACE FUNCTION check_unique_president()
RETURNS TRIGGER AS $$
DECLARE
    total INTEGER;
BEGIN
    IF NEW.type = 'ADMINISTRATEUR_UNIV' or NEW.type ='PRESIDENT'  THEN
        SELECT COUNT(*) INTO total FROM users WHERE type = New.type;

        IF total > 0 THEN
            RAISE EXCEPTION 'There can be only one type of this  user in UNIVERSITY ABMS.';
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER unique_president
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION check_unique_president();


----------------------------------
----------------------------------
---------pour etre sur que instruction delete ne vient pas a ma base de donne j'ai developper un trriger
CREATE OR REPLACE FUNCTION bloqu…
[22:54, 13/06/2023] +212 674-566170: CREATE OR REPLACE FUNCTION check_unique_president()
RETURNS TRIGGER AS $$
DECLARE
    total INTEGER;
BEGIN
    IF NEW.type = 'ADMINISTRATEUR_UNIV' or NEW.type ='PRESIDENT'  THEN
        SELECT COUNT(*) INTO total FROM users WHERE type = New.type;

        IF total > 0 THEN
            RAISE EXCEPTION 'There can be only one type of this  user in UNIVERSITY ABMS.';
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER unique_president
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION check_unique_president();


----------------------------------
----------------------------------
---------pour etre sur que instruction delete ne vient pas a ma base de donne j'ai developper un trriger
CREATE OR REPLACE FUNCTION bloque_suppression_enseignant()
RETURNS TRIGGER AS $$
BEGIN
    RAISE EXCEPTION 'La supppression d''un enseignant est interdite.';
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER bloque_suppression_enseignant_trigger
BEFORE DELETE ON enseignants
FOR EACH ROW
EXECUTE FUNCTION bloque_suppression_enseignant();



delete from enseignants where id = 10;
--------------- impossible de valide visa_uae si visa_etb =false
CREATE OR REPLACE FUNCTION check_visa_etb()
RETURNS TRIGGER AS $$
BEGIN
  IF (NEW.Visa_uae) AND (SELECT Visa_etb FROM interventions  WHERE id = NEW.id) <> TRUE THEN
    RAISE EXCEPTION ' l''intervention n''est pas encore  validé par le directeur d''etablissement';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;
---------------------------------------------------
-- CREATE OR REPLACE FUNCTION Not_update_visaetb()
-- RETURNS TRIGGER AS $$
-- BEGIN
--   IF (old.Visa_etb=1 and NEW.Visa_etb=0) AND (SELECT Visa_uae FROM intervention  WHERE id_Intervention = NEW.id_Intervention) <> False THEN
--     RAISE EXCEPTION ' l''intervention est   validé par le president veuillez le contacter  ';
--   END IF;

--   RETURN NEW;
-- END;
-- $$ LANGUAGE plpgsql;

-- CREATE or replace TRIGGER Trg_update_visa_etb
-- BEFORE UPDATE ON interventions FOR EACH ROW
-- EXECUTE FUNCTION Not_update_visaetb();
-------------------
CREATE OR REPLACE FUNCTION forbiden_insert_intervention_etatinactif()
RETURNS TRIGGER AS $$
DECLARE
   actif boolean;
   

BEGIN
SELECT etat  into actif FROM enseignants  
  WHERE id = NEW.id_intervenant;
  IF  actif=false  THEN
    RAISE EXCEPTION ' l''intervention  impossible  de s''ajouter ou se modifier enseignant est inactif  ';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER Trg_update_etat
BEFORE UPDATE or insert  ON interventions FOR EACH ROW
EXECUTE FUNCTION forbiden_insert_intervention_etatinactif();
------------version 2

---------------
-----------
CREATE OR REPLACE FUNCTION Not_update_ifvisaetb()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.Visa_uae  and  NEW.Visa_uae = OLD.Visa_uae THEN
        RAISE EXCEPTION 'La modification ou la suppression de l''intervention est bloquée car le visa UAE est déjà validé ';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

Drop trigger Trg_update_visa_etb on interventions;
Drop  FUNCTION Not_update_ifvisaetb ;
CREATE or replace TRIGGER Trg_update_visa_etb
BEFORE UPDATE or delete ON interventions
FOR EACH ROW
EXECUTE FUNCTION Not_update_ifvisaetb();
-----------------------------------------------------
----------------------------------------------------si l'intervention est valider par etablissement impossible la modification
CREATE OR REPLACE FUNCTION stop_modification_directeurdecided()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.Visa_etb = true  and  NEW.Visa_etb = OLD.Visa_etb and NEW.Visa_uae=false and NEW.Visa_uae=old.Visa_uae THEN 
        RAISE EXCEPTION 'La modification ou la suppression de l''intervention est bloquée car le visa ETB est déjà validé.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_stop_modification
BEFORE UPDATE OR DELETE ON interventions
FOR EACH ROW
EXECUTE FUNCTION stop_modification_directeurdecided();

----------------------------------------------------

-----------aucune modification je veux sure cette table si le president a pris sa decision
CREATE OR REPLACE FUNCTION Not_update_ifvisaetb()
RETURNS TRIGGER AS $$
BEGIN
if old.visa_etb<>new.visa_etb then

    IF (SELECT Visa_uae FROM interventions WHERE id_Intervention = NEW.id_Intervention) = 1 THEN
        RAISE EXCEPTION 'L''intervention est validé par le président, impossible d''appliquer aucune modification. Veuillez le contacter.';
    END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER Trg_update_visa_etb
BEFORE UPDATE OR DELETE ON interventions
FOR EACH ROW
EXECUTE FUNCTION Not_update_ifvisaetb();
----- trigger de sensibilite du nombres d'enseignant de la table etablissement 
CREATE OR REPLACE FUNCTION update_nbr_enseignant()
RETURNS TRIGGER AS $$
DECLARE
   Nbr_old int;
   Nbr_new int;
BEGIN
   IF TG_OP = 'INSERT' THEN
       SELECT Nbr_enseignants INTO Nbr_new
       FROM etablissements WHERE id = NEW.Etablissement;

       Nbr_new := Nbr_new + 1;

       UPDATE Etablissement
       SET Nbr_enseignants = Nbr_new
       WHERE id = NEW.Etablissement;

       RETURN NEW;



   ELSIF TG_OP = 'UPDATE' THEN
      IF ( OLD.etat=true and  NEW.etat=false ) 
      THEN 
             SELECT Nbr_enseignants INTO Nbr_old
             FROM etablissements WHERE id = OLD.Etablissement;
             Nbr_old := Nbr_old - 1;
             UPDATE Etablissements
             SET Nbr_enseignants = Nbr_old
             WHERE id = OLD.Etablissement; 
      
      end if;
      IF ( OLD.etat=false and  NEW.etat=true ) 
      THEN 
             SELECT Nbr_enseignants INTO Nbr_old
             FROM Etablissements WHERE id = OLD.Etablissement;
             Nbr_old := Nbr_old + 1;
             UPDATE Etablissements
             SET Nbr_enseignants = Nbr_old
             WHERE id = OLD.Etablissement; 
      
      end if;
       IF OLD.Etablissement <> NEW.Etablissement   THEN
           SELECT Nbr_enseignants INTO Nbr_old
           FROM Etablissements WHERE id = OLD.Etablissement;

           Nbr_old := Nbr_old - 1;
            
           UPDATE Etablissements
           SET Nbr_enseignants = Nbr_old
           WHERE id = OLD.Etablissement;

           SELECT Nbr_enseignants INTO Nbr_new
           FROM Etablissements WHERE id = NEW.Etablissement;

           Nbr_new := Nbr_new + 1;

           UPDATE Etablissements
           SET Nbr_enseignants = Nbr_new
           WHERE id = NEW.Etablissement;
       END IF;

       RETURN NEW;
   END IF;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER TRG_enseignant
after INSERT OR UPDATE  ON enseignants
FOR EACH ROW  -----trigger par ligne car je veux qu'il se declenche pour chaque instruction 
EXECUTE FUNCTION update_nbr_enseignant();
----------------------------

----------------------------------delete from table user after deleting from enseignat
---------------------------------ATTENTION NE L'implemente pas jusqua la validite par prof
-------------non car si je la fait je dois supprimer colone ok users et la mettre null---
-- CREATE OR REPLACE FUNCTION supprimer_utilisateur()
-- RETURNS TRIGGER AS $$
-- BEGIN
--    IF ( OLD.etat=true and  NEW.etat=false ) 
--       THEN 
--     DELETE FROM users WHERE id = OLD.id_User;
--     end if;
--     RETURN OLD;
-- END;
-- $$ LANGUAGE plpgsql;

-- CREATE TRIGGER supprimer_utilisateur_trigger
-- AFTER update  ON enseignants
-- FOR EACH ROW
-- EXECUTE FUNCTION supprimer_utilisateur();
------------------------------------------------------
----------------------------------------------

 
--------------------------------securise que id user n'est donne qu'au plus une seul personne
--- teste de securite
CREATE OR REPLACE FUNCTION verifier_user_id()
RETURNS TRIGGER AS $$
DECLARE
    user_count INTEGER;
BEGIN
if new.id_user<>old.id_user 
THEN

    
        SELECT COUNT(*) INTO user_count FROM enseignants WHERE id_User = NEW.id_User;
        
        IF user_count > 0 THEN
            RAISE EXCEPTION 'mot de passe et email déjà utilisés';
        END IF;
    end if;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER verifier_user_id_trigger
BEFORE INSERT OR UPDATE ON enseignants
FOR EACH ROW
EXECUTE FUNCTION verifier_user_id();


--------------------paiements

-------------------- fonction de calcule brut

CREATE OR REPLACE FUNCTION calcule_brut(VH NUMERIC, Taux_H NUMERIC)
RETURNS NUMERIC AS $$
BEGIN
    RETURN (VH * Taux_H);
END;
$$ LANGUAGE plpgsql;


---------------------fonction de calcule net

CREATE OR REPLACE FUNCTION calcule_net(brut NUMERIC, IR NUMERIC)
RETURNS NUMERIC AS $$
BEGIN
    RETURN (brut - (IR * brut * 0.01));
END;
$$ LANGUAGE plpgsql;


---------------


---------------------------trigger de deletion de paiement 







--------------------------------



alter TABLe interventions disable  trigger all;

ALTER Table interventions
add constraint nbr_positif  check (Nbr_heures >= 0);

ALTER Table etablissements
add constraint nbr_ens_positif  check (Nbr_enseignants >= 0);


ALTER COLUMN Nbr_heures SET DEFAULT 0;

ALTER Table interventions
add constraint annee_uv  check (Annee_univ >= 1989 and Annee_univ<9999);

ALTER Table paiements
add constraint annee_uviv  check (Annee_univ >= 1989 and Annee_univ<9999);


ALTER Table interventions
add constraint sms  check ( Semestre in ('S1' ,'S2'));

ALTER Table paiements
add constraint smsPay  check ( Semestre in ('S1' ,'S2'));

ALTER Table Users
add constraint typ  check (upper(type) in ('ENSEIGNANT','ADMINISTRATEUR_ETA','PRESIDENT','ADMINISTRATEUR_UNIV','DIRECTEUR'));

ALTER TABLE enseignants
ALTER COLUMN id_User DROP NOT NULL;

ALTER TABLE administrateurs
ALTER COLUMN id_User DROP NOT NULL;

Insert into enseignants ( ppr , nom , prenom , date_naissance ,etablissement, id_grade , id_user ) values ('100' , 'benzema' , 'Kdidar' , '12-03-2002' ,14, 11 , 26);



-------------------------------------------
CREATE OR REPLACE FUNCTION free_a_user()
RETURNS TRIGGER AS $$
DECLARE
    id_u users.id%TYPE;
BEGIN
    IF NEW.etat = false AND OLD.etat = true THEN
        SELECT id_User INTO id_u FROM enseignants WHERE id = NEW.id;
        update enseignants set id_user=Null where id=New.id;
        DELETE FROM users WHERE id = id_u;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER free_a_user_trigger
after UPDATE ON enseignants
FOR EACH ROW
EXECUTE FUNCTION free_a_user();
--------------------------------
------------------------

CREATE OR REPLACE FUNCTION free_a_administratory()
RETURNS TRIGGER AS $$
DECLARE
    
BEGIN
        
        
        DELETE FROM users WHERE id = old.id_User;
        
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER free_a_admin_trigger
after delete ON Administrateurs
FOR EACH ROW
EXECUTE FUNCTION free_a_administratory();

Drop trigger free_a_admin_trigger on Administrateurs;


-------#################-------------

CREATE OR REPLACE FUNCTION calculate_salaire_brut(code_enseignant INTEGER, annee INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    salaire_brut NUMERIC;
BEGIN
    SELECT SUM(brut) INTO salaire_brut FROM paiements
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee;

    RAISE NOTICE 'Son salaire brut: %', salaire_brut;
    RETURN salaire_brut;
END;
$$ LANGUAGE plpgsql;
-----------------------------
CREATE OR REPLACE FUNCTION calculate_salaire_net(code_enseignant INTEGER, annee INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    salaire_net NUMERIC;
BEGIN
    SELECT SUM(net) INTO salaire_net FROM paiements
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee;

    RAISE NOTICE 'Son salaire net: %', salaire_brut;
    RETURN salaire_net;
END;
$$ LANGUAGE plpgsql;
------------------------
CREATE OR REPLACE FUNCTION calculate_salaire_totalnet(code_enseignant INTEGER, annee INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    volumehoraire NUMERIC;
    salaire NUMERIC;
    Ip NUMERIC;
    TXH NUMERIC;
BEGIN
    SELECT SUM(vh) INTO volumehoraire FROM paiements
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee;

    -- Adjust volumehoraire
    IF volumehoraire > 200 THEN
        volumehoraire := 200;
    END IF;
SELECT Taux_horaire_vacation INTO TXH
            FROM grades
            WHERE id = (SELECT id_Grade FROM enseignants WHERE id = code_enseignant);

    RAISE NOTICE 'Son nbr d''heures de vacation est : %', volumehoraire;

    salaire := public.calcule_net(public.calcule_brut(volumehoraire, TXH), 38);

    RAISE NOTICE 'Son salaire est : %', salaire;

    RETURN salaire;
END;
$$ LANGUAGE plpgsql;
----------------------------------------------------
CREATE OR REPLACE FUNCTION calculate_salaire_sup(code_enseignant INTEGER, annee INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    cod etablissements.id%type;
    volumehoraire NUMERIC;
    salaire NUMERIC;
    
    TXH NUMERIC;
BEGIN
 select etablissement into cod from enseignants where id=code_enseignant;
    SELECT SUM(vh) INTO volumehoraire FROM paiements
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee and id_Etab=cod;
   
    -- Adjust volumehoraire
    
SELECT Taux_horaire_vacation INTO TXH
            FROM grades
            WHERE id = (SELECT id_Grade FROM enseignants WHERE id = code_enseignant);

    RAISE NOTICE 'Son nbr d''heures de vacation est : %', volumehoraire;

    salaire := public.calcule_net(public.calcule_brut(volumehoraire, TXH), 38);

    RAISE NOTICE 'Son salaire est : %', salaire;

    RETURN salaire;
END;
$$ LANGUAGE plpgsql;
--------------------------------
-------------------------------
CREATE OR REPLACE FUNCTION calculate_salaire_vacation(code_enseignant INTEGER, annee INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    cod etablissements.id%type;
    volumehoraire NUMERIC;
    salaire NUMERIC;
    
    TXH NUMERIC;
BEGIN
 select etablissement into cod from enseignants where id=code_enseignant;
    SELECT SUM(vh) INTO volumehoraire FROM paiements
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee and id_Etab<>cod;
   
    -- Adjust volumehoraire
    
SELECT Taux_horaire_vacation INTO TXH
            FROM grades
            WHERE id = (SELECT id_Grade FROM enseignants WHERE id = code_enseignant);

    RAISE NOTICE 'Son nbr d''heures de vacation est : %', volumehoraire;

    salaire := public.calcule_net(public.calcule_brut(volumehoraire, TXH), 38);

    RAISE NOTICE 'Son salaire est : %', salaire;

    RETURN salaire;
END;
$$ LANGUAGE plpgsql;



---------###################

CREATE OR REPLACE FUNCTION update_paiement()
RETURNS TRIGGER AS $$
DECLARE
    existing_paiement paiements%ROWTYPE;
    grade_info grades%ROWTYPE;
    etab_exists BOOLEAN;
    same_etab BOOLEAN;
    VH1 INTEGER;
    total_hours INTEGER;
    montant_brut INTEGER;
    montant_net INTEGER;
BEGIN
    IF (old.visa_uae = false and NEW.Visa_uae= true ) THEN
        SELECT * INTO existing_paiement
        FROM paiements
        WHERE id_Intervenant = NEW.id_Intervenant
        AND id_etab = NEW.id_Etab
        AND Annee_Univ = NEW.Annee_univ
        AND Semestre = NEW.Semestre;

        IF FOUND THEN
        VH1 := existing_paiement.VH + NEW.Nbr_heures;

    
        UPDATE paiements
SET VH = VH1,
    Brut = existing_paiement.Brut + calcule_brut(NEW.Nbr_heures, existing_paiement.Taux_H),
    Net = existing_paiement.Net + calcule_net(calcule_brut(NEW.Nbr_heures, existing_paiement.Taux_H), existing_paiement.IR)
WHERE id = existing_paiement.id;


        ELSE
            SELECT * INTO grade_info
            FROM grades
            WHERE id = (SELECT id_Grade FROM enseignants WHERE id = NEW.id_Intervenant);

            SELECT Etablissement = NEW.id_etab INTO same_etab
            FROM enseignants
            WHERE id = NEW.id_Intervenant;


            IF (same_etab = FALSE) then
                INSERT INTO paiements (id_Intervenant, id_etab, VH, Taux_H, Brut, IR, Net, Annee_Univ, Semestre)
                VALUES (NEW.id_Intervenant, NEW.id_etab, NEW.Nbr_heures, grade_info.Taux_horaire_vacation,
                        calcule_brut(NEW.Nbr_heures, grade_info.Taux_horaire_vacation), 38,
                        calcule_net(calcule_brut(NEW.Nbr_heures, grade_info.Taux_horaire_vacation), 38),
                        NEW.Annee_univ, NEW.Semestre);
            ELSE
                SELECT SUM(Nbr_heures) INTO total_hours
                FROM interventions
                WHERE id_Intervenant = NEW.id_Intervenant
                AND id_Etab = NEW.id_Etab
                AND Annee_univ = NEW.Annee_univ
                and visa_uae=true;

                IF total_hours > grade_info.charge_statutaire THEN
                     SELECT * INTO existing_paiement
                     FROM paiements
                     WHERE id_Intervenant = NEW.id_Intervenant
                     AND id_etab = NEW.id_Etab
                     AND Annee_Univ = NEW.Annee_univ
                     AND Semestre = 'S1' or Semestre = 'S2';

                    IF FOUND THEN
                    total_hours := total_hours - grade_info.charge_statutaire-existing_paiement.VH;
                    ELSE
                    total_hours := total_hours - grade_info.charge_statutaire;
                    end if;
                    montant_brut := calcule_brut(total_hours, grade_info.Taux_horaire_vacation);
                    montant_net := calcule_net(montant_brut, 38);
                    
                    INSERT INTO paiements (id_Intervenant, id_etab, VH, Taux_H, Brut, IR, Net, Annee_Univ, Semestre)
                    VALUES (NEW.id_Intervenant, NEW.id_etab, total_hours, grade_info.Taux_horaire_vacation,
                            montant_brut, 38, montant_net, NEW.Annee_univ, NEW.Semestre);
                END IF;
            END IF;
        END IF;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE or replace  TRIGGER visa_uae_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION update_paiement();

-------------------------------------
-------------------------------------




CREATE OR REPLACE FUNCTION delete_paiement_trigger()
RETURNS TRIGGER AS $$
DECLARE
    volume Integer;
    same_etab BOOLEAN;
    total_hours INTEGER;
    grade_info grades%ROWTYPE;
    semestr INTEGER;
    temp INTEGER;
BEGIN
    IF (OLD.Visa_uae = true AND NEW.Visa_uae = false) THEN
        SELECT Etablissement = NEW.id_etab INTO same_etab
        FROM enseignants
        WHERE id = NEW.id_Intervenant;

        IF (NOT same_etab) THEN
            SELECT VH INTO volume
            FROM paiements
            WHERE id_Intervenant = OLD.id_Intervenant
                AND id_etab = OLD.id_Etab
                AND Annee_Univ = OLD.Annee_univ
                AND Semestre = OLD.Semestre;

            IF (volume - NEW.Nbr_heures > 0) THEN
                UPDATE paiements
                SET VH = volume - NEW.Nbr_heures,
                    Brut = Brut - calcule_brut(NEW.Nbr_heures, Taux_H),
                    Net = Net - calcule_net(calcule_brut(NEW.Nbr_heures, Taux_H), IR)
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            ELSE
                DELETE FROM paiements
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            END IF;
        ELSE
            SELECT * INTO grade_info
            FROM grades
            WHERE id = (SELECT id_Grade FROM enseignants WHERE id = NEW.id_Intervenant);

            SELECT SUM(Nbr_heures) INTO total_hours
            FROM interventions
            WHERE id_Intervenant = NEW.id_Intervenant
                AND id_Etab = NEW.id_Etab
                AND Annee_univ = NEW.Annee_univ
                AND Visa_uae = true;

            IF (total_hours > grade_info.charge_statutaire) THEN
                
                SELECT VH INTO temp
                FROM paiements
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = NEW.semestre;

                temp := temp - NEW.nbr_heures;
                IF (temp > 0) THEN
                    UPDATE paiements
                    SET VH = total_hours,
                        Brut = calcule_brut(total_hours, Taux_H),
                        Net = calcule_net(calcule_brut(total_hours, Taux_H), IR)
                    WHERE id_Intervenant = OLD.id_Intervenant
                        AND id_etab = OLD.id_Etab
                        AND Annee_Univ = OLD.Annee_univ
                        AND Semestre = NEW.semestre;
                ELSE
                    DELETE FROM paiements
                    WHERE id_Intervenant = OLD.id_Intervenant
                        AND id_etab = OLD.id_Etab
                        AND Annee_Univ = OLD.Annee_univ
                        AND Semestre = NEW.semestre;
                END IF;
            ELSE
                DELETE FROM paiements
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ;
            END IF;
        END IF;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;


CREATE or replace TRIGGER delete_paiement_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION delete_paiement_trigger();