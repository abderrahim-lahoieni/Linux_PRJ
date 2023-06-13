CREATE OR REPLACE FUNCTION free_a_user()
RETURNS TRIGGER AS $$
DECLARE
    id_u users.id%TYPE;
BEGIN
    IF NEW.etat = 0 AND OLD.etat = 1 THEN
        SELECT id_User INTO id_u FROM enseignant WHERE id = NEW.id;
        DELETE FROM users WHERE id_User = id_u;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER free_a_user_trigger
after UPDATE ON enseignant
FOR EACH ROW
EXECUTE FUNCTION free_a_user();
--------------------------------
------------------------

CREATE OR REPLACE FUNCTION free_a_administratory()
RETURNS TRIGGER AS $$
DECLARE
    id_u users.id_user%TYPE;
BEGIN
     
        SELECT id_User INTO id_u FROM Administrateur WHERE id = old.id;
        DELETE FROM users WHERE id_user = id_u;
    
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER free_a_admin_trigger
after delete ON Administrateur
FOR EACH ROW
EXECUTE FUNCTION free_a_administratory();
