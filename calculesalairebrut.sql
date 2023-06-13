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
    SELECT SUM(net) INTO salaire_brut FROM paiements
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
    cod etablissement.id%type;
    volumehoraire NUMERIC;
    salaire NUMERIC;
    
    TXH NUMERIC;
BEGIN
 select etablissement into cod from enseignant where id=code_enseignant;
    SELECT SUM(vh) INTO volumehoraire FROM paiement
    WHERE id_Intervenant = code_enseignant AND annee_univ = annee and id_Etab=cod;
   
    -- Adjust volumehoraire
    
SELECT Taux_horaire_vacation INTO TXH
            FROM grade
            WHERE id_Grade = (SELECT id_Grade FROM enseignant WHERE id = code_enseignant);

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
    cod etablissement.id%type;
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
            WHERE id = (SELECT id_Grade FROM enseignant WHERE id = code_enseignant);

    RAISE NOTICE 'Son nbr d''heures de vacation est : %', volumehoraire;

    salaire := public.calcule_net(public.calcule_brut(volumehoraire, TXH), 38);

    RAISE NOTICE 'Son salaire est : %', salaire;

    RETURN salaire;
END;
$$ LANGUAGE plpgsql;