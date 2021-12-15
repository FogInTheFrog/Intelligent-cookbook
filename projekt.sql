DROP TABLE Recipe_ingredients;
DROP TABLE Ingredient;
DROP TABLE Recipe;

CREATE TABLE Ingredient (
 id NUMBER(15) PRIMARY KEY,
 name VARCHAR2(100) UNIQUE NOT NULL
);

CREATE TABLE Recipe (
    id NUMBER(15) PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    description VARCHAR2(3000) NOT NULL,
    instructions VARCHAR2(3000) NOT NULL,
    cook_time NUMBER(5) NOT NULL,
    yield VARCHAR2(30),
    calories NUMBER(5),
    protein NUMBER(5),
    carbohydrates NUMBER(5),
    fat NUMBER(5),
    cholesterol NUMBER(5),
    sodium NUMBER(5),
    servings NUMBER(5),
    author VARCHAR2(50),
    average_rating NUMBER(10) NOT NULL,
    link NUMBER(1)
);

CREATE TABLE Recipe_ingredients (
    recipe_id NUMBER(15) NOT NULL REFERENCES Recipe,
    ingredient_id NUMBER(15) NOT NULL REFERENCES Ingredient,
    CONSTRAINT rec_ing_pk PRIMARY KEY (recipe_id, ingredient_id)
);

COMMIT;