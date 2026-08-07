# Implementation Plan

Data: 2026-04-28

## Cel

Wyznaczyc dalsza kolejnosc implementacji po ustabilizowaniu:

- `NutritionCatalog`,
- command/read side `MealJournal`,
- frontendowego shella public/auth/app,
- frontendowego toolchainu na Node 20 + Encore 4.7.

Plan ma utrzymac iteracyjny charakter zmian i nie mieszac cleanupu domenowego z runtime upgrade w jednym kroku.

## Aktualna diagnoza

Stan repo wskazuje trzy rozne poziomy gotowosci:

1. `NutritionCatalog` i `MealJournal` maja juz pierwszy sensowny ksztalt modulowy, testy i weryfikacje w Dockerze.
2. `Metabolism & Goals` nadal siedzi blisko legacy warstwy aplikacyjnej:
   - route i nazewnictwo `preferention`,
   - `PreferenceController` zalezy od `EntityManagerInterface`,
   - formularz i template preferencji sa legacy,
   - brak testow jednostkowych i brak scenariusza Behat dla tego flow.
3. `Measurements & Devices` praktycznie nie wystartowal:
   - `WeightHistoryRepository` jest jeszcze klasycznym repo read/write,
   - dashboard nadal sklada wykres w kontrolerze na bazie legacy query shape,
   - brak nowego modelu pomiaru i kontraktow ingestii.

Z punktu widzenia ryzyka upgrade najwazniejsze jest teraz ograniczenie pozostalych silnych zaleznosci od starego stylu Symfony/Doctrine w miejscach, ktore dalej beda dotykane funkcjonalnie.

## Zasada kolejnosci

Najpierw domykamy bezpieczny slice `Metabolism & Goals`, potem wyjmujemy pierwszy read-side slice `Measurements & Devices`, a dopiero potem uruchamiamy wlasciwy tor runtime upgrade.

Powod:

- preferencje sa najblizszym logicznie krokiem po dzienniku i dashboardzie,
- ekran preferencji jest nadal widocznym debt area w UI i backendzie,
- `WeightHistory` jest potrzebne do dalszej modularyzacji dashboardu,
- upgrade bez tych porzadkow zwiekszy koszt dotykania legacy kontrolerow po migracji.

## Rekomendowana kolejnosc prac

### P1. `Metabolism & Goals` - slice preferencji

Zakres:

- przepisac `templates/User/Preferentions/index.html.twig` do wspolnego design systemu,
- uporzadkowac formularz `PreferenceType` pod czytelne stany walidacyjne i spojnosc z nowym shellem,
- dodac pierwszy testowy harness dla flow preferencji:
  - unit dla logiki przeliczenia celu/metabolizmu, jesli zostanie wydzielona,
  - Behat dla utworzenia lub edycji preferencji,
- ograniczyc zaleznosc kontrolera od bezposredniego Doctrine tam, gdzie to mozliwe bez big-bang rewrite,
- zapisac decyzje nazewnicza i migracyjna wokol `Preferention` vs `Preference`.

Minimalny wynik:

- nowy widok preferencji,
- czytelne komunikaty bledow i success flow,
- test krytycznego scenariusza,
- dopisany artefakt `.codex/` z decyzja o dalszej ekstrakcji `Metabolism & Goals`.

Uwagi wykonawcze:

- nie laczyc tego od razu z przemianowaniem wszystkiego globalnie,
- najpierw uporzadkowac UI i flow, potem zaplanowac rename/debt retirement jako osobny krok,
- jesli logika liczenia kalorii nadal siedzi w serwisach aplikacyjnych, wydzielic ja do pierwszej uslugi domenowej lub application service z testami.

### P2. `Measurements & Devices` - pierwszy read-side/dashboard slice

Zakres:

- wydzielic read model dla historii wagi zamiast bezposredniego uzycia `WeightHistoryRepository` w `DashboardController`,
- uproscic budowanie danych wykresu przez osobny reader/factory,
- przygotowac grunt pod przyszly bounded context `Measurements & Devices` bez wdrazania jeszcze ingestii urzadzen,
- zapisac shape docelowych bytow i portow pomiarowych w `.codex/`.

