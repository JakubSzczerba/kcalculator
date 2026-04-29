# Runtime Upgrade Audit

Data: 2026-04-29

## Cel

Zmapowac realne blokery i pierwszy bezpieczny kierunek prac pod `Symfony 8 / PHP 8.5` po domknieciu ostatnich slice'ow domenowych i read-side dashboardu.

## Stan wyjsciowy

- `composer.json` nadal deklaruje `php >=8.2` i `symfony 6.4.*`,
- Docker dla serwisu `php` nadal buduje sie na `php:8.3-fpm`,
- asset pipeline jest ustabilizowany na Node 20 i `@symfony/webpack-encore 4.7.0`,
- PHPUnit, Behat i `lint:container` przechodza w Dockerze,
- routing kontrolerow byl jeszcze ladowany jako `annotation`, mimo ze kod juz korzysta z atrybutow `#[Route]`.

## Potwierdzone blokery

### 1. Constrainty runtime

- `composer.json`:
  - `php: ">=8.2.0"`
  - `extra.symfony.require: "6.4.*"`
- `docker/php/Dockerfile`:
  - `FROM php:8.3-fpm`

Wniosek:

- upgrade nie zacznie sie od samego obrazu Dockera; najpierw trzeba rozpisac kompatybilnosc pakietow i kodu.

### 2. Pakiety wymagajace osobnego audytu

Zidentyfikowane w `composer.json` / `composer.lock`:

- `doctrine/orm` `2.17.1`
- `doctrine/doctrine-bundle` `2.11.1`
- `doctrine/doctrine-migrations-bundle` `3.3.0`
- `easycorp/easyadmin-bundle` `4.8.6`
- `friendsofsymfony/elastica-bundle` `6.3.1`
- `symfony/maker-bundle` `1.52.0`
- `symfony/proxy-manager-bridge` `6.4.x-dev`
- `symfony/webpack-encore-bundle` `2.1.1`
- dev bundles `symfony/debug-bundle` / `symfony/web-profiler-bundle` w linii `6.4`

Wniosek:

- najpierw trzeba ustalic, ktore z tych pakietow maja bezposrednia sciezke do Symfony 8, a ktore trzeba zaktualizowac lub odizolowac w osobnych slice'ach.

### 2a. Pakiety zdjete od razu po audycie

Udalo sie usunac bez regresji:

- `composer/package-versions-deprecated`
- `doctrine/annotations`

Powod:

- repo nie mialo lokalnych uzyc tych pakietow,
- Doctrine mapping jest prowadzone przez YAML,
- routing zostal juz przestawiony na atrybuty.

### 3. Legacy praktyki frameworkowe

Potwierdzone w kodzie:

- route loader w `config/routes/annotations.yaml` byl ustawiony na `type: annotation`,
- kontrolery importowaly `Symfony\Component\Routing\Annotation\Route`,
- nazewnictwo `Preferention` nadal pozostaje dlugiem migracyjnym,
- czesc encji i repozytoriow nadal ma legacy shape i luzniejsze typowanie.

Wniosek:

- te punkty nie sa glownym blockerem dependency graphu, ale trzeba je zdejmowac iteracyjnie, bo inaczej koszt upgrade wzrosnie przy kazdym dotknieciu kontrolerow i configu.

## Wykonany maly cleanup w ramach audytu

Weszlo od razu:

- `config/routes/annotations.yaml` przestawiono na `type: attribute`,
- kontrolery przeszly z importu `Routing\\Annotation\\Route` na `Routing\\Attribute\\Route`.

Powod:

- to maly, bezpieczny krok zgodny z aktualnym stylem kodu,
- redukuje jeden z legacy elementow przed wiekszym upgrade.

## Rekomendowana kolejnosc kolejnych slice'ow

### Slice 1: dependency audit

Zakres:

- rozpisac kompatybilnosc pakietow z Symfony 8,
- ustalic pierwszy konkretny blocker do zdjecia,
- zapisac wynik jako wykonawczy backlog.

### Slice 2: pierwszy blocker techniczny

Kandydaci:

- `symfony/proxy-manager-bridge`,
- `symfony/maker-bundle` i jego relacja do `nikic/php-parser`.

### Slice 3: runtime alignment

Zakres:

- przygotowac przejscie `php:8.3-fpm` do kolejnej linii runtime po wstepnym oczyszczeniu dependency graphu.

## Rekomendacja wykonawcza

Nastepny commit techniczny nie powinien jeszcze podnosic Symfony ani PHP globalnie. Najlepszy zwrot da audit kompatybilnosci pakietow i zdjecie pierwszego pojedynczego blockera z dependency graphu.
