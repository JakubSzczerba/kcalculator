# Frontend Toolchain Spike

Data: 2026-04-14

## Cel

Zredukowac aktywne ryzyko w asset pipeline przed dalszym runtime upgrade i kolejnymi frontendowymi slice'ami.

## Co sprawdzono

- `docker-compose.yml`: serwis `encore` korzystal z `node:12.13-alpine`,
- `docker/php/Dockerfile`: obraz PHP instalowal Node 18 przez Nodesource,
- `package.json` i `node_modules`: `@symfony/webpack-encore` jest na `1.5.0`, a `@symfony/stimulus-bridge` na `3.2.2`,
- build w Dockerze przechodzi, ale reprodukuje warning kompatybilnosci `stimulus-bridge` vs Encore.

## Wynik spike'a

1. Problem nie lezy w konfiguracji webpacka, tylko w wersjach pakietow JS.
2. Warning pochodzi z samego `@symfony/webpack-encore`, ktore przy wersji `1.5.0` wspiera tylko `@symfony/stimulus-bridge` w zakresie `^1.1.0 || ^2.0.0`.
3. Repo mialo dwa rozne runtime'y Node dla tego samego obszaru: osobny dla `encore` i osobny w obrazie PHP.
4. Build nadal dziala funkcjonalnie, ale obecny stan nie daje wiarygodnej bazy pod dalszy upgrade Symfony 8 / PHP 8.5.

## Decyzja

- Tymczasowo zostajemy przy Encore.
- W tej sesji porzadkujemy runtime kontenerowy i dokumentujemy problem wersji.
- Nie robimy teraz pelnej migracji na Vite/AssetMapper ani przypadkowego downgrade'u `@symfony/stimulus-bridge`.

## Zmiana wykonana teraz

- serwis `encore` zostal podniesiony z `node:12.13-alpine` do `node:20-alpine`,
- `docker/php/Dockerfile` zostal zrownany do Node 20 i uproszczony przez usuniecie zdublowanej instalacji `nodejs`,
- kontrolowany update zaleznosci JS podniosl `@symfony/webpack-encore` do `4.7.0`, `webpack` do `5.106.1` i `webpack-cli` do `5.1.4`,
- usunieto tymczasowy `NODE_OPTIONS=--openssl-legacy-provider`, bo po update build wraca do stabilnego stanu na Node 20,
- usunieto legacy pakiet `stimulus`, a lokalny kontroler zostal przepiety na import z `@hotwired/stimulus`,
- `chart.js` zostal przypiety do `3.8.0`, zeby pozostac w zakresie wspieranym przez `symfony/ux-chartjs`.

## Co zostaje otwarte

1. Build zglasza przeterminowane dane `browserslist/caniuse-lite`; to jest osobny maintenance task, nie blocker dla tej sesji.
2. Encore pozostaje stackiem przejsciowym do czasu szerszego runtime upgrade i decyzji o docelowym asset pipeline.

## Rekomendowana sekwencja

1. Uznac toolchain assetow za ustabilizowany na poziomie potrzeb obecnej migracji.
2. Wrocic do `templates/User/Preferentions/index.html.twig`.
3. Osobno zaplanowac maintenance task dla `browserslist/caniuse-lite`.
