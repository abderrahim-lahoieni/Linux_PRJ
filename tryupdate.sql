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
            WHERE id_grade = (SELECT id_Grade FROM enseignant WHERE id = NEW.id_Intervenant);

            SELECT Etablissement = NEW.id_etab INTO same_etab
            FROM enseignants
            WHERE id = NEW.id_Intervenant;


            IF (same_etab = FALSE) then
                INSERT INTO paiement (id_Intervenant, id_etab, VH, Taux_H, Brut, IR, Net, Annee_Univ, Semestre)
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

CREATE  TRIGGER visa_uae_trigger
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
    grade_info grade%ROWTYPE;
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
            WHERE id = (SELECT id_Grade FROM enseignant WHERE id = NEW.id_Intervenant);

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


CREATE TRIGGER delete_paiement_trigger
AFTER UPDATE ON interventions
FOR EACH ROW
EXECUTE FUNCTION delete_paiement_trigger();