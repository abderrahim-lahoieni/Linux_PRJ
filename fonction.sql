
-----------procedure stocker pour valider visa uae-
CREATE OR REPLACE FUNCTION validation_visa_uae(intervention_id INT)
RETURNS VOID AS $$
BEGIN
  UPDATE interventions  SET visa_uae = TRUE
  WHERE id_Intervention = intervention_id ;
END;
$$ LANGUAGE plpgsql;
----------------deletion equivalent a etat=0 au lieu de donne id c'est mieux de pqsser juste 
------------------ppr il est unique connu dans plate form ppr cle candidate
CREATE OR REPLACE FUNCTION deletion_enseignant(prof_pr varchar(100))
RETURNS VOID AS $$
BEGIN
  UPDATE enseignant  SET etat = 0
  WHERE ppr = prof_pr ;
END;
$$ LANGUAGE plpgsql;
-------------------------
CREATE OR REPLACE FUNCTION id_grade_via_designation(
    grade_des VARCHAR(100)
)
RETURNS INTEGER
AS $$
DECLARE
    grade_id INTEGER;
BEGIN
    SELECT id_Grade INTO grade_id
    FROM grades
    WHERE designation = grade_des;
    
    RETURN grade_id;
END;
$$ LANGUAGE plpgsql;
----------------------------------------------

----------------------------by anne
CREATE OR REPLACE FUNCTION GetAllInterventionsvalideByPresident(annee INT)                                          
  RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean,
    visauae boolean
  )
AS $$
BEGIN
  RETURN QUERY  
    SELECT id_Intervention, Intitule_intervention, Annee_univ, Semestre, 
    Date_debut, Date_fin, Nbr_heures, visa_etb, visa_uae
    FROM interventions
    WHERE interventions.visa_etb = true AND interventions.Annee_univ = annee;
END;
$$ LANGUAGE plpgsql;
--------------------------------------------------paretab
CREATE OR REPLACE FUNCTION GetAllInterventionsvalideByPresidentviaid(id_etabli INTEGER)                                          
  RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean,
    visauae boolean
  )
AS $$
BEGIN
  RETURN QUERY  
    SELECT id_Intervention, Intitule_intervention, Annee_univ, Semestre, 
    Date_debut, Date_fin, Nbr_heures, visa_etb, visa_uae
    FROM interventions
    WHERE interventions.visa_etb = true AND interventions.id_Etab = id_etabli;
END;
$$ LANGUAGE plpgsql;
------------------------------------------parenseignnat

CREATE OR REPLACE FUNCTION GetAllInterventionsvalideByPresidentparenseignanat(id_ens INTEGER)                                          
  RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean,
    visauae boolean
  )
AS $$
BEGIN
  RETURN QUERY  
    SELECT id_Intervention, Intitule_intervention, Annee_univ, Semestre, 
    Date_debut, Date_fin, Nbr_heures, visa_etb, visa_uae
    FROM interventions
    WHERE intervention.visa_etb = true AND intervention.id_Etab = id_ens;
END;
$$ LANGUAGE plpgsql;
----------------------------------ALL intervension ou visa etb=1
CREATE OR REPLACE FUNCTION GetAllInterventionsvalideByPresident()                                          
  RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean,
    visauae boolean
  )
AS $$
BEGIN
  RETURN QUERY  
    SELECT id_Intervention, Intitule_intervention, Annee_univ, Semestre, 
    Date_debut, Date_fin, Nbr_heures, visa_etb, visa_uae
    FROM interventions
    WHERE intervention.visa_etb = true ;
END;
$$ LANGUAGE plpgsql;
--------------------------enseignnants et ses interventions
-------------que les interventions non valider
CREATE OR REPLACE FUNCTION get_all_interventions_by_enseignant(id_professeur INT)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures
    FROM interventions
    WHERE visa_etb = false
    AND id_intervenant = id_professeur;
END;
$$ LANGUAGE plpgsql;

------------------------------------

CREATE OR REPLACE FUNCTION interventions_by_etablissement_by_enseignant(id_etablissement INT, id_professeur INT)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures
    FROM interventions
    WHERE visa_etb = false
    AND id_intervenant = id_professeur
    AND id_etablissement = id_etablissement;
END;
$$ LANGUAGE plpgsql;
-------------------------------------
CREATE OR REPLACE FUNCTION get_interventions_by_annee_by_enseignant(annee_universitaire int, id_professeur INT)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures
    FROM interventions
    WHERE visa_etb = false
    AND id_intervenant = id_professeur
    AND annee_univ = annee_universitaire;
END;
$$ LANGUAGE plpgsql;

-----------DIRECTEUR-----
CREATE OR REPLACE FUNCTION get_all_interventions_by_directeur(id_etablissement INT)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures, visa_etab
    FROM interventions
    WHERE visa_etab = false
    AND id_Etab  = id_etablissement;
END;
$$ LANGUAGE plpgsql;
-------------------
CREATE OR REPLACE FUNCTION get_interventions_by_professeur_by_directeur(id_etablissement INT, id_professeur INT)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures, visa_etab
    FROM interventions
    WHERE visa_etab = false
    AND id_Eta = id_etablissement
    AND id_intervenant = id_professeur;
END;
$$ LANGUAGE plpgsql;

--------------------------
CREATE OR REPLACE FUNCTION get_interventions_by_annee_by_directeur(id_etablissement INT, annee_universitaire int)
RETURNS TABLE (
    id INT,
    intitule VARCHAR(30),
    Annee_u INT,
    S CHAR(2),
    Date_db DATE,
    Date_fn DATE,
    Nbr_heure INT,
    visaetb boolean
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_Intervention, intitule_intervention, annee_univ, semestre, date_debut, date_fin, nbr_heures, visa_etab
    FROM interventions
    WHERE visa_etab = false
    AND id_etab = id_etablissement
    AND annee_univ = annee_universitaire;
END;
$$ LANGUAGE plpgsql;



------------------------------