Minimalny wynik:

- `DashboardController` przestaje skladac surowe tablice z wynikow Doctrine,
- historia wagi ma osobny kontrakt odczytowy,
- istnieje artefakt planujacy przejscie od `WeightHistory` do `WeightMeasurement` / `MeasurementSession`.

Dlaczego przed upgrade:

- to ostatni widoczny fragment dashboardu mocno przyklejony do legacy repo,
- ten krok jest maly, ale obniza koszt kolejnych zmian w `Insights` i `Measurements`.

### P3. Runtime upgrade prep

To ma byc tor techniczny, nie laczony z nowym slice'em domenowym.

Zakres:

- zaktualizowac backlog upgrade po zmianach z kwietnia,
- zrobic lokalny audit kodu pod:
  - zaleznosci legacy w `composer.json`,
  - miejsca oparte o stare annotacje/nazewnictwo/praktyki,
  - kontrolery i formularze najbardziej sprzezone z frameworkiem,
- rozpisac serie malych PR-ow/slice'ow zamiast jednego skoku.

Kolejnosc w tym torze:

1. audit zaleznosci i configu Symfony 6.4,
2. plan usuwania blockerow z `composer.json`,
3. przygotowanie pod `PHP 8.4+` kompatybilnosc kodu,
4. dopiero potem podniesienie obrazu Dockerowego z `php:8.3-fpm` do linii docelowej.

Minimalny wynik:

- odswiezony `.codex/upgrade-backlog.md`,
- lista blockerow z przypisaniem do konkretnych slice'ow,
- decyzja, czy `Encore` pozostaje przez caly upgrade do Symfony 8.

## Proponowany plan na 3 najblizsze sesje

### Sesja 1

Cel:

- domknac `Preferentions` jako bezpieczny slice `Metabolism & Goals`.

Kroki:

1. przeprojektowac Twig i UX formularza,
2. uzupelnic walidacje/stany formularza,
3. dodac test krytycznego flow,
4. zweryfikowac w Dockerze,
5. zapisac decyzje o dalszym rozbiciu tego kontekstu.

### Sesja 2

Cel:

- wyjac historię wagi z legacy dashboard flow.

Kroki:

1. dodac reader/factory dla wykresu lub historii wagi,
2. odchudzic `DashboardController`,
3. dopisac testy jednostkowe dla nowego read side,
4. zapisac kontrakty i plan pod `Measurements & Devices`.

### Sesja 3

Cel:

- odpalic wlasciwy spike przygotowawczy pod Symfony 8 / PHP 8.5.

Kroki:

1. zaktualizowac backlog upgrade,
2. rozpisac blockers per pakiet/obszar,
3. zdecydowac pierwszy techniczny slice upgrade,
4. dopisac zasady weryfikacji i Definition of Ready dla zmian upgrade'owych.

## Kryteria wejscia do runtime upgrade

Nie zaczynac zasadniczego upgrade dopoki:

- `Preferentions` nie beda uporzadkowane przynajmniej na poziomie flow i UI,
- dashboard nie bedzie mial wydzielonego read side dla historii wagi,
- backlog upgrade nie bedzie rozpisany na konkretne blockers zamiast ogolnych hasel.

## Ryzyka

1. Zbyt szerokie wejscie w rename `Preferention` moze rozszerzyc scope i zmieszac cleanup z migracja danych.
2. Wejscie w `Measurements & Devices` zbyt gleboko moze przedwczesnie otworzyc temat ingestii urzadzen.
3. Szybki skok do upgrade bez domkniecia dwoch powyzszych debt areas zwiekszy liczbe miejsc wymagajacych poprawek po migracji.

## Rekomendacja wykonawcza

Nastepna implementacja powinna objac `Preferentions`, ale nie jako sam lifting Twiga. Najlepszy zwrot da slice laczacy:

- nowy widok,
- porzadek w formularzu,
- pierwszy test flow,
- zapis decyzji o ekstrakcji `Metabolism & Goals`.

Po tym warto wejsc w `WeightHistory`/dashboard, a dopiero potem przejsc do przygotowania runtime upgrade jako osobnego strumienia prac.
