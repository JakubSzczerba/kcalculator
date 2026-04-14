# Next Implementation Plan

Data: 2026-04-14

## Cel

Wybrac kolejny bezpieczny slice implementacyjny po domknieciu:

- command side `Meal Journal` dla `add/edit/delete`,
- read-side cleanup dziennika i dashboardu,
- public/auth UI dla `Homepage`, `Login` i `Register`.

Najblizsza sesja ma wrocic do kolejnego bezpiecznego slice'u UI po domknieciu frontendowego toolchainu i przygotowac grunt pod runtime upgrade.

## Aktualna diagnoza

Stan zapisany w backlogach potwierdza sie w kodzie:

- `DailyController` i dashboard zostaly juz odciete od legacy zaleznosci krytycznych dla ostatnich slice'ow,
- `EntryRepository` zostal uproszczony do metod dziennika, a agregaty dashboardu maja osobny reader,
- public/auth UI nie jest juz legacy bottleneckiem,
- aktywne ryzyko toolchainu frontendowego zostalo zredukowane: Node 20 jest zrownany w Dockerze, `Encore` zostal podniesiony do `4.7.0`, a build przechodzi bez warningu `stimulus-bridge`,
- pozostajacym drobnym maintenance taskiem po stronie assetow jest `Browserslist/caniuse-lite`,
- po uporzadkowaniu toolchainu sensowny jest juz kolejny krok UI albo doprecyzowanie runtime upgrade.

## Rekomendowana kolejnosc

### 1. P1: `Preferentions` UI

Zakres:

- przepisac `templates/User/Preferentions/index.html.twig` do design systemu,
- uporzadkowac walidacje, stany bledow i czytelnosc formularza,
- nie wprowadzac nowego inline JS ani nowych zaleznosci frontendowych.

Kryteria akceptacji:

- ekran preferencji korzysta ze wspolnego shellu i tokenow design systemu,
- walidacje i stany formularza sa czytelne na desktopie i mobile,
- slice nie doklada nowego debt do legacy Bootstrapa i inline JS.

### 2. P2: runtime upgrade prep

To powinien byc osobny spike techniczny, nie laczony z refaktorem domenowym.

Zakres:

- doprecyzowac backlog upgrade do Symfony 8 / PHP 8.5 przy zalozeniu, ze asset pipeline pozostaje tymczasowo na Encore,
- zdecydowac, czy kolejny backendowy cleanup obejmuje `WeightHistory` / dashboard, czy juz przygotowanie pod upgrade,
- wydzielic maintenance task dla `Browserslist/caniuse-lite`.

### 3. P3: dalszy frontend cleanup

Po preferencjach wrocic do pozostalych aktywnych stanow UI.

Zakres:

- dopisac aktywne stany i komunikaty dla wyszukiwarki produktu,
- dodac lepsza prezentacje empty state i bledow formularzy,
- ograniczac zaleznosc od starego Bootstrapa bez rozszerzania scope do pelnej migracji stacku.

## Proponowany najblizszy slice do implementacji

Jesli celem ma byc najlepszy zwrot z kolejnej sesji, nastepna implementacja powinna objac:

1. przepisanie `templates/User/Preferentions/index.html.twig` do wspolnego design systemu
2. uporzadkowanie stanów formularza i walidacji
3. weryfikacje przez `docker compose run --rm encore yarn build` i `lint:twig`
4. zapis wyniku i decyzji w `.codex/`
5. dopiero po tym runtime upgrade prep albo kolejny cleanup UI

## Dlaczego taka kolejnosc

- Projekt deklaruje, ze najpierw porzadkujemy granice modulow i testy, a dopiero potem migracje frameworka.
- Ostatnie slice'y zamknely najpilniejsze debt areas `Meal Journal`, auth/home UI i frontendowy toolchain.
- Kolejnym widocznym frontendowym debt area jest ekran preferencji, ktory nadal siedzi blisko starego markupu i formularzy.
- Runtime upgrade ma teraz lepsza baze, ale nie powinien byc mieszany z refaktorem widoku w jednym kroku.

## Ryzyka

- ekran preferencji nadal siedzi blisko starego modelu aplikacyjnego, wiec warto rozdzielic UI cleanup od wiekszego refaktoru domenowego,
- maintenance task `Browserslist/caniuse-lite` nie powinien rozszerzyc scope kolejnego slice'u,
- zbyt wczesna decyzja o migracji asset stacku nadal moze rozszerzyc scope przed runtime upgrade.
