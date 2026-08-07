# Runtime Upgrade Dependency Matrix

Data: 2026-07-18

## Cel

Domknac audit zaleznosci przed migracja z Symfony 6.4 / PHP 8.3 do Symfony 8.x / PHP 8.5 i zamienic ogolny backlog na kolejnosc malych, weryfikowalnych slice'ow.

## Wynik wykonanego slice'u

Pierwszy techniczny slice zostal wykonany bez zmiany majorow aplikacji:

- `symfony/maker-bundle`: `1.52.0` -> `1.67.0`,
- `nikic/php-parser`: `4.19.5` -> `5.8.0`,
- `easycorp/easyadmin-bundle`: `4.8.6` -> `4.29.14`,
- podatne komponenty Symfony 6.4 zostaly podniesione do bezpiecznych patchy,
- `twig/twig`: `3.8.0` -> `3.28.0`,
- `twig/extra-bundle`: `3.8.0` -> `3.24.0`,
- dodano zaleznosci wymagane przez aktualny EasyAdmin 4.x.

`composer audit --locked` nie zglasza juz znanych podatnosci.

## Macierz kompatybilnosci

| Obszar | Stan repo / linia | Kompatybilnosc docelowa | Decyzja |
| --- | --- | --- | --- |
| PHP | Docker `8.3`, constraint `>=8.2` | Symfony 8 wymaga PHP `>=8.4`; target projektu to `8.5` | nastepny runtime slice podnosi Docker do PHP 8.5 |
| Symfony | `6.4.*` | docelowo `8.x` | przejsc przez `7.4`, usuwajac deprecations przed kazdym majorem |
| MakerBundle | `1.67.0` | wspiera Symfony `6.4`, `7` i `8` | blocker zdjety |
| PHP Parser | `5.8.0` | zgodny z aktualnym MakerBundle i przyszlym PHPUnit | blocker zdjety |
| EasyAdmin | `4.29.14` | linia 4.x wspiera Symfony 8 i Doctrine ORM 3 | pozostaje na 4.x przez upgrade frameworka |
| WebpackEncoreBundle | `2.1.1` | linia 2.x wspiera Symfony 8 | Encore pozostaje przejsciowym pipeline; patch update osobno |
| FOS Elastica | `6.3.1` | linia 7.2 wspiera Symfony 6.4, 7.4 i 8 | podniesc do 7.2 przed Symfony 7.4 |
| Doctrine ORM | `2.17.1` | docelowa linia 3.x | osobny major slice po PHP 8.5 |
| DoctrineBundle | `2.11.1` | linia 3.1+ wspiera Symfony 8, wymaga PHP 8.4, DBAL 4 i Persistence 4 | migrowac razem z ORM 3 / DBAL 4 |
| DoctrineFixturesBundle | `3.5.1` | linia 4.3 wspiera Symfony 8 i ORM 3 | aktualizowac w doctrine major slice |
| DoctrineMigrationsBundle | `3.3.0` | stabilna linia 3.7 wspiera Symfony 8 w wiekszosci komponentow, ale nadal ogranicza `http-kernel` do 7.x | jawny blocker finalnego Symfony 8; ponowic audit przed finalnym skokiem |
| PHPUnit | `10.5` | PHPUnit 11 jest mozliwy po PHP Parser 5; docelowy runtime pozwala tez na nowsza linie | aktualizowac dopiero po przywroceniu Docker verification |

## Kolejnosc wykonawcza

### Slice 1 - security baseline i MakerBundle

Status: completed

- zaktualizowac MakerBundle i PHP Parser,
- zaktualizowac podatne patche bez zmiany majorow,
- doprowadzic `composer audit --locked` do zera.

### Slice 2 - PHP 8.5 w Dockerze

Status: next

- zmienic baze `php:8.3-fpm` na `php:8.5-fpm`,
- zbudowac obraz od zera,
- uruchomic PHPUnit, Behat, `lint:container`, Twig lint i build frontendu,
- usunac problemy kompatybilnosci PHP bez podnoszenia jeszcze Symfony.

### Slice 3 - dependency baseline na Symfony 6.4

- podniesc pozostale komponenty Symfony 6.4 do ostatnich patchy,
- podniesc FOS Elastica do 7.2,
- podniesc WebpackEncoreBundle w linii 2.x,
- wlaczyc jawny check deprecations i ponowic `composer audit`.

### Slice 4 - Doctrine major

- Doctrine ORM 2 -> 3,
- DBAL 3 -> 4,
- DoctrineBundle 2 -> 3.1+,
- Persistence 3 -> 4,
- FixturesBundle 3 -> 4,
- zweryfikowac mapowania YAML, repozytoria, migracje i EasyAdmin.

### Slice 5 - Symfony 7.4

- usunac deprecations 6.4,
- przestawic wszystkie komponenty Symfony na 7.4,
- zaktualizowac recipes i wykonac pelna regresje.

### Slice 6 - Symfony 8.x

- ponowic audit DoctrineMigrationsBundle i innych bundle,
- usunac deprecations 7.4,
- przestawic wszystkie komponenty Symfony na jedna linie 8.x,
- wykonac pelna regresje oraz finalny audit security.

## Weryfikacja tej sesji

Wykonane:

- `docker.exe compose run --rm --no-deps php composer install --no-interaction`,
- PHPUnit: `19 tests, 58 assertions`,
- Behat: `7 scenarios, 7 passed`,
- `lint:container` - sukces,
- Twig lint: wszystkie 10 szablonow poprawne,
- Encore production build - sukces,
- `composer audit --locked` - zero advisory,
- `composer validate --no-check-publish --check-lock` - poprawny z dwoma istniejacymi warningami constraintow,
- `git diff --check` - brak bledow whitespace.

Uwagi:

- linuksowy wrapper `docker` nie jest dostepny, ale Docker Desktop i Compose dzialaja przez `docker.exe`,
- build nadal zglasza maintenance warning `Browserslist: caniuse-lite is outdated`,
- host PHP nie ma `ext-mbstring`, ale nie jest kanonicznym runtime.
