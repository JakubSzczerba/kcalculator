# Next Implementation Plan

Data: 2026-04-10

## Cel

Wybrac kolejny bezpieczny slice implementacyjny po domknieciu:

- command side `Meal Journal` dla `add/edit/delete`,
- read-side cleanup dziennika i dashboardu,
- public/auth UI dla `Homepage`, `Login` i `Register`.

Najblizsza sesja ma skupic sie na ryzyku technicznym toolchainu frontendowego i przygotowaniu pod runtime upgrade.

## Aktualna diagnoza

Stan zapisany w backlogach potwierdza sie w kodzie:

- `DailyController` i dashboard zostaly juz odciete od legacy zaleznosci krytycznych dla ostatnich slice'ow,
- `EntryRepository` zostal uproszczony do metod dziennika, a agregaty dashboardu maja osobny reader,
- public/auth UI nie jest juz legacy bottleneckiem,
- najwiekszym aktywnym ryzykiem jest frontendowy toolchain: `php:8.3-fpm`, `node:12.13-alpine`, warning `stimulus-bridge` vs Encore,
- po uporzadkowaniu toolchainu sensowny bedzie dopiero kolejny krok runtime upgrade albo dalszy cleanup formularzy/preferencji.

## Rekomendowana kolejnosc

### 1. P1: spike toolchainu frontendowego

Najpierw zredukowac aktualne ryzyko build/runtime po stronie assetow.

Zakres:

- przejrzec `docker-compose.yml`, `docker/php/Dockerfile` i `package.json`,
- zrownac Dockerowy frontend z wspieranym Node LTS,
- opisac i przetestowac opcje dla warningu `@symfony/stimulus-bridge`,
- zdecydowac, czy tymczasowo zostaje Encore, czy przygotowujemy plan migracji.

Kryteria akceptacji:

- istnieje zapisany plan techniczny z konkretna decyzja lub sekwencja decyzji,
- wiemy, czy warning usuwamy przez downgrade bridge, upgrade Encore, czy zmiane stacku,
- backlog runtime upgrade nie opiera sie juz na domyslach dotyczacych asset pipeline.

### 2. P2: kolejny ekran legacy UI

Po spike'u domknac `templates/User/Preferentions/index.html.twig` i powiazane stany formularza.

Zakres:

- przepisac preferencje do design systemu,
- uporzadkowac walidacje i empty/error states,
- nie wprowadzac nowego inline JS ani nowych zaleznosci frontendowych.

### 3. P3: runtime upgrade prep

To powinien byc osobny spike techniczny, nie laczony z refaktorem domenowym.

Zakres:

- zdecydowac, czy tymczasowo zostaje Encore, czy przygotowujemy migracje do Vite/AssetMapper,
- zrownac wersje Node w Dockerze do wspieranego LTS,
- opisac sciezke usuniecia warningu `@symfony/stimulus-bridge`,
- doprecyzowac backlog upgrade do Symfony 8 / PHP 8.5.

## Proponowany najblizszy slice do implementacji

Jesli celem ma byc najlepszy zwrot z kolejnej sesji, nastepna implementacja powinna objac:

1. audit `docker-compose.yml`, `docker/php/Dockerfile` i `package.json`
2. decyzje dla `Encore` / `@symfony/stimulus-bridge`
3. propozycje nowego obrazu Node oraz miejsca jego uruchamiania
4. zapis planu w `.codex/`
5. dopiero po tym bezpieczne zmiany w toolchainie albo rozpoczecie `Preferentions` UI

## Dlaczego taka kolejnosc

- Projekt deklaruje, ze najpierw porzadkujemy granice modulow i testy, a dopiero potem migracje frameworka.
- Ostatnie slice'y zamknely najpilniejsze debt areas `Meal Journal` i auth/home UI.
- Kolejnym realnym bottleneckiem nie jest juz brak komend czy brak layoutu, tylko niespojny runtime assetow.
- Toolchain upgrade narusza jednoczesnie Docker, Node, Encore i dependency graph, wiec najpierw potrzebuje jawnego spike'a zamiast zmian robionych "przy okazji".

## Ryzyka

- zmiana toolchainu moze naruszyc jednoczesnie build produkcyjny, dev-server i integracje UX/Stimulus,
- zbyt wczesna decyzja o migracji asset stacku moze rozszerzyc scope przed runtime upgrade,
- ekran preferencji nadal siedzi blisko starego modelu aplikacyjnego, wiec warto rozdzielic UI cleanup od wiekszego refaktoru domenowego.
