# Worklog

## 2026-04-08

Wykonane:

- przeanalizowano aktualny stan repo i zaleznosci,
- zapisano kanoniczny kontekst projektu w `AGENTS.md` i `.codex/*`,
- potwierdzono kierunek technologiczny: Symfony 8 jest dostepne, PHP 8.5 jest stabilne, ale lokalny workspace nadal pracuje na PHP 8.2.26,
- zidentyfikowano glowne debt areas: brak testow, stary frontend, anemiczny model domenowy, CSV jako jedyne zrodlo danych.

Najblizsze kroki:

1. Dodac fundament testow i konfiguracje katalogow `tests/` oraz `features/`.
2. Przygotowac backlog migracji zaleznosci i runtime.
3. Rozpoczac wydzielanie `Nutrition Catalog` i `Meal Journal`.

Decyzje robocze:

- nie migrujemy wszystkiego naraz,
- nie przywiazujemy domeny do CSV,
- przygotowujemy architekture pod smart scale przez porty i adaptery,
- UI bedzie modernizowane po ustabilizowaniu kontraktow backendowych.

Dopisane artefakty:

- `.codex/upgrade-backlog.md`
- `.codex/food-data-strategy.md`

Pierwsze zmiany wykonawcze:

- wprowadzono zalazek bounded context `NutritionCatalog`,
- stary `csv:import` zastapiono komenda `food-catalog:import` z aliasem wstecznym,
- import CSV zostal ukryty za portem `FoodCatalogImportSource`,
- `DailyController` zalezy juz od interfejsu repozytorium zamiast klasy infrastrukturalnej,
- dodano pierwszy smoke test Behat i pierwsze testy jednostkowe dla nowego importera.
- dla obecnego stacka 6.4 wybrano przejsciowo `phpunit 10.5`, bo aktualny `symfony/maker-bundle` blokuje `nikic/php-parser 5.x`, wymagany przez `phpunit 11`.

Weryfikacja w Dockerze:

- `docker compose build php`
- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --colors`
- `docker compose run --rm --no-deps php php bin/console list food-catalog`

Wynik:

- PHPUnit: `2 tests, 7 assertions`,
- Behat: `1 scenario, 1 passed`,
- komenda `food-catalog:import` jest zarejestrowana.

Blokery i uwagi na nowa sesje:

- `docker/php/Dockerfile` nadal opiera sie o `php:8.3-fpm` i legacy Node 18,
- recipe PHPUnit utworzylo pliki testowe; zostaly ujednolicone do `phpunit.dist.xml`,
- `Meal Journal` i stary model wpisow nie zostaly jeszcze przebudowane,
- repo nadal zawiera duzy procent starej architektury warstwowej.

## 2026-04-09

Wykonane:

- przeanalizowano aktualny stan `Meal Journal` i punkty sprzezenia z legacy warstwa aplikacyjna,
- potwierdzono, ze `DailyController` nadal uzywa `EntityManagerInterface` w sciezkach add/edit/delete,
- zapisano plan pierwszego bezpiecznego slice'u `Meal Journal` w `.codex/meal-journal-slice-plan.md`,
- dodano nowy modul `src/MealJournal/` dla command side `AddMealEntry`,
- dodano porty `FoodProductLookup` i `MealEntryRepository` oraz adaptery Doctrine,
- dodano `NutritionCalculator` i testy jednostkowe dla kalkulacji i handlera,
- przepieto `DailyController::addEntry()` na nowy command `MealJournal\Application\Command\AddMealEntryCommand`,
- dodano scenariusze Behat dla sukcesu i bledu przy dodawaniu wpisu posilku,
- wydzielono read side `Meal Journal` przez `DailyMealJournalViewReader`,
- usunieto legacy `MealsDataProvider`,
- zastapiono duplikowane odczyty per typ posilku jednym zapytaniem `findEntriesForDay()` i mapowaniem do legacy payloadu Twiga,
- zmodernizowano shell frontendu w `templates/base.html.twig`,
- usunieto wielokrotne CDN-y jQuery i inline JS z layoutu,
- przeniesiono interakcje shellu do `assets/app.js`,
- przebudowano UI flow dziennika: wyniki wyszukiwania, szczegoly produktu i widok dnia,
- dodano nowy `assets/styles/app.css` z responsywnym layoutem, tokenami kolorow i wsparciem dla motywu,
- przebudowano dashboard i profil do nowego ukladu kart, metryk i empty state bez zmiany kontraktow backendu.

Dopisane artefakty:

- `.codex/meal-journal-slice-plan.md`
- `.codex/frontend-backlog.md`

Decyzje robocze:

- pierwszy wycinek obejmuje tylko command side `AddMealEntry`,
- obecne encje Doctrine moga zostac wykorzystane jako adapter przejsciowy,
- read side dziennika bedzie osobnym krokiem po domknieciu sciezki dodawania wpisu,
- Behat dla `Meal Journal` zostal poprowadzony na poziomie use case'a, bo repo nie ma jeszcze harnessu przegladarkowego,
- read side zachowuje obecny shape danych dla `Twig`, zeby nie laczyc refaktoru backendu z przebudowa UI w jednym kroku,
- frontend byl modernizowany iteracyjnie: najpierw shell i dziennik, potem dashboard i profil,
- warning o zgodnosci `@symfony/stimulus-bridge` z obecnym Encore pozostaje technicznym dlugiem toolchainu, ale build przechodzi.

Weryfikacja w Dockerze:

- `docker compose run --rm encore yarn build`
- `docker compose run --rm --no-deps php php bin/console lint:twig templates/base.html.twig templates/User/Daily/index.html.twig templates/User/Daily/Products/productDetails.html.twig templates/User/Daily/Products/searchedProducts.html.twig`
- `docker compose run --rm --no-deps php php bin/console lint:twig templates/User/Dashboard/index.html.twig templates/User/Profile/index.html.twig`
- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --config=behat.yml.dist --colors`

Wynik:

- Encore: `webpack compiled successfully`,
- Twig lint: `All 6 Twig files contain valid syntax`,
- PHPUnit: `7 tests, 28 assertions`,
- Behat: `3 scenarios, 3 passed`.

Rekomendowany nastepny krok:

1. Domknac frontend dla ekranow logowania, rejestracji i homepage, zeby shell aplikacji byl juz spojny end-to-end.
2. Doliczyc frontendowe stany aktywne/blad dla wyszukiwarki i formularzy.
3. Potem wracac do backendowego cleanupu `edit/delete` w `MealJournal`.
