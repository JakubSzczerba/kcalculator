# Upgrade Backlog

## Stan na 2026-04-08

- repo deklaruje Symfony 6.4 i PHP `>=8.2`,
- Docker buduje sie na `php:8.3-fpm`,
- lokalny workspace uruchamia CLI na PHP 8.2.26,
- target projektu: Symfony 8.x + PHP 8.5.

## Krytyczne blokery

1. Brak testow automatycznych.
2. Niespojne runtime'y lokalne i kontenerowe.
3. Stare zaleznosci frontendowe oraz legacy jQuery w layoutach.
4. Pakiety i konfiguracja wymagajace przegladu przed skokiem major:
   - `composer/package-versions-deprecated`
   - `doctrine/annotations`
   - `symfony/proxy-manager-bridge`
   - `symfony/maker-bundle`
   - `symfony/webpack-encore-bundle`
   - `friendsofsymfony/elastica-bundle`

## Zalecana sekwencja

### Etap A - Bezpieczna baza

- dodac PHPUnit i Behat,
- uporzadkowac nazewnictwo i katalogi,
- odpalic aplikacje na "clean" Symfony 6.4 latest patch,
- zrobic audit deprecations.

### Etap B - Przygotowanie pod Symfony 8

- podniesc kod do kompatybilnosci z PHP 8.4+,
- usunac API i praktyki oznaczone jako deprecated,
- odkleic logike od warstw frameworkowych tam, gdzie utrudnia upgrade.

### Etap C - Upgrade zaleznosci

- zaktualizowac constrainty `symfony/*` do `^8.0`,
- przejrzec Doctrine i EasyAdmin pod kompatybilnosc,
- zdecydowac, czy Encore zostaje tymczasowo, czy przechodzimy na AssetMapper/Vite.

### Etap D - Runtime 8.5

- ustawic obraz PHP 8.5 w Dockerze,
- zrownac lokalne i CI runtime,
- uruchomic komplet testow, smoke i scenariusze Behat.

## Zasada wykonawcza

Target produkcyjny pozostaje `PHP 8.5`, ale pierwsza fala zmian ma doprowadzic kod do zgodnosci `PHP 8.4+ / Symfony 8`, bo to zmniejsza ryzyko i nie blokuje bieżącego refaktoru domenowego.

