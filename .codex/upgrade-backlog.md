# Upgrade Backlog

## Stan na 2026-07-18

- repo deklaruje Symfony `6.4.*` i PHP `>=8.2`,
- Docker buduje sie na `php:8.3-fpm`,
- lokalny workspace uruchamia CLI na PHP `8.2.26`,
- target projektu: Symfony 8.x + PHP 8.5.

Postep od 2026-04-08:

- PHPUnit i Behat sa juz obecne i przechodza w Dockerze,
- `MealJournal`, `Preferentions` i dashboardowa historia wagi maja juz osobne kontrakty read/write lub read-side,
- frontendowy toolchain zostal ustabilizowany na Node 20 i `@symfony/webpack-encore 4.7.0`,
- pozostaje maintenance warning `Browserslist: caniuse-lite is outdated`.
- zaktualizowano MakerBundle do `1.67.0` i PHP Parser do `5.8.0`,
- zaktualizowano podatne patche Symfony 6.4, Twig i EasyAdmin bez zmiany majorow,
- `composer audit --locked` nie zglasza znanych podatnosci,
- szczegolowa macierz kompatybilnosci jest w `.codex/runtime-upgrade-dependency-matrix-2026-07-18.md`.

## Krytyczne blokery

1. Runtime nadal nie jest na sciezce docelowej:
   - Docker: `php:8.3-fpm`,
   - host CLI: `8.2.26`,
   - target: `8.5`.
2. Constrainty aplikacji nadal siedza na linii Symfony `6.4.*` i `php >=8.2`.
3. Dependency graph ma juz rozpoznana sciezke, ale wymaga kolejnych major slice'ow:
   - `friendsofsymfony/elastica-bundle 6.3` -> `7.2`,
   - `doctrine/orm 2` -> `3`,
   - `doctrine/dbal 3` -> `4`,
   - `doctrine/doctrine-bundle 2` -> `3.1+`,
   - `doctrine/doctrine-fixtures-bundle 3` -> `4`,
   - ponowny audit `doctrine/doctrine-migrations-bundle` przed Symfony 8.
4. Nadal istnieja miejsca mocniej przyklejone do legacy warstwy frameworkowej:
   - stare namespace i nazewnictwo `Preferention`,
   - czesc encji Doctrine z luźnym typowaniem,
   - kontrolery i formularze oparte o legacy shape danych.

## Zalecana sekwencja

### Etap A - Audit i mapa blockerow

Status: completed

- przejrzec `composer.json`, Docker i config Symfony po ostatnich slice'ach,
- wypisac realne blokery dla `php 8.5` i `symfony 8.x`,
- przypisac blokery do malych krokow wykonawczych,
- zdecydowac, czy Encore zostaje tymczasowo do konca upgrade.

### Etap B - Security baseline i przygotowanie pakietow

Status: in progress

- wykonane: MakerBundle / PHP Parser oraz security patches,
- nastepne: PHP 8.5 w Dockerze,
- potem: FOS Elastica 7.2 i aktualny patch WebpackEncoreBundle,
- nastepnie: Doctrine ORM 3 / DBAL 4 / DoctrineBundle 3.

### Etap C - Runtime alignment

Status: next

- przygotowac przejscie obrazu Dockerowego z `php:8.3-fpm` na kolejna linie zgodna z planem upgrade,
- zrownac deklaracje PHP z realnym wspieranym runtime,
- utrzymac zielone testy i `lint:container` po kazdym malym kroku.

### Etap D - Skok frameworka

- wejsc najpierw na Symfony 7.4, a dopiero potem na docelowa linie 8.x,
- przejrzec Doctrine, EasyAdmin i FOS Elastica pod finalna kompatybilnosc,
- odpalic komplet testow, smoke i scenariusze Behat po zmianie.

## Najblizszy rekomendowany slice

Najblizsza sesja powinna wykonac runtime alignment:

1. podniesc obraz PHP z `8.3-fpm` do `8.5-fpm`,
2. naprawic wylacznie problemy kompatybilnosci PHP,
3. wykonac pelna regresje przez `docker.exe compose`,
4. nie podnosic jeszcze Symfony ani Doctrine major.

Wykonane juz po audycie:

- usunieto `composer/package-versions-deprecated`,
- usunieto `doctrine/annotations`,
- usunieto `symfony/proxy-manager-bridge` wraz z `friendsofphp/proxy-manager-lts` i `laminas/laminas-code`,
- routing przestawiono z loadera `annotation` na `attribute`.
- MakerBundle podniesiono do `1.67.0`, a PHP Parser do `5.8.0`.
- zaktualizowano podatne patche EasyAdmin, Symfony 6.4 i Twig.
- `composer audit --locked` zostal doprowadzony do zera.

## Zasada wykonawcza

Target produkcyjny pozostaje `PHP 8.5`, ale wykonawczo najpierw trzeba przygotowac kod i dependency graph do bezpiecznego wejscia w `Symfony 8`. Upgrade ma byc seria malych, weryfikowalnych krokow, a nie jednym skokiem.
