# Next Implementation Plan

Data: 2026-07-18

## Cel

Kontynuowac migracje techniczna do PHP 8.5 / Symfony 8.x malymi slice'ami, bez mieszania runtime upgrade z nowym bounded context ani z Doctrine major w jednym kroku.

Audit, security baseline, pierwszy pakietowy slice i regresja Dockerowa sa zakonczone.

## Aktualny baseline

- aplikacja: Symfony `6.4.*`,
- deklaracja PHP: `>=8.2`,
- Docker: `php:8.3-fpm`,
- frontend: Node 20, Encore 4.7, Webpack 5,
- testy: PHPUnit `19 tests, 58 assertions`,
- BDD: Behat `7 scenarios, 7 passed`,
- `lint:container`: zielony,
- Twig lint: 10 szablonow poprawnych,
- Encore production build: zielony,
- `composer audit --locked`: zero advisory.

## Slice 1 - PHP 8.5 runtime alignment

Status: next

Zakres:

1. zmienic baze obrazu na `php:8.5-fpm`,
2. przebudowac obraz od zera,
3. nie zmieniac jeszcze constraintow Symfony ani majorow Doctrine,
4. naprawic wylacznie problemy kompatybilnosci PHP i rozszerzen,
5. po zielonej regresji podniesc deklaracje PHP w `composer.json`,
6. zapisac wynik w `.codex/`.

Definition of Done:

- obraz PHP 8.5 buduje sie powtarzalnie,
- `composer install` przechodzi w kontenerze,
- PHPUnit, Behat, `lint:container`, Twig lint i Encore build sa zielone,
- `composer audit --locked` nadal zwraca zero advisory,
- brak nieudokumentowanych zmian majorow.

## Slice 2 - dependency baseline na Symfony 6.4

Zakres:

- FOS Elastica `6.3` -> `7.2`,
- WebpackEncoreBundle do aktualnego patcha linii 2.x,
- pozostale bezpieczne patche Symfony 6.4,
- jawny check deprecations,
- maintenance `Browserslist/caniuse-lite` jako osobny maly task.

## Slice 3 - Doctrine major

Zakres:

- Doctrine ORM `2` -> `3`,
- DBAL `3` -> `4`,
- DoctrineBundle `2` -> `3.1+`,
- Persistence `3` -> `4`,
- FixturesBundle `3` -> `4`,
- weryfikacja mapowan YAML, migracji, repozytoriow i EasyAdmin.

Ten slice nie moze byc laczony ze skokiem Symfony major.

## Slice 4 - Symfony 7.4

Zakres:

- usunac deprecations Symfony 6.4,
- przestawic wszystkie komponenty Symfony na jedna linie 7.4,
- zaktualizowac recipes,
- wykonac pelna regresje.

## Slice 5 - Symfony 8.x

Zakres:

- ponowic audit DoctrineMigrationsBundle i innych bundle,
- usunac deprecations Symfony 7.4,
- przestawic komponenty na jedna wspierana linie 8.x,
- wykonac pelna regresje i security audit.

## Po runtime upgrade

Kolejny tor domenowy:

1. write-side `Measurements & Devices`,
2. model pomiaru i sesji wazenia,
3. port ingestii urzadzen,
4. adapter inteligentnej wagi,
5. oddzielenie raw payload, interpretacji i zatwierdzenia przez uzytkownika.

Debt do zaplanowania osobno:

- rename `Preferention`,
- luzne typowanie legacy encji,
- dalsze usuwanie jQuery,
- docelowa decyzja o asset pipeline.

## Dokumenty powiazane

- `.codex/runtime-upgrade-dependency-matrix-2026-07-18.md`
- `.codex/upgrade-backlog.md`
- `.codex/roadmap.md`
- `.codex/session-handoff.md`
