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
- frontend: Twig + jQuery + Encore,
- persistence: Doctrine ORM z mapowaniem YAML,
- dane produktowe: nadal seed z CSV, ale import zostal juz ukryty za portem `FoodCatalogImportSource`,
- istnieje zalazek bounded context `NutritionCatalog`,
- projekt ma juz bazowy harness testowy: PHPUnit 10.5 i Behat,
- brak warstwy API, a wiekszosc starej logiki nadal siedzi w klasycznym ukladzie `Application/Domain/Infrastructure`.

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

1. Zapisac kontekst, plan i decyzje architektoniczne.
2. Dodac fundament testow i reguly TDD/BDD.
3. Wydzielic pierwszy modul domenowy: katalog produktow i dziennik posilkow.
4. Przygotowac migracje runtime do Symfony 8 / PHP 8.5.
5. Zmodernizowac UI oraz przygotowac kontrakty pod urzadzenia.

## Stan po sesji 2026-04-08

Wykonane:

- zapisano kontekst projektu i roadmape w `.codex/`,
- dodano `food-catalog:import` z aliasem `csv:import`,
- wprowadzono `NutritionCatalog` jako pierwszy nowy modul,
- dodano PHPUnit i Behat oraz pierwsze testy smoke/unit,
- zweryfikowano nowe zmiany w kontenerze PHP.

Otwarte blokery:

- Docker nadal siedzi na `php:8.3-fpm` i legacy Node 18,
- `symfony/maker-bundle` blokuje przejscie na `phpunit 11`,
- stare kontrolery i encje nadal sa mocno sprzezone z infrastruktura,
- `Meal Journal` nie zostal jeszcze wydzielony.

## Kanoniczne pliki projektowe

- `.codex/project-context.md`
- `.codex/roadmap.md`
- `.codex/bounded-contexts.md`
- `.codex/worklog.md`
- `.codex/session-handoff.md`
