CREATE OR REPLACE FUNCTION delete_paiement_trigger()

RETURNS TRIGGER AS $$

DECLARE
volume Integer;
    same_etab BOOLEAN;
    total_hours INTEGER;
    grade_info grade%ROWTYPE;

BEGIN
    IF (OLD.Visa_uae =true AND NEW.Visa_uae = false) THEN

        SELECT NEW.id_etab = Etablissement INTO same_etab
        FROM enseignants
        WHERE id = NEW.id_Intervenant;

        IF (NOT same_etab) THEN
        select VH into volume from paiements
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
            FROM grade
            WHERE id = (SELECT id_Grade FROM enseignant WHERE id = NEW.id_Intervenant);

            SELECT SUM(Nbr_heures) INTO total_hours
            FROM interventions
            WHERE id_Intervenant = NEW.id_Intervenant
                AND id_Etab = NEW.id_Etab
                AND Annee_univ = NEW.Annee_univ
                AND Semestre = NEW.Semestre
                AND Visa_uae = true;

           
            IF (total_hours > grade_info.charge_statutaire) THEN 
            total_hours:= total_hours - grade_info.charge_statutaire;
                UPDATE paiements
                SET VH = total_hours,
                    Brut = calcule_brut(total_hours, Taux_H),
                    Net = calcule_net(calcule_brut(total_hours, Taux_H), IR)
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
        END IF;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;