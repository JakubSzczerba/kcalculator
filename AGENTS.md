# AGENTS.md

Ten plik jest glownym kontekstem operacyjnym dla osob i agentow pracujacych nad projektem `Kcalculator`.

## Cel projektu

`Kcalculator` ma ewoluowac z prototypu licznika kalorii do modularnego systemu nutrition-tech:

- nowoczesna aplikacja webowa dla uzytkownika koncowego,
- gotowa na integracje z inteligentnymi wagami i innymi urzadzeniami,
- oparta o czytelne bounded contexts,
- rozwijana test-first: TDD dla logiki domenowej, Behat dla krytycznych scenariuszy biznesowych.

## Aktualny stan repo

- backend: Symfony 6.4, PHP deklarowane jako `>=8.2`, Docker na `php:8.3-fpm`,
- lokalne CLI w tym workspace dziala na PHP 8.2.26,
- frontend: Twig + Stimulus/Encore na Node 20; pozostaja lokalne fragmenty legacy jQuery,
- persistence: Doctrine ORM z mapowaniem YAML,
- dane produktowe: nadal seed z CSV, ale import zostal juz ukryty za portem `FoodCatalogImportSource`,
- istnieja pierwsze moduly i kontrakty dla `NutritionCatalog`, `MealJournal`, `Metabolism & Goals` oraz read-side `Measurements`,
- projekt ma harness testowy PHPUnit 10.5 i Behat; aktualny baseline to 19 testow / 58 asercji oraz 7 scenariuszy BDD,
- dashboard i dziennik zostaly odciete od najwazniejszych bezposrednich zaleznosci do legacy repozytoriow,
- security baseline zaleznosci jest czysty: `composer audit --locked` nie zglasza advisory,
- brak warstwy API, a pozostala legacy logika nadal siedzi w klasycznym ukladzie `Application/Domain/Infrastructure`.

## Docelowy kierunek

- PHP 8.5 jako docelowy runtime produkcyjny,
- Symfony 8.x jako docelowy framework aplikacyjny,
- architektura: modular monolith, DDD-lite z wyraznymi granicami modulow,
- UI: nowoczesny layout, AJAX i interakcje oparte o komponenty zamiast jQuery spaghetti,
- integracje urzadzen: ports/adapters, osobny ingestion pipeline, brak sprzezenia domeny z transportem.

## Zasady pracy

- Nie robimy big-bang rewrite. Zmiany maja byc iteracyjne i bezpieczne.
- Najpierw porzadkujemy granice modulow i testy, dopiero potem gleboka migracja frameworka.
- Kazda wieksza zmiana powinna miec zapisany kontekst w `.codex/`.
- Nowe elementy domenowe projektujemy per bounded context, nie per warstwa techniczna.
- Zewnetrzne zrodla danych i urzadzenia wchodza przez interfejsy i adaptery.
- CSV pozostaje tylko jako fallback/seed, nie jako docelowe zrodlo prawdy.
- Pracujemy i weryfikujemy zmiany przez Docker; lokalny host PHP nie jest kanonicznym runtime.

## Wstepne bounded contexts

- `Identity & Access`
- `Nutrition Catalog`
- `Meal Journal`
- `Metabolism & Goals`
- `Measurements & Devices`
- `Insights & Recommendations`
- `Backoffice`

Szczegoly znajduja sie w `.codex/bounded-contexts.md`.

## Kolejnosc prac

1. Podniesc runtime Dockerowy z PHP 8.3 do PHP 8.5 bez zmiany majorow frameworka.
2. Domknac dependency baseline na Symfony 6.4: FOS Elastica 7.2, EncoreBundle 2.x i deprecations.
3. Wykonac osobny major slice Doctrine ORM 3 / DBAL 4 / DoctrineBundle 3.
4. Przejsc kolejno przez Symfony 7.4 i Symfony 8.x.
5. Po runtime upgrade wrocic do write-side `Measurements & Devices` i dalszej modularyzacji.

## Stan po sesji 2026-07-18

Wykonane:

- domknieto audit zaleznosci pod Symfony 8 / PHP 8.5,
- MakerBundle podniesiono do `1.67.0`, a PHP Parser do `5.8.0`,
- zaktualizowano podatne patche EasyAdmin, Twig i Symfony 6.4,
- `composer audit --locked` zostal doprowadzony do zera,
- zapisano macierz `.codex/runtime-upgrade-dependency-matrix-2026-07-18.md`,
- pelna regresja przechodzi przez `docker.exe compose`: PHPUnit, Behat, DI lint, Twig lint i Encore build.

Otwarte blokery:

- Docker nadal siedzi na `php:8.3-fpm`,
- FOS Elastica 6.3 blokuje Symfony 7.4,
- Doctrine major wymaga migracji ORM 2 -> 3 i DBAL 3 -> 4,
- DoctrineMigrationsBundle wymaga ponownego audytu przed finalnym Symfony 8,
- pozostaja legacy namespace `Preferention` i luzno typowane encje.

## Kanoniczne pliki projektowe

- `.codex/project-context.md`
- `.codex/roadmap.md`
- `.codex/bounded-contexts.md`
- `.codex/worklog.md`
- `.codex/session-handoff.md`
- `.codex/runtime-upgrade-dependency-matrix-2026-07-18.md`
