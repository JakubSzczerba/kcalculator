# Measurements Dashboard Slice

Data: 2026-04-29

## Cel slice'u

Wydzielic pierwszy read-side dla `Measurements & Devices`, tak aby dashboard przestal zalezec bezposrednio od legacy `WeightHistoryRepository` i recznego skladania danych wykresu w kontrolerze.

## Co weszlo do zakresu

- nowy port odczytowy `WeightHistoryChartReader`,
- view model `WeightHistoryChart` i factory normalizujace dane wykresu,
- adapter `DoctrineWeightHistoryChartReader` oparty o aktualna encje `WeightHistory`,
- odchudzony `DashboardController`, ktory pobiera gotowy model trendu zamiast wykonywac dwa osobne zapytania i petle mapujace,
- cleanup `WeightHistoryRepository`, z ktorego usunieto dashboardowe metody pomocnicze,
- test jednostkowy dla factory read modelu.

## Decyzje

### 1. Read-side pozostaje adapterem do legacy `WeightHistory`

Powod:

- celem slice'u jest zmniejszenie sprzezenia dashboardu, a nie pelne przemodelowanie zapisu pomiarow,
- obecna encja i tabela moga chwilowo pozostac adapterem infrastrukturalnym.

Konsekwencja:

- przyszly rename do `WeightMeasurement` powinien wejsc jako osobny krok wraz z decyzja o nowym modelu write-side.

### 2. Shape read modelu jest dopasowany do potrzeb dashboardu

Powod:

- dashboard potrzebuje wyłącznie uporzadkowanych etykiet dat i wag,
- wprowadzanie bogatszego modelu punktow pomiarowych na tym etapie zwiekszyloby scope bez realnej wartosci wykonawczej.

Konsekwencja:

- kolejny krok moze rozbudowac ten obszar o bardziej ogolny snapshot/trend reader, jesli wejdzie `Insights & Recommendations`.

### 3. Nie otwieramy jeszcze ingestii urzadzen

Powod:

- bounded context `Measurements & Devices` ma na razie wejsc od strony bezpiecznego read-side,
- ingestia, klasyfikacja payloadow i rejestracja urzadzen wymagaja osobnego kontraktu i osobnych scenariuszy BDD.

## Weryfikacja planowana dla tego slice'u

- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --config=behat.yml.dist --colors`
- `docker compose run --rm --no-deps php php bin/console lint:container`

## Nastepny sensowny krok

Po ustabilizowaniu tego slice'u mozna:

- zdecydowac, czy dashboard ma dostac osobny model `ProgressSnapshot`,
- albo przejsc do backlogu i blockerow runtime upgrade dla Symfony 8 / PHP 8.5.
