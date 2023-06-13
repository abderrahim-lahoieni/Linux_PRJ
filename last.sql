CREATE OR REPLACE FUNCTION check_unique_president()
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


