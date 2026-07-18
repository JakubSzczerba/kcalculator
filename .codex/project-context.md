# Project Context

## Snapshot

Repo jest prototypem aplikacji fitness/nutrition zbudowanym jako klasyczne Symfony z warstwami `Application`, `Domain`, `Infrastructure`, ale granice sa dzisiaj glownie organizacyjne, nie architektoniczne.

Najwazniejsze obserwacje:

- kontrolery sa nadal dosc grube, ale `Meal Journal` i dashboard zostaly juz czesciowo odciete od bezposredniego dostepu do infrastruktury,
- CQRS jest tylko czesciowe; ten sam Messenger obsluguje command/query bez jasnych kontraktow modulowych,
- encje domenowe sa w praktyce anemiczne i wystawiaja settery zamiast pilnowac inwariantow,
- nazewnictwo jest niespojne (`Preferention`, `Prodiver`, mieszanie polskiego i angielskiego),
- dane produktowe nadal pochodza z `src/Application/Data/Products.csv`, ale import zostal juz odklejony od komendy i schowany za portem,
- frontend nadal opiera sie o Twig i przejsciowy Encore, ale glowny shell, dziennik, dashboard, profil, preferencje, homepage i auth korzystaja juz ze wspolnego design systemu,
- toolchain frontendowy jest ustabilizowany na Node 20, Encore 4.7 i Webpack 5; pozostaja lokalne fragmenty legacy jQuery i maintenance warning Browserslist,
- w repo sa testy jednostkowe oraz scenariusze Behat dla smoke, `Meal Journal` i preferencji,
- `MealJournal` ma command side add/edit/delete oraz osobne read modele dziennika i dashboardowych podsumowan,
- `Measurements` ma pierwszy read-side historii wagi, a `Metabolism & Goals` ma ustabilizowany flow preferencji,
- dependency security baseline jest czysty; `composer audit --locked` nie zglasza znanych podatnosci,
- praca developerska i weryfikacja powinny byc prowadzone przez Docker, nie przez lokalne PHP hosta.

## Ryzyka techniczne

- migracja do Symfony 8 bez uporzadkowania deprecations zwiekszy koszt i ryzyko regresji,
- podniesienie do PHP 8.5 wymaga zachowania zielonego baseline PHPUnit, Behat, lintow i builda po kazdym kroku,
- obecny model produktow nie wspiera wielu zrodel danych, wersjonowania ani identyfikatorow zewnetrznych,
- brak warstwy integracyjnej utrudni podpiecie inteligentnych wag,
- UI nadal ma debt w pozostalych legacy formularzach i fragmentach jQuery, ale ekran preferencji oraz public/auth shell nie blokuja juz glownego flow,
- asset pipeline pozostaje przejsciowo na Encore, ale runtime Node i dependency graph sa juz ustabilizowane na poziomie potrzeb biezacej migracji.

## Zalozenia architektoniczne

- docelowo modular monolith z wyraznym podzialem na bounded contexts,
- per context: `Domain`, `Application`, `Infrastructure`, `UI`,
- wspolne elementy tylko w `SharedKernel`, bez wrzucania tam logiki biznesowej,
- adaptery do danych o produktach i adaptery urzadzen za osobnymi interfejsami,
- preferowany kierunek frontendowy: Twig + Stimulus/Turbo lub API-first + nowoczesny klient, decyzja po stabilizacji kontraktow backendu.

## Historyczne wnioski na start

- najpierw potrzebne sa dokumentacja, plan i struktura decyzyjna,
- pierwszy duzy refaktor powinien objac `Nutrition Catalog` i `Meal Journal`,
- zrodlo danych o posilkach trzeba potraktowac jako osobny problem produktowy, nie tylko import pliku,
- integracja inteligentnych wag powinna powstac jako niezalezny bounded context z eventami i adapterami.

## Stan po pierwszej sesji wykonawczej

- `NutritionCatalog` zostal uruchomiony jako nowy modul z warstwami `Application`, `Infrastructure`, `UI`,
- komenda `food-catalog:import` dziala i ma alias zgodnosci wstecznej `csv:import`,
- `DailyController` zalezy od `ProductRepositoryInterface`, a nie od repozytorium infrastrukturalnego,
- PHPUnit 10.5 zostal wybrany jako wersja przejsciowa ze wzgledu na konflikt starszego `symfony/maker-bundle` z `nikic/php-parser 5.x`; blocker zostal zdjety 2026-07-18,
- Behat jest skonfigurowany i ma pierwszy scenariusz smoke.

## Stan po sesji 2026-04-09

