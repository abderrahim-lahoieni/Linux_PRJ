CREATE OR REPLACE FUNCTION free_a_user()
RETURNS TRIGGER AS $$
DECLARE
    id_u users.id%TYPE;
BEGIN
    IF NEW.etat = 0 AND OLD.etat = 1 THEN
        SELECT id_User INTO id_u FROM enseignant WHERE id = NEW.id;
        update enseignant set id_user=NULL where id = NEW.id;
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
  
BEGIN
     
        

        DELETE FROM users WHERE id_user = old.id_User;
    
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE or replace TRIGGER free_a_admin_trigger
after delete ON Administrateur
FOR EACH ROW
EXECUTE FUNCTION free_a_administratory();
