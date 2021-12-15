from selenium.common.exceptions import NoSuchElementException
from selenium import webdriver

# Number of recipes added
recipesAdded = 0

# Address where we download DB from
allrecipesAddress = "https://www.allrecipes.com/recipe/"

# Using Chrome to access web on Linux Laptop
# driver = webdriver.Chrome("Users\Kuba\Downloads\chromedriver_win32\chromedriver")

# Using Chrome to access web on Windows PC
driverPath = "C:/Users/Kuba/Downloads/chromedriver_win32/chromedriver.exe"
driver = webdriver.Chrome(executable_path=driverPath)


# ------------------------------------------------------------------------------
# Class which contains data of one recipe
class Recipe:
    def __init__(self, rid, name, description, directions, cookTime, recipeYield, nutritionInfo, servings, author):
        self.recipeId = rid
        self.name = self.formatText(name)
        self.description = self.formatText(description)
        self.directions = self.formatText(directions)
        self.cookTime = cookTime
        self.recipeYield = self.formatText(recipeYield)
        self.nutritionInfo = nutritionInfo
        self.servings = servings
        self.author = self.formatText(author)
        self.rating = 0
        self.ingredientsIdList = []

    def formatText(self, text):
        formattedText = ""
        for c in text:
            if not c.isascii():
                continue
            elif c == "'":
                formattedText += "''"
            else:
                formattedText += c
        return formattedText

    def addIngredientId(self, iid):
        self.ingredientsIdList.append(iid)

    def getNiceForm(self):
        return str("(" + str(self.recipeId) + ", '" + str(self.name) + "', '" + str(self.description) + "', '" +
                   str(self.directions) + "', " + str(self.cookTime) + ", '" + str(self.recipeYield) + "', " +
                   str(self.nutritionInfo.calories) + ", " + str(self.nutritionInfo.protein) + ", " +
                   str(self.nutritionInfo.carbohydrates) + ", " + str(self.nutritionInfo.fat) + ", " +
                   str(self.nutritionInfo.cholesterol) + ", " + str(self.nutritionInfo.sodium) + ", " +
                   str(self.servings) + ", '" + str(self.author) + "', " + str(self.rating) + ", 1)")

    def printNicely(self):
        print(self.getNiceForm())


# Class which collects information on Ingredient
class Ingredient:
    def __init__(self, name, ingredientId):
        self.id = ingredientId
        self.name = name.replace("'", "''")
        self.recipesIdList = []

    def addRecipeId(self, rid):
        self.recipesIdList.append(rid)

    def getNiceForm(self):
        return str("(" + str(self.id) + ", '" + self.name + "')")

# Class which collects information on Nutrition
class NutritionInfo:
    def __init__(self, calories, protein, carbohydrates, fat, cholesterol, sodium):
        self.calories = calories
        self.protein = protein
        self.carbohydrates = carbohydrates
        self.fat = fat
        self.cholesterol = cholesterol
        self.sodium = sodium


# ------------------------------------------------------------------------------
# Recipe:Cooktime

def is_string_total_cooktime(s):
    sLowercase = s.casefold()
    isTotal = sLowercase.find("total")

    return isTotal != -1


def getCooktimeInMinutes(s):
    hrs = 0
    mins = 0
    val = 0
    wasPreviousDigit = False
    for i in s:
        if i.isdigit():
            if wasPreviousDigit == False and val != 0:
                hrs = val
                val = int(i)
            else:
                val *= 10
                val += int(i)
            wasPreviousDigit = True
        else:
            wasPreviousDigit = False
            if i == "h":
                hrs = val
                val = 0
            elif i == "m":
                mins = val
                val = 0
    if val != 0:
        print("I couldn't parse it: ", hrs, "=hrs ", mins, "=mins ", val, "=mins", s)
    return hrs * 60 + mins


# ------------------------------------------------------------------------------
# Auxiliary Functions

def removePattern(s, pattern):
    startingIndex = s.find(pattern)

    if startingIndex == -1:
        print("You should have checked if " + pattern + " is in s before calling this function")
    else:
        startingIndex += len(pattern)
        while startingIndex < s.__len__():
            if not s[startingIndex].isspace():
                break
            else:
                startingIndex += 1
    return s[startingIndex:]


# ------------------------------------------------------------------------------
# Recipe:Yield