- `MealJournal` dostal pierwszy nowy command side (`AddMealEntry`) i read side dla widoku dziennego,
- `DailyController::addEntry()` nie korzysta juz z `EntityManagerInterface`,
- zniknal legacy `MealsDataProvider`; dziennik korzysta z `DailyMealJournalViewReader`,
- frontend shell, dziennik, dashboard i profil zostaly zmodernizowane bez zmiany kontraktow backendowych,
- asset build przechodzi w Dockerze, ale pozostaje warning zgodnosci `@symfony/stimulus-bridge` z obecna wersja Encore.

## Stan po sesji 2026-04-10

- `MealJournal` dostal kolejne command side: `EditMealEntry` i `DeleteMealEntry`,
- `DailyController` nie zalezy juz od `EntityManagerInterface` w sciezkach dziennika,
- dashboardowe agregaty zostaly wydzielone do osobnego read modelu `DailyNutritionSummaryReader`,
- `EntryRepository` zostal uproszczony do metod nalezacych bezposrednio do dziennika wpisow,
- odczyt dzienny wpisow korzysta z zakresu `start/end of day`, zamiast porownania `datetime` do stringa z data,
- `Homepage`, `Login` i `Register` zostaly przeniesione do wspolnego shellu public/auth,
- zasady frontendowego design systemu zostaly zapisane w `.codex/frontend-design-system.md`,
- testy przechodza w Dockerze: PHPUnit `13 tests, 46 assertions`, Behat `5 scenarios, 5 passed`, `lint:container` oraz Twig lint sa zielone,
- glownym technicznym debt pozostaje Dockerowy frontend runtime i warning zgodnosci `@symfony/stimulus-bridge` z Encore.

## Stan po sesji 2026-04-14

- frontendowy toolchain zostal ustabilizowany i opisany w `.codex/frontend-toolchain-spike-2026-04-14.md`,
- `docker-compose.yml` podnosi serwis `encore` do Node 20, a `docker/php/Dockerfile` jest zrownany do tej samej linii runtime,
- dependency graph JS zostal zaktualizowany do `@symfony/webpack-encore 4.7.0`, `webpack 5.106.1` i `webpack-cli 5.1.4`,
- build assetow przechodzi w Dockerze bez warningu zgodnosci `@symfony/stimulus-bridge` i bez obejscia `NODE_OPTIONS=--openssl-legacy-provider`,
- usunieto legacy pakiet `stimulus`; lokalne kontrolery uzywaja `@hotwired/stimulus`,
- `chart.js` zostal przypiety do `3.8.0`, zgodnie z zakresem wspieranym przez `symfony/ux-chartjs`,
- pozostajacym drobnym debt po stronie assetow jest maintenance warning `Browserslist: caniuse-lite is outdated`,
- po domknieciu toolchainu kolejnym sensownym frontendowym slice'em jest `templates/User/Preferentions/index.html.twig`.

## Stan po sesjach 2026-04-28 i 2026-04-29

- flow `Preferentions` zostal przebudowany i pokryty testami jednostkowymi oraz Behat,
- ekran preferencji korzysta ze wspolnego design systemu,
- wydzielono `WeightHistoryChartReader` i view model trendu wagi,
- `DashboardController` nie zalezy juz od surowego `WeightHistoryRepository`,
- test baseline wzrosl do 19 testow / 58 asercji oraz 7 scenariuszy Behat,
- routing przestawiono z loadera annotations na attributes,
- usunieto nieuzywane `composer/package-versions-deprecated`, `doctrine/annotations` i `symfony/proxy-manager-bridge`.

## Stan po sesji 2026-07-18

- wykonano pelny audit dependency graphu pod Symfony 8 / PHP 8.5,
- MakerBundle `1.52.0` zostal podniesiony do `1.67.0`, a PHP Parser `4.19.5` do `5.8.0`,
- EasyAdmin, Twig i podatne komponenty Symfony 6.4 zostaly podniesione do bezpiecznych patchy,
- `composer audit --locked` nie zglasza znanych advisory,
- pelna regresja przechodzi w Docker Desktop przez `docker.exe compose`,
- aktualna kolejnosc migracji to PHP 8.5, dependency baseline, Doctrine major, Symfony 7.4 i Symfony 8.x,
- szczegolowa macierz znajduje sie w `.codex/runtime-upgrade-dependency-matrix-2026-07-18.md`.

## Aktualny punkt wejscia

Najblizszy izolowany slice:

1. zmienic obraz `php:8.3-fpm` na `php:8.5-fpm`,
2. utrzymac Symfony 6.4 i obecne majory Doctrine,
3. naprawic tylko problemy kompatybilnosci PHP,
4. wykonac pelna regresje w Dockerze,
5. dopiero potem wejsc w FOS Elastica 7.2 i Doctrine major.
