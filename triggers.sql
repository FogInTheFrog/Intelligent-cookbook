CREATE SEQUENCE ingId
  START WITH 3000
  INCREMENT BY 1;

CREATE OR REPLACE TRIGGER ingTrigger
  BEFORE INSERT
  ON INGREDIENT
  FOR EACH ROW
    BEGIN
      SELECT ingId.nextval INTO :NEW.ID FROM dual;
    END;
/