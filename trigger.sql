---------pour etre sur que instruction delete ne vient pas a ma base de donne j'ai developper un trriger
CREATE OR REPLACE FUNCTION bloque_suppression_enseignant()
RETURNS TRIGGER AS $$
BEGIN
    RAISE EXCEPTION 'La suppression d''un enseignant est interdite.';
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER bloque_suppression_enseignant_trigger
BEFORE DELETE ON enseignant
FOR EACH ROW
EXECUTE FUNCTION bloque_suppression_enseignant();


--------------- impossible de valide visa_uae si visa_etb =false
CREATE OR REPLACE FUNCTION check_visa_etb()
RETURNS TRIGGER AS $$
BEGIN
  IF (NEW.Visa_uae) AND (SELECT Visa_etb FROM interventions  WHERE id_Intervention = NEW.id_Intervention) <> TRUE THEN
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
   actif int;
   

BEGIN
SELECT etat  into actif FROM enseignants  
  WHERE id = NEW.id_Intervenant;
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
        RAISE EXCEPTION 'La modification ou la suppression de l''intervention est bloquée car le visa UAE est déjà validé ou la valeur de Visa_uae ne peut pas être modifiée.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER Trg_update_visa_etb
BEFORE UPDATE OR DELETE ON interventions
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
CREATE OR REPLACE FUNCTION supprimer_utilisateur()
RETURNS TRIGGER AS $$
BEGIN
   IF ( OLD.etat=true and  NEW.etat=false ) 
      THEN 
    DELETE FROM users WHERE id = OLD.id_User;
    end if;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER supprimer_utilisateur_trigger
AFTER update  ON enseignants
FOR EACH ROW
EXECUTE FUNCTION supprimer_utilisateur();
------------------------------------------------------
----------------------------------------------

 
--------------------------------securise que id user n'est donne qu'au plus une seul personne
--- teste de securite
CREATE OR REPLACE FUNCTION verifier_user_id()
RETURNS TRIGGER AS $$
DECLARE
    user_count INTEGER;
BEGIN
    
        SELECT COUNT(*) INTO user_count FROM enseignant WHERE id_User = NEW.id_User;
        
        IF user_count > 0 THEN
            RAISE EXCEPTION 'mot de passe et email déjà utilisés';
        END IF;
    

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





-- Créer le déclencheur "delete_paiement_trigger"
CREATE TRIGGER delete_paiement_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION delete_paiement_trigger();



CREATE  TRIGGER visa_uae_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION update_paiement();
--------------------------------------------
--------------declecheur-----------
------------------------------------------
CREATE or replace TRIGGER visa_uae_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION update_paiement();

















