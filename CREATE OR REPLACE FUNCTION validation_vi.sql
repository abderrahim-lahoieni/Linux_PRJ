CREATE OR REPLACE FUNCTION validation_visa_uae(intervention_id INT)
RETURNS VOID AS $$
BEGIN
  UPDATE interventions  SET visa_uae = 1
  WHERE id_Intervention = intervention_id ;
END;
$$ LANGUAGE plpgsql;
--------------------------------------------
------------------- avoirune idee si vous la voulez 
CREATE OR REPLACE PROCEDURE register_administrateur(
    p_nom VARCHAR,
    p_prenom VARCHAR,
    p_ppr VARCHAR,
    p_date_naissance VARCHAR,
    p_telephone INTEGER,
    p_email VARCHAR,
    p_password VARCHAR,
    p_nom_etablissement VARCHAR,
    p_type VARCHAR,
    p_user_id INT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_etablissement_id INT;
BEGIN
    -- Vérifier si le type d'utilisateur correspond à Administrateur_Etablissement ou directeur
    IF p_type = 'Administrateur_Etablissement' OR p_type = 'directeur' THEN
        -- Récupérer l'ID de l'établissement en fonction du nom
        SELECT id INTO v_etablissement_id
        FROM etablissements
        WHERE nom = p_nom_etablissement;

        -- Créer l'administrateur
        INSERT INTO administrateurs (nom, prenom, ppr, date_naissance, telephone, id_etablissement, id_user)
        VALUES (p_nom, p_prenom, p_ppr, p_date_naissance, p_telephone, v_etablissement_id, p_user_id);
    END IF;
END;
$$;