yieldPattern = "Yield:"

def isStringYield(s):
    isYield = s.find(yieldPattern)
    return isYield != -1


# Discards "Yield:" prefix from string and spaces after it
def removeYield(s):
    return removePattern(s, yieldPattern)


# ------------------------------------------------------------------------------
# Recipe:Servings

servingsPattern = "Servings:"


def isStringServings(s):
    isServings = s.find(servingsPattern)
    return isServings != -1


# Discards "Servings:" prefix from string and spaces after it
def removeServings(s):
    return removePattern(s, servingsPattern)


# ------------------------------------------------------------------------------
# Parses s which is NutritionFactsSection text to class NutritionInfo
def parseNutritionFactsSection(s):
    splitNutritionFacts = s.split(";")
    calories = 0
    protein = 0
    carbohydrates = 0
    fat = 0
    cholesterol = 0
    sodium = 0

    for x in splitNutritionFacts:
        lastWasDot = False
        value = 0
        # print(x)
        for c in x:
            if c.isdigit():
                if lastWasDot:
                    if int(c) >= 5:
                        value += 1
                    break
                else:
                    value *= 10
                    value += int(c)
                    lastWasDot = False
            elif c == ".":
                lastWasDot = True
            else:
                lastWasDot = False

        if "calories" in x:
            calories = value
        elif "protein" in x:
            protein = value
        elif "carbohydrates" in x:
            carbohydrates = value
        elif "fat" in x:
            fat = value
        elif "cholesterol" in x:
            cholesterol = value
        elif "sodium" in x:
            sodium = value
        else:
            print("I couldn't match " + x + " to any nutrition")

    return NutritionInfo(calories, protein, carbohydrates, fat, cholesterol, sodium)


# ------------------------------------------------------------------------------
# Reads list of ingredients from file into list.

ingredientsFile = open("parsedIngredientsNames.txt", "r")
ingredientsListFromFile = ingredientsFile.readlines()
ingredientsFile.close()

numberOfIngredients = 0
ingredientsList = []

insertIntoIngredientsFile = open("ingredientInserts.txt", "a")
#insertIntoIngredientsFile.truncate(0)

for ingredientName in ingredientsListFromFile:
    ingredientsList.append(Ingredient(ingredientName.replace("\n", ""), numberOfIngredients))
    numberOfIngredients += 1
    #insertIntoIngredientsFile.write("INSERT INTO Ingredient VALUES" + ingredientsList[-1].getNiceForm() + ";\n")


# ------------------------------------------------------------------------------
# Function receives one read ingredient line from allrecipes.com and tries
# to find the best match in known ingredients list. Returns found ingredient
# id and adds recipeId to ingredient's recipe list. If no match was found,
# returns -1.

# We greedy merge ingredientLine with ingredient from our list of Ingredients
# which has the best % matching

def comparePercentages(bestCandidatePercentage, candidatePercentage):
    return bestCandidatePercentage[0] * candidatePercentage[1] <= bestCandidatePercentage[1] * candidatePercentage[0]


def mergeIngredients(ingredientLineToMatch, recipeId):
    bestCandidateId = -1
    bestCandidatePercentage = (0, 10)
    bestCandidateName = ""

    for ingredient in ingredientsList:
        ingredientCasefold = ingredient.name.casefold()
        splittedIngredientCasefold = ingredientCasefold.split()
        candidateId = ingredient.id
        candidateName = ""
        for ingredientWord in splittedIngredientCasefold:
            if ingredientWord in ingredientLineToMatch:
                if candidateName != "":
                    candidateName += " "
                candidateName += ingredientWord

        candidatePercentage = (candidateName.__len__(), ingredientCasefold.__len__())
        if comparePercentages(bestCandidatePercentage, candidatePercentage):
            if comparePercentages(candidatePercentage, bestCandidatePercentage):
                if bestCandidateName.__len__() <= candidateName.__len__():
                    bestCandidateId = candidateId
                    bestCandidatePercentage = candidatePercentage
                    bestCandidateName = candidateName
            else:
                bestCandidateId = candidateId
                bestCandidatePercentage = candidatePercentage
                bestCandidateName = candidateName

    if bestCandidateId != -1:
        ingredientsList[bestCandidateId].addRecipeId(recipeId)
    return bestCandidateId


