# Next Implementation Plan

Data: 2026-04-29

## Cel

Wyznaczyc kolejny tor prac po domknieciu dwoch bezpiecznych slice'ow:

- `Metabolism & Goals` dla `Preferentions`,
- pierwszego read-side `Measurements & Devices` dla dashboardowej historii wagi.

Kolejna sesja powinna przejsc z modularyzacji widocznych debt areas do przygotowania technicznego pod `Symfony 8 / PHP 8.5` bez mieszania tego z nowym slice'em domenowym.

## Aktualna diagnoza

Stan repo po zmianach z 2026-04-28 i 2026-04-29:

- `NutritionCatalog`, `MealJournal`, `Preferentions` i dashboardowa historia wagi maja juz pierwsze kontrakty, testy i artefakty `.codex/`,
- `DashboardController` nie zalezy juz od legacy agregatow wpisow ani od surowego `WeightHistoryRepository`,
- frontendowy shell, auth/public UI i ekran preferencji zostaly uporzadkowane,
- test harness dziala w Dockerze: PHPUnit, Behat i `lint:container` przechodza,
- glowny pozostaly tor ryzyka to runtime i dependency graph:
  - Docker nadal siedzi na `php:8.3-fpm`,
  - `composer.json` nadal deklaruje `php >=8.2` i `symfony 6.4.*`,
  - backlog nadal zawiera pakiety wymagajace audytu przed `Symfony 8`.

## Rekomendowana kolejnosc

### 1. P1: runtime upgrade prep

To powinien byc najblizszy glowny slice.

Zakres:

- odswiezyc `.codex/upgrade-backlog.md` na bazie realnego stanu po ostatnich slice'ach,
- zrobic audit blockerow w `composer.json` i configu pod:
  - `php 8.5`,
  - `symfony 8.x`,
  - `doctrine/*`,
  - `easycorp/easyadmin-bundle`,
  - `friendsofsymfony/elastica-bundle`,
  - `symfony/maker-bundle`,
  - `symfony/webpack-encore-bundle`,
- rozpisac male kroki wykonawcze zamiast jednego skoku major.

Minimalny wynik:

- nowy plan upgrade z konkretnymi blockerami,
- przypisanie blockerow do kolejnych slice'ow,
- decyzja, czy asset pipeline zostaje na Encore przez caly upgrade Symfony.

### 2. P2: runtime alignment

Po audycie wejsc w pierwszy techniczny slice przygotowawczy.

Zakres:

- zdecydowac, czy najpierw podnosimy deklaracje PHP i kompatybilnosc kodu, czy czyscimy pakiety blokujace `Symfony 8`,
- przygotowac osobny plan dla przejscia z `php:8.3-fpm` do linii docelowej,
- nie laczyc tego jeszcze z nowym bounded context.

### 3. P3: maintenance i follow-up

Male zadania po glownej sciezce:

- rozpisac maintenance task dla `Browserslist/caniuse-lite`,
- rozstrzygnac, czy po upgrade prep wracamy do rename debt `Preferention`, czy do write-side `Measurements`.

## Proponowane 3 najblizsze sesje

### Sesja 1

Cel:

- audit i backlog runtime upgrade.

Kroki:

1. przejrzec `composer.json`, obrazy Dockerowe i zaleznosci Symfony/Doctrine,
2. wypisac blokery i miejsca sprzezone z frameworkiem,
3. zaktualizowac `.codex/upgrade-backlog.md`,
4. zapisac decyzje wykonawcze w osobnym artefakcie `.codex/`.

### Sesja 2

Cel:

- pierwszy techniczny slice pod upgrade.

Kroki:

1. usunac lub odizolowac pierwszy konkretny blocker z dependency graphu,
2. utrzymac zielone testy i `lint:container`,
3. dopisac wynik do worklogu i backlogu upgrade.

### Sesja 3

Cel:

- przygotowac runtime alignment Docker/PHP.

Kroki:

1. zdecydowac docelowy krok po `php:8.3-fpm`,
2. sprawdzic konsekwencje dla zaleznosci i narzedzi developerskich,
3. dopiero potem planowac faktyczne podniesienie runtime'u.

## Ryzyka

1. Zbyt szybkie wejscie w podnoszenie wersji bez audytu pakietow rozszerzy scope i utrudni cofanie zmian.
2. Mieszanie cleanupu dependency graphu z nowym slice'em domenowym znowu rozmyje priorytety.
3. Przedwczesne wejscie w write-side `Measurements & Devices` otworzy osobny, wiekszy strumien prac zanim runtime bedzie gotowy.

## Rekomendacja wykonawcza

Najblizsza implementacja powinna byc juz techniczna, nie domenowa: audit runtime upgrade i rozpisanie blockerow na male, wykonywalne kroki. Dopiero po tym warto decydowac, czy pierwszy ruch idzie w pakiety, kompatybilnosc PHP, czy obraz Dockerowy.
