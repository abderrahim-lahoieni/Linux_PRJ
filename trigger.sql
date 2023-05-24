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
  IF (NEW.Visa_uae) AND (SELECT Visa_etb FROM interventionsWHERE id_Intervention = NEW.id_Intervention) <> TRUE THEN
    RAISE EXCEPTION ' l''intervention n''est pas encore  validé par le directeur d''etablissement';
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER Trg_update_visa_
BEFORE UPDATE ON interventionsFOR EACH ROW
EXECUTE FUNCTION check_visa_etb();

-------------------
----- trigger de sensibilite du nombres d'enseignant de la table etablissement 
CREATE OR REPLACE FUNCTION update_nbr_enseignant()
RETURNS TRIGGER AS $$
DECLARE
   Nbr_old int;
   Nbr_new int;
BEGIN
   IF TG_OP = 'INSERT' THEN
       SELECT Nbr_enseignants INTO Nbr_new
       FROM Etablissement WHERE id = NEW.Etablissement;

       Nbr_new := Nbr_new + 1;

       UPDATE Etablissement
       SET Nbr_enseignants = Nbr_new
       WHERE id = NEW.Etablissement;

       RETURN NEW;



   ELSIF TG_OP = 'UPDATE' THEN
      IF ( OLD.etat=1 and  NEW.etat=0 ) 
      THEN 
             SELECT Nbr_enseignants INTO Nbr_old
             FROM Etablissement WHERE id = OLD.Etablissement;
             Nbr_old := Nbr_old - 1;
             UPDATE Etablissement
             SET Nbr_enseignants = Nbr_old
             WHERE id = OLD.Etablissement; 
      
      end if;
      IF ( OLD.etat=0 and  NEW.etat=1 ) 
      THEN 
             SELECT Nbr_enseignants INTO Nbr_old
             FROM Etablissement WHERE id = OLD.Etablissement;
             Nbr_old := Nbr_old + 1;
             UPDATE Etablissement
             SET Nbr_enseignants = Nbr_old
             WHERE id = OLD.Etablissement; 
      
      end if;
       IF OLD.Etablissement <> NEW.Etablissement   THEN
           SELECT Nbr_enseignants INTO Nbr_old
           FROM Etablissement WHERE id = OLD.Etablissement;

           Nbr_old := Nbr_old - 1;
            
           UPDATE Etablissement
           SET Nbr_enseignants = Nbr_old
           WHERE id = OLD.Etablissement;

           SELECT Nbr_enseignants INTO Nbr_new
           FROM Etablissement WHERE id = NEW.Etablissement;

           Nbr_new := Nbr_new + 1;

           UPDATE Etablissement
           SET Nbr_enseignants = Nbr_new
           WHERE id = NEW.Etablissement;
       END IF;

       RETURN NEW;
   END IF;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER TRG_enseignant
BEFORE INSERT OR UPDATE OR DELETE ON enseignant
FOR EACH ROW  -----trigger par ligne car je veux qu'il se declenche pour chaque instruction 
EXECUTE FUNCTION update_nbr_enseignant();
----------------------------

----------------------------------delete from table user after deleting from enseignat
---------------------------------ATTENTION NE L'implemente pas jusqua la validite par prof
CREATE OR REPLACE FUNCTION supprimer_utilisateur()
RETURNS TRIGGER AS $$
BEGIN
   IF ( OLD.etat=1 and  NEW.etat=0 ) 
      THEN 
    DELETE FROM users WHERE id_User = OLD.id_User;
    end if;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER supprimer_utilisateur_trigger
AFTER update  ON enseignant
FOR EACH ROW
EXECUTE FUNCTION supprimer_utilisateur();


 
--------------------------------securise que id user n'est donne qu'au plus une seul personne
CREATE OR REPLACE FUNCTION verifier_user_id()
RETURNS TRIGGER AS $$
DECLARE
    user_count INTEGER;
BEGIN
    IF TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND NEW.id_User <> OLD.id_User) THEN
        SELECT COUNT(*) INTO user_count FROM enseignant WHERE id_User = NEW.id_User;
        
        IF user_count > 0 THEN
            RAISE EXCEPTION 'mot de passe et email déjà utilisés';
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER verifier_user_id_trigger
BEFORE INSERT OR UPDATE ON enseignant
FOR EACH ROW
EXECUTE FUNCTION verifier_user_id();


--------------------paiements

-------------------- fonction de calcule brut

CREATE OR REPLACE FUNCTION calcule_brut(VH INTEGER, Taux_H INTEGER)
RETURNS INTEGER AS $$
BEGIN
    RETURN (VH * Taux_H) ;
END;
$$ LANGUAGE plpgsql;
---------------------fonction de calcule net

CREATE OR REPLACE FUNCTION calcule_net(brut INTEGER, IR INTEGER)
RETURNS INTEGER AS $$
BEGIN
    RETURN (brut - ((IR * brut)*0.01));
END;
$$ LANGUAGE plpgsql;

---------------
CREATE OR REPLACE FUNCTION update_paiement()
RETURNS TRIGGER AS $$
DECLARE
    existing_paiement paiement%ROWTYPE;
    grade_info grade%ROWTYPE;
    etab_exists BOOLEAN;
    same_etab BOOLEAN;
    VH1 INTEGER;
    total_hours INTEGER;
    montant_brut INTEGER;
    montant_net INTEGER;
