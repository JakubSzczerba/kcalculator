# Meal Journal Slice Plan

Data: 2026-04-09

## Cel tej iteracji

Wydzielic pierwszy bezpieczny slice `Meal Journal`, ktory:

- odcina `DailyController` od `EntityManagerInterface` w sciezce dodawania wpisu,
- przenosi liczenie wartosci odzywczych do jawnego use case'a,
- zostawia Doctrine i obecne encje jako adapter przejsciowy,
- doklada testy TDD i pierwszy scenariusz biznesowy zamiast robic big-bang rewrite.

## Diagnoza stanu obecnego

Najmocniejsze sprzezenia:

- `src/Application/Controller/DailyController.php` pobiera `User`, `Product` i `Entry` bezposrednio przez `EntityManagerInterface`,
- `AddEntryCommand` i `EditEntryCommand` przenosza encje/DTO zamiast kontraktu aplikacyjnego opartego o prymitywy i VO,
- `EntryDataProvider` wykonuje logike domenowa, ale siedzi w legacy `Application/Prodiver`,
- `MealsDataProvider` zalezy od `Infrastructure/Repository/EntryRepository` i opiera sie na duplikowanych metodach per typ posilku,
- `Domain/Entry/Entity/Entry` jest anemiczna i nie pilnuje inwariantow.

## Zakres pierwszego slice'u

W scope:

1. Nowy modul `src/MealJournal/` jako warstwa aplikacyjna nad obecnym modelem persistence.
2. Use case `AddMealEntry`.
3. Port do zapisu wpisu i port do pobrania produktu potrzebnego do kalkulacji.
4. Refaktor `DailyController::addEntry()` tak, aby nie korzystal z `EntityManagerInterface`.
5. Testy jednostkowe dla kalkulacji i use case'a.
6. Pierwszy scenariusz Behat dla dodania wpisu do dziennika.

Poza scope:

- pelna wymiana encji `Entry`,
- migracja `edit` i `delete`,
- przebudowa calego widoku dziennego,
- przejscie na nowy frontend stack,
- runtime upgrade PHP/Symfony.

## Docelowy ksztalt po tej iteracji

Proponowana struktura:

- `MealJournal/Application/Command/AddMealEntryCommand`
- `MealJournal/Application/Handler/AddMealEntryHandler`
- `MealJournal/Application/Port/MealEntryRepository`
- `MealJournal/Application/Port/FoodProductLookup`
- `MealJournal/Application/Service/NutritionCalculator`
- `MealJournal/Infrastructure/Persistence/DoctrineMealEntryRepository`
- `MealJournal/Infrastructure/Catalog/DoctrineFoodProductLookup`

Wazne: pierwszy slice moze adaptowac aktualne `Domain/Entry/Entity/Entry` i `Domain/Product/Entity/Product`. Celem nie jest od razu nowy model domenowy, tylko postawienie granicy modulowej i usuniecie bezposredniego dostepu kontrolera do infrastruktury.

## Kolejnosc implementacji

### Krok 1

Dodac test jednostkowy dla kalkulacji wartosci odzywczych na podstawie produktu i gramatury.

### Krok 2

Wprowadzic `AddMealEntryCommand` w nowym module z danymi:

- `userId`
- `productId`
- `mealType`
- `grammage`

### Krok 3

Dodac use case i porty:

- lookup produktu po ID,
- zapis wpisu posilku.

### Krok 4

Napisac adaptery infrastrukturalne bazujace na obecnych repozytoriach/Doctrine.

### Krok 5

Przepiac `DailyController::addEntry()` na nowy command handler i usunac z tej sciezki `EntityManagerInterface`.

### Krok 6

Dopisac Behat dla flow:

- wyszukaj produkt,
- wejdz w szczegoly,
- dodaj wpis,
- zobacz komunikat sukcesu lub wpis w dzienniku.

## Kryteria akceptacji

- kontroler nie pobiera `User` i `Product` przez `EntityManagerInterface` w sciezce dodawania wpisu,
- liczenie makro i kcal jest testowane jednostkowo poza kontrolerem,
- nowy kod trafia do `src/MealJournal/*`, nie do legacy `Application/Prodiver`,
- dodanie wpisu przechodzi przez Docker w `phpunit` i `behat`,
- decyzje architektoniczne sa zapisane w `.codex/`.

## Nastepny slice po domknieciu tego planu

Po sukcesie tej iteracji kolejny ruch powinien objac read side:

1. zastapienie `MealsDataProvider` jednym read modelem dziennym,
2. usuniecie duplikacji metod `Show*` i `Summ*` w `EntryRepository`,
3. dopiero potem migracje `edit/delete`.

## Ryzyka

- obecny formularz i routing sa mocno sklejone z legacy UI, wiec Behat moze wymagac drobnych stabilizacji selektorow,
- adapter produktu bedzie tymczasowo oparty o stary `Product`, co trzeba potraktowac jako kompromis przejsciowy,
- query side ma wiekszy debt niz command side; dlatego nie laczymy obu refaktorow w jednym kroku.