# ------------------------------------------------------------------------------
# main
# Program guarentees to scrape correctly any recipe on allrecipes.com
# with recipeId in range [210000, 290000]
startingRecipeId = "270000"


insertsRecipesFile = open("inserts.txt", "a")
#insertsRecipesFile.truncate(0)
recipesList = []

recId = startingRecipeId
recipeLimit = 273000

insertsRecipeIngredientsFile = open("recipeIngredientInserts.txt", "a")
#insertsRecipeIngredientsFile.truncate(0)

while int(recId) <= recipeLimit:
    print(80 * '-')
    correctPageFound = False

    while not correctPageFound:
        try:
            recId = str(int(recId) + 1)
            if int(recId) > recipeLimit:
                break

            driver.get(allrecipesAddress + recId)
            title = driver.find_element_by_xpath("//*[@class='headline heading-content']").text
            description = driver.find_element_by_xpath("//*[@class='margin-0-auto']").text
            recipeInfoSectionList = driver.find_elements_by_xpath("//*[@class='recipe-meta-item']")
            nutritionFactsSection = driver.find_element_by_xpath("//*[@class='partial recipe-nutrition-section']")
            author = driver.find_element_by_xpath("//*[@class='author-name link']").text

            cookTime = 0
            yieldString = ""
            servingsString = ""
            # Scraping cookTime, yield, servings
            for x in recipeInfoSectionList:
                # print("x from recipeInfoSectionList:", x.text)
                if is_string_total_cooktime(x.text):
                    cookTime = getCooktimeInMinutes(x.text)
                elif isStringYield(x.text):
                    yieldString = removeYield(x.text)
                elif isStringServings(x.text):
                    servingsString = removeServings(x.text)

            # Parse nutrition facts section
            nutritionFacts = parseNutritionFactsSection(nutritionFactsSection.text)

            # Scraping directions on how to prepare the meal
            directionsListOfSteps = driver.find_elements_by_xpath("//*[@class='paragraph']")
            directions = ""
            for step in directionsListOfSteps:
                directions += step.text + " "

            newRecipe = Recipe(recId, title, description, directions, cookTime, yieldString, nutritionFacts,
                               servingsString, author)

            # Scraping ingredients
            ingredientsLinkList = driver.find_elements_by_xpath("//*[@class='ingredients-item-name']")

            for ingredientLink in ingredientsLinkList:
                ingredientWithNumsAndSpecial = ingredientLink.text
                ingredientWithNumsAndSpecial = ingredientWithNumsAndSpecial.casefold()
                ingredientLine = ""

                for j in ingredientWithNumsAndSpecial:
                    if j.isalpha() or j.isspace():
                        ingredientLine += j

                # Finds ingredient in database and updates ingredient's recipe list
                mergedIngredientId = mergeIngredients(ingredientLine, recId)

                if mergedIngredientId == -1:
                    print("I couldn't match this ingredient line: ", ingredientLine)
                    print("Add new ingredient: ", ingredientLine.replace("\n", ""))
                    ingredientsList.append(Ingredient(ingredientLine.replace("\n", ""), numberOfIngredients))
                    numberOfIngredients += 1
                    insertIntoIngredientsFile.write("")
                    newRecipe.addIngredientId(numberOfIngredients - 1)
                    insertIntoIngredientsFile.write(
                        "INSERT INTO Ingredient VALUES" + ingredientsList[-1].getNiceForm() + ";\n")
                else:
                    newRecipe.addIngredientId(mergedIngredientId)
                    print(ingredientLine, " = ", ingredientsList[mergedIngredientId].name)

        except NoSuchElementException:
            print("No recipe with id: " + recId)
        else:
            correctPageFound = True
            recipesList.append(newRecipe)
            recipesList[-1].printNicely()
            for ingredientId in newRecipe.ingredientsIdList:
                insertsRecipeIngredientsFile.write("INSERT INTO Recipe_Ingredients VALUES(" + str(newRecipe.recipeId) +
                                                   ", " + str(ingredientId) + ");\n")
            insertsRecipesFile.write("INSERT INTO Recipe VALUES" + newRecipe.getNiceForm() + ";\n")

insertsRecipesFile.close()
insertIntoIngredientsFile.close()
insertsRecipeIngredientsFile.close()
