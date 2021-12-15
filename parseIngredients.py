ingredientsNotParsedFile = open("ingredientsText.txt", "r", encoding="utf8")
ingredientsParsedFile = open("parsedIngredientsNames.txt", "w")

contentOfIngredientsDataBase = ingredientsNotParsedFile.readlines()
correctIngredients = 0
unknownIngredients = 0
nonEnglishCharacterIngredients = 0
incorrectLineIngredients = 0
digitIngredients = 0


def isNonEnglishCharacter(s):
    try:
        s.encode(encoding='utf-8').decode('ascii')
    except UnicodeDecodeError:
        return True
    else:
        return False


def formatIngredientName(ingredientName):
    ingredientName = ingredientName.casefold()
    ingredientName = ingredientName.title()

    return ingredientName


for lineOfTextFile in contentOfIngredientsDataBase:
    listOfIngredientSubstring = lineOfTextFile.split('}')

    for ingredientSubstring in listOfIngredientSubstring:
        isIngredientWithWikiLink = 0

        namePattern = '"name":'
        nameBeginning = ingredientSubstring.find(namePattern)

        if nameBeginning == -1:
            print("Incorrect ingredient substring format", ingredientSubstring)
            incorrectLineIngredients += 1
        else:
            knownPattern = '"known":'
            isUnknown = ingredientSubstring[ingredientSubstring.find(knownPattern) + knownPattern.__len__()] == '0'

            if isUnknown:
                print("This ingredient is unknown", ingredientSubstring)
                unknownIngredients += 1
            else:
                nameBeginning += namePattern.__len__()
                pureIngredientName = ""
                quotes = 0
                isDigitInName = False

                for ch in ingredientSubstring[nameBeginning:]:
                    if ch == '"':
                        quotes += 1
                    # colon in name means, there was prefix indicating ingredient's
                    # origin country and we don't want it
                    elif ch == ':':
                        pureIngredientName = ""
                    elif ch.isdigit():
                        isDigitInName = True
                    else:
                        pureIngredientName += ch
                    if quotes == 2:
                        break

                # we also want to get rid of names which contains digits, because they are either
                # vitamins or chemical compounds (we don't want our base to contain such complicated things)
                if isDigitInName:
                    print("There was digit in name", ingredientSubstring)
                    digitIngredients += 1
                    continue

                # we decided also not to include ingredients with nonEnglishCharacters
                if isNonEnglishCharacter(pureIngredientName):
                    print("Non English Characters in line:", ingredientSubstring)
                    nonEnglishCharacterIngredients += 1
                    continue

                correctIngredients += 1
                if correctIngredients > 1:
                    ingredientsParsedFile.write("\n")
                ingredientsParsedFile.write(formatIngredientName(pureIngredientName))


ingredientsNotParsedFile.close()
ingredientsParsedFile.close()

print("Number of correctIngredients = ", correctIngredients)
print("Number of unknownIngredients = ", unknownIngredients)
print("Number of nonEnglishCharacterIngredients = ", nonEnglishCharacterIngredients)
print("Number of incorrectLineIngredients = ", incorrectLineIngredients)
print("Number of digitIngredients = ", digitIngredients)