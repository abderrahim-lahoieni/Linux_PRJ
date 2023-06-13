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

--------------------------
create or replace Trigger free_a_user
before update on intervention
for each ROW
DECLARE
id_u users.id%type;
BEGIN
if new.etat=0 and old.etat=1
THEN
select id_user into id_u from enseignant 
where id=new.id;
delete from users where id_users=id_u;
end if;
end;\


