# Upgrade Backlog

## Stan na 2026-04-29

- repo deklaruje Symfony `6.4.*` i PHP `>=8.2`,
- Docker buduje sie na `php:8.3-fpm`,
- lokalny workspace uruchamia CLI na PHP `8.2.26`,
- target projektu: Symfony 8.x + PHP 8.5.

Postep od 2026-04-08:

- PHPUnit i Behat sa juz obecne i przechodza w Dockerze,
- `MealJournal`, `Preferentions` i dashboardowa historia wagi maja juz osobne kontrakty read/write lub read-side,
- frontendowy toolchain zostal ustabilizowany na Node 20 i `@symfony/webpack-encore 4.7.0`,
- pozostaje maintenance warning `Browserslist: caniuse-lite is outdated`.

## Krytyczne blokery

1. Runtime nadal nie jest na sciezce docelowej:
   - Docker: `php:8.3-fpm`,
   - host CLI: `8.2.26`,
   - target: `8.5`.
2. Constrainty aplikacji nadal siedza na linii Symfony `6.4.*` i `php >=8.2`.
3. Dependency graph wymaga audytu kompatybilnosci przed `Symfony 8`:
   - `doctrine/orm ^2.8`
   - `symfony/maker-bundle`
   - `symfony/webpack-encore-bundle`
   - `friendsofsymfony/elastica-bundle`
4. Nadal istnieja miejsca mocniej przyklejone do legacy warstwy frameworkowej:
   - stare namespace i nazewnictwo `Preferention`,
   - czesc encji Doctrine z luźnym typowaniem,
   - kontrolery i formularze oparte o legacy shape danych.

## Zalecana sekwencja

### Etap A - Audit i mapa blockerow

- przejrzec `composer.json`, Docker i config Symfony po ostatnich slice'ach,
- wypisac realne blokery dla `php 8.5` i `symfony 8.x`,
- przypisac blokery do malych krokow wykonawczych,
- zdecydowac, czy Encore zostaje tymczasowo do konca upgrade.

### Etap B - Przygotowanie kodu i pakietow

- podniesc kod do kompatybilnosci z PHP 8.4+,
- usunac API i praktyki oznaczone jako deprecated,
- odkleic pozostale miejsca, gdzie logika nadal trzyma sie legacy kontrolerow i formularzy,
- rozbroic pierwszy pakiet lub constraint blokujacy `Symfony 8`.

### Etap C - Runtime alignment

- przygotowac przejscie obrazu Dockerowego z `php:8.3-fpm` na kolejna linie zgodna z planem upgrade,
- zrownac deklaracje PHP z realnym wspieranym runtime,
- utrzymac zielone testy i `lint:container` po kazdym malym kroku.

### Etap D - Skok frameworka

- zaktualizowac constrainty `symfony/*` do docelowej linii,
- przejrzec Doctrine, EasyAdmin i FOS Elastica pod finalna kompatybilnosc,
- odpalic komplet testow, smoke i scenariusze Behat po zmianie.

## Najblizszy rekomendowany slice

Najblizsza sesja powinna domknac Etap A:

1. audit blockerow,
2. odswiezenie backlogu upgrade,
3. zapis konkretnej sekwencji malych commitow/slice'ow,
4. bez ruszania jeszcze docelowego PHP 8.5 w Dockerze.

Wykonane juz po audycie:

- usunieto `composer/package-versions-deprecated`,
- usunieto `doctrine/annotations`,
- usunieto `symfony/proxy-manager-bridge` wraz z `friendsofphp/proxy-manager-lts` i `laminas/laminas-code`,
- routing przestawiono z loadera `annotation` na `attribute`.

## Zasada wykonawcza

Target produkcyjny pozostaje `PHP 8.5`, ale wykonawczo najpierw trzeba przygotowac kod i dependency graph do bezpiecznego wejscia w `Symfony 8`. Upgrade ma byc seria malych, weryfikowalnych krokow, a nie jednym skokiem.
