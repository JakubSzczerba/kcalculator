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
