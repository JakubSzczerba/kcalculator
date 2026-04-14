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

## 2026-04-10

Wykonane:

- przejrzano roadmape, backlogi i zapisany stan projektu,
- zapisano proponowana kolejnosc kolejnej implementacji w `.codex/next-implementation-plan.md`,
- dodano drugi command slice `MealJournal` dla `EditMealEntry` i `DeleteMealEntry`,
- dodano port `MealEntryLookup` oraz adapter Doctrine oparty o `EntryRepository`,
- rozszerzono port `MealEntryRepository` o aktualizacje i usuwanie wpisu,
- przepieto `DailyController::editEntry()` i `DailyController::deleteEntry()` na nowy modul `MealJournal`,
- usunieto bezposrednia zaleznosc `DailyController` od `EntityManagerInterface`,
- dodano testy jednostkowe dla handlerow `EditMealEntryHandler` i `DeleteMealEntryHandler`,
- dodano scenariusze Behat dla edycji i usuwania wpisu,
- wydzielono read model `DailyNutritionSummaryReader` dla dashboardowych agregatow dziennych,
- przepieto `DashboardController` z `EntryRepository` na nowy reader,
- uproszczono `EntryRepository` do metod nalezacych bezposrednio do dziennika wpisow,
- zmieniono odczyt dnia na zakres `start/end of day`, zamiast porownywania `datetime` do stringa z data,
- przebudowano `Homepage`, `Login` i `Register` do wspolnego shellu public/auth,
- dopisano zasady design systemu do `.codex/frontend-design-system.md`.

Decyzje robocze:

- ownership wpisu jest sprawdzany w lookupie i brak dostepu jest traktowany jak `not found`,
- obecna encja `Entry` oraz formularz `ProductDetailsType` pozostaja adapterem przejsciowym,
- dashboardowe sumy zostaly wyjete z `EntryRepository` do osobnego read modelu zamiast dokladania kolejnych metod do repo,
- odczyt dzienny uzywa jawnego zakresu czasu, co lepiej odpowiada semantyce wpisow zapisywanych z pelnym `datetime`.

Weryfikacja w Dockerze:

- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --config=behat.yml.dist --colors`
- `docker compose run --rm --no-deps php php bin/console lint:container`
- `docker compose run --rm --no-deps php php bin/console lint:twig templates/Homepage/homepage.html.twig templates/User/Account/Login/index.html.twig templates/User/Account/Register/index.html.twig`
- `docker compose run --rm encore yarn build`

Wynik:

- PHPUnit: `13 tests, 46 assertions`,
- Behat: `5 scenarios, 5 passed`,
- `lint:container` przechodzi,
- Twig lint: `All 3 Twig files contain valid syntax`,
- Encore: `webpack compiled successfully` z pozostajacym warningiem `@symfony/stimulus-bridge`.

Rekomendowany nastepny krok:

1. Wejsc w techniczny spike toolchainu frontendowego: Node LTS, Encore i warning `@symfony/stimulus-bridge`.
2. Potem zdecydowac, czy nastepny backendowy cleanup dotyczy `WeightHistory`/dashboardu, czy zaczynamy przygotowanie pod runtime upgrade.

## 2026-04-14

Wykonane:

- przeprowadzono techniczny spike frontendowego toolchainu i zapisano wynik w `.codex/frontend-toolchain-spike-2026-04-14.md`,
- potwierdzono, ze `docker compose run --rm encore yarn build` przechodzi, ale reprodukuje warning kompatybilnosci `@symfony/stimulus-bridge 3.2.2` z `@symfony/webpack-encore 1.5.0`,
- potwierdzono, ze warning pochodzi z samego pakietu Encore, a nie z lokalnej konfiguracji `webpack.config.js`,
- zidentyfikowano rozjazd runtime'ow Node miedzy serwisem `encore` i obrazem PHP,
- podniesiono runtime Node do linii 20 w `docker-compose.yml` i `docker/php/Dockerfile`,
- po podniesieniu Node wykryto blad OpenSSL w starym `webpack 5.45`, dlatego skrypty `yarn` dostaly tymczasowy `NODE_OPTIONS=--openssl-legacy-provider`.
- wykonano kontrolowany update zaleznosci JS: `@symfony/webpack-encore 4.7.0`, `webpack 5.106.1`, `webpack-cli 5.1.4`,
- usunieto tymczasowy `NODE_OPTIONS=--openssl-legacy-provider`,
- usunieto legacy pakiet `stimulus`, poprawiono import w `assets/controllers/hello_controller.js` i przypieto `chart.js` do `3.8.0`, zgodnie z zakresem `symfony/ux-chartjs`.

Decyzje robocze:

- tymczasowo zostajemy przy Encore i nie laczymy tego kroku z migracja na inny stack assetow,
- kontrolowany update dependency graphu JS zostal wykonany bez zmiany architektury assetow,
- wynik spike'a ma sluzyc jako wejscie do runtime upgrade, a nie jako pretekst do rozszerzenia scope.

Weryfikacja:

- `docker compose build php`
- `docker compose run --rm encore node -v`
- `docker compose run --rm encore yarn install`
- `docker compose run --rm encore yarn build`

Wynik:

- Node w kontenerze `encore` przed zmiana: `v12.13.1`,
- Node w kontenerze `encore` po zmianie: `v20.20.2`,
- obraz `php` przebudowal sie poprawnie po przejsciu na Node 20,
- po update zaleznosci warning `Webpack Encore requires version ^1.1.0 || ^2.0.0 of @symfony/stimulus-bridge` zniknal,
- build przechodzi na Node 20 bez obejscia `NODE_OPTIONS=--openssl-legacy-provider`,
- lockfile odswiezyl sie poprawnie i nie zgłasza juz peer warningu `chart.js` vs `symfony/ux-chartjs`,
- build zwraca tez maintenance warning `Browserslist: caniuse-lite is outdated`.

Rekomendowany nastepny krok:

1. Wrocic do `templates/User/Preferentions/index.html.twig`.
2. Przy okazji kolejnej sesji frontendowej rozważyć maintenance task dla `browserslist/caniuse-lite`.
3. Potem wracac do backlogu runtime upgrade albo kolejnych cleanupow read side.
