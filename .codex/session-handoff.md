# Session Handoff

Data: 2026-04-08

## Co jest zrobione

- zapisano kontekst projektu, bounded contexts i roadmape,
- dodano pierwszy nowy modul: `NutritionCatalog`,
- stary import CSV zostal zamieniony na `food-catalog:import` z aliasem `csv:import`,
- dodano harness testowy: PHPUnit 10.5 + Behat,
- pierwsze testy przechodza w Dockerze,
- README zostal uzupelniony o komendy build/test przez Docker.

## Najwazniejsze zmienione obszary

- `AGENTS.md`
- `.codex/project-context.md`
- `.codex/roadmap.md`
- `.codex/worklog.md`
- `config/services.yaml`
- `src/Application/Controller/DailyController.php`
- `src/NutritionCatalog/*`
- `composer.json`
- `composer.lock`
- `README.md`

## Co zweryfikowano

Uruchomione komendy:

- `docker compose build php`
- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --colors`
- `docker compose run --rm --no-deps php php bin/console list food-catalog`

Wynik:

- unit testy przechodza,
- Behat smoke przechodzi,
- nowa komenda Symfony jest widoczna.

## Otwarte problemy

1. `docker/php/Dockerfile` nadal uzywa `php:8.3-fpm`.
2. Dockerfile instaluje legacy Node 18 i globalny `yarn`.
3. `symfony/maker-bundle` blokuje wejscie na `phpunit 11`, bo trzyma `nikic/php-parser ^4.11`.
4. `Meal Journal` nadal siedzi w starej architekturze.
5. Kontrolery nadal sa zbyt grube i korzystaja z infrastruktury bezposrednio.

## Priorytety na nowa sesje

### P1

- zaczac wydzielanie `Meal Journal`,
- odciac `DailyController` od `EntityManagerInterface`,
- zaprojektowac use case dodawania wpisu posilku po nowemu,
- dopisac testy TDD dla nowej logiki wpisu.

### P2

- dodac kolejny port/interfejs dla `Nutrition Catalog`,
- przygotowac model metadanych produktu: `source`, `externalId`, `isVerified`, `lastSyncedAt`,
- rozpisac migracje od starego `Product` do nowego modelu bez big-bang rewrite.

### P3

- przygotowac osobny backlog modernizacji Dockera,
- zaplanowac przejscie z legacy frontendu do nowoczesnych interakcji.

## Zalecany punkt wejscia

1. Przeczytac `AGENTS.md`.
2. Przeczytac `.codex/session-handoff.md`.
3. Otworzyc `.codex/roadmap.md` i `.codex/bounded-contexts.md`.
4. Wejsc w `src/NutritionCatalog/` i `src/Application/Controller/DailyController.php`.
5. Kontynuowac od refaktoru `Meal Journal`.

## Zasady operacyjne

- pracowac przez Docker,
- nie polegac na lokalnym PHP hosta,
- nie robic big-bang rewrite,
- kazda wieksza decyzje dopisywac do `.codex/`.