BEGIN
    IF (old.visa_uae = false and NEW.Visa_uae= true ) THEN
        SELECT * INTO existing_paiement
        FROM paiement
        WHERE id_Intervenant = NEW.id_Intervenant
        AND id_etab = NEW.id_Etab
        AND Annee_Univ = NEW.Annee_univ
        AND Semestre = NEW.Semestre;

        IF FOUND THEN
        VH1 := existing_paiement.VH + NEW.Nbr_heures;

    
        UPDATE paiement
SET VH = VH1,
    Brut = existing_paiement.Brut + calcule_brut(NEW.Nbr_heures, existing_paiement.Taux_H),
    Net = existing_paiement.Net + calcule_net(existing_paiement.Brut + calcule_brut(NEW.Nbr_heures, existing_paiement.Taux_H), existing_paiement.IR)
WHERE id = existing_paiement.id;


        ELSE
            SELECT * INTO grade_info
            FROM grade
            WHERE id_Grade = (SELECT id_Grade FROM enseignant WHERE id = NEW.id_Intervenant);

            SELECT Etablissement = NEW.id_etab INTO same_etab
            FROM enseignant
            WHERE id = NEW.id_Intervenant;


            IF (same_etab = FALSE) then
                INSERT INTO paiement (id_Intervenant, id_etab, VH, Taux_H, Brut, IR, Net, Annee_Univ, Semestre)
                VALUES (NEW.id_Intervenant, NEW.id_etab, NEW.Nbr_heures, grade_info.Taux_horaire_vacation,
                        calcule_brut(NEW.Nbr_heures, grade_info.Taux_horaire_vacation), 38,
                        calcule_net(calcule_brut(NEW.Nbr_heures, grade_info.Taux_horaire_vacation), 38),
                        NEW.Annee_univ, NEW.Semestre);
            ELSE
                SELECT SUM(Nbr_heures) INTO total_hours
                FROM intervention
                WHERE id_Intervenant = NEW.id_Intervenant
                AND id_Etab = NEW.id_Etab
                AND Annee_univ = NEW.Annee_univ
                AND Semestre = NEW.Semestre
                and visa_uae=true;

                IF total_hours > grade_info.Taux_horaire_vacation THEN
                total_hours := total_hours - grade_info.Taux_horaire_vacation;
                    montant_brut := calcule_brut(total_hours, grade_info.Taux_horaire_vacation);
                    montant_net := calcule_net(montant_brut, 38);
                    
                    INSERT INTO paiement (id_Intervenant, id_etab, VH, Taux_H, Brut, IR, Net, Annee_Univ, Semestre)
                    VALUES (NEW.id_Intervenant, NEW.id_etab, total_hours, grade_info.Taux_horaire_vacation,
                            montant_brut, 38, montant_net, NEW.Annee_univ, NEW.Semestre);
                END IF;
            END IF;
        END IF;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

---------------------------trigger de deletion de paiement 
CREATE OR REPLACE FUNCTION delete_paiement_trigger()
RETURNS TRIGGER AS $$
DECLARE
    same_etab BOOLEAN;
    total_hours INTEGER;
    grade_info grade%ROWTYPE;
BEGIN
    IF (OLD.Visa_uae = true AND NEW.Visa_uae = false) THEN
        
        SELECT NEW.id_etab = Etablissement INTO same_etab
        FROM enseignant
        WHERE id = NEW.id_Intervenant;

        IF (NOT same_etab) THEN
            IF (OLD.VH - NEW.Nbr_heures > 0) THEN
                UPDATE paiement
                SET VH = VH - NEW.Nbr_heures,
                    Brut = Brut - calcule_brut(NEW.Nbr_heures, Taux_H),
                    Net = Net - calcule_net(Brut + calcule_brut(NEW.Nbr_heures, Taux_H), IR)
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            ELSE
                DELETE FROM paiement
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            END IF;
        ELSE
            SELECT * INTO grade_info
            FROM grade
            WHERE id_Grade = (SELECT id_Grade FROM enseignant WHERE id = NEW.id_Intervenant);

            SELECT SUM(Nbr_heures) INTO total_hours
            FROM intervention
            WHERE id_Intervenant = NEW.id_Intervenant
                AND id_Etab = NEW.id_Etab
                AND Annee_univ = NEW.Annee_univ
                AND Semestre = NEW.Semestre
                AND Visa_uae = true;

            --total_hours:= total_hours - new.nbr.heurs;
            IF (total_hours > grade_info.charge_statutaire) THEN 
            total_hours:= total_hours - grade_info.charge_statutaire;
                UPDATE paiement
                SET VH = total_hours,
                    Brut = Brut - calcule_brut(total_hours, Taux_H),
                    Net = Net + calcule_net(Brut + calcule_brut(total_hours, Taux_H), IR)
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            ELSE
                DELETE FROM paiement
                WHERE id_Intervenant = OLD.id_Intervenant
                    AND id_etab = OLD.id_Etab
                    AND Annee_Univ = OLD.Annee_univ
                    AND Semestre = OLD.Semestre;
            END IF;
        END IF;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;


-- Créer le déclencheur "delete_paiement_trigger"
CREATE TRIGGER delete_paiement_trigger
AFTER UPDATE ON intervention
FOR EACH ROW
EXECUTE FUNCTION delete_paiement_trigger();



CREATE  TRIGGER visa_uae_trigger
AFTER UPDATE ON intervention
FOR EACH ROW
EXECUTE FUNCTION update_paiement();
--------------------------------------------
--------------declecheur-----------
------------------------------------------
CREATE or replace TRIGGER visa_uae_trigger
AFTER UPDATE ON intervention
FOR EACH ROW
EXECUTE FUNCTION update_paiement();

















