# Session Handoff

Data: 2026-04-29

## Co jest zrobione

- `MealJournal` ma command side dla `AddMealEntry`, `EditMealEntry` i `DeleteMealEntry`,
- dodano read side dziennika przez `DailyMealJournalViewReader`,
- dodano osobny read model `DailyNutritionSummaryReader` dla dashboardowych agregatow dziennych,
- dodano pierwszy read-side `Measurements & Devices` przez `WeightHistoryChartReader`,
- `DailyController` zostal odciety od `EntityManagerInterface` we wszystkich sciezkach dziennika, ktore byly jeszcze na nim oparte,
- `DashboardController` nie zalezy juz od legacy sum z `EntryRepository` ani od surowego `WeightHistoryRepository`,
- dodano port `MealEntryLookup` i adapter Doctrine do lookupu wpisu z kontrola ownership,
- usunieto legacy `MealsDataProvider`,
- dodano testy jednostkowe i scenariusze Behat dla dodawania, edycji i usuwania wpisow `Meal Journal`,
- `EntryRepository` zostal uproszczony do metod potrzebnych dziennikowi wpisow,
- `Homepage`, `Login` i `Register` zostaly przeniesione do wspolnego shellu public/auth,
- `templates/User/Preferentions/index.html.twig` zostal przebudowany do nowego design systemu razem z walidacjami i flow formularza,
- `PreferenceController` przeszedl na bezpieczniejszy flow create/edit z kontrola ownership,
- dodano testy jednostkowe i Behat dla flow preferencji,
- zasady design systemu zostaly zapisane w `.codex/frontend-design-system.md`,
- zapisano dalsza kolejnosc implementacji w `.codex/next-implementation-plan.md`,
- zapisano artefakty `.codex/metabolism-goals-preferences-slice-2026-04-28.md` i `.codex/measurements-dashboard-slice-2026-04-29.md`,
- PHPUnit, Behat i `lint:container` przechodza w Dockerze,
- wykonano spike toolchainu frontendowego i zapisano wynik w `.codex/frontend-toolchain-spike-2026-04-14.md`,
- `docker-compose.yml` podnosi serwis `encore` do Node 20,
- `docker/php/Dockerfile` zostal zrownany do Node 20 i uproszczony,
- wykonano kontrolowany update zaleznosci JS: `@symfony/webpack-encore 4.7.0`, `webpack 5.106.1`, `webpack-cli 5.1.4`,
- usunieto tymczasowy `NODE_OPTIONS=--openssl-legacy-provider`,
- usunieto legacy pakiet `stimulus`, poprawiono import w `assets/controllers/hello_controller.js` i przypieto `chart.js` do `3.8.0`.
- usunieto z rootowych zaleznosci `composer/package-versions-deprecated` i `doctrine/annotations`,
- routing zostal przestawiony z `annotation` na `attribute`.

## Najwazniejsze zmienione obszary

- `.codex/worklog.md`
- `.codex/next-implementation-plan.md`
- `.codex/frontend-design-system.md`
- `.codex/frontend-toolchain-spike-2026-04-14.md`
- `docker-compose.yml`
- `docker/php/Dockerfile`
- `package.json`
- `webpack.config.js`
- `assets/controllers/hello_controller.js`
- `yarn.lock`
- `src/MealJournal/*`
- `src/Measurements/*`
- `src/Application/Controller/DailyController.php`
- `src/Application/Controller/DashboardController.php`
- `src/Application/Controller/PreferenceController.php`
- `src/Infrastructure/Repository/EntryRepository.php`
- `src/Infrastructure/Repository/WeightHistoryRepository.php`
- `config/services.yaml`
- `templates/Homepage/homepage.html.twig`
- `templates/User/Account/Login/index.html.twig`
- `templates/User/Account/Register/index.html.twig`
- `templates/User/Preferentions/index.html.twig`
- `assets/styles/app.css`
- `translations/messages.pl.yaml`
- `tests/Unit/MealJournal/Application/Handler/*`
- `tests/Unit/MealJournal/Application/View/DailyNutritionSummaryFactoryTest.php`
- `tests/Unit/Application/Services/Preference/BasalMetabolicRateAlgorithmTest.php`
- `tests/Unit/Application/CommandHandler/Preferention/*`
- `tests/Unit/Measurements/Application/View/WeightHistoryChartFactoryTest.php`
- `tests/Behat/Context/MealJournalContext.php`
- `tests/Behat/Context/PreferenceContext.php`
- `features/meal_journal/*`
- `features/preferences/*`

## Co zweryfikowano

Uruchomione komendy:

- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --config=behat.yml.dist --colors`
- `docker compose run --rm --no-deps php php bin/console lint:container`
- `docker compose run --rm --no-deps php php bin/console lint:twig templates/User/Preferentions/index.html.twig templates/User/Profile/index.html.twig`
- `docker compose run --rm encore yarn build`

Wynik:

- PHPUnit: `19 tests, 58 assertions`,
- Behat: `7 scenarios, 7 passed`,
- `lint:container` przechodzi,
- Twig lint: `All 2 Twig files contain valid syntax`,
- Encore: `webpack compiled successfully`,
- pozostaje maintenance warning `Browserslist: caniuse-lite is outdated`.

## Otwarte problemy

1. Build zgłasza maintenance warning `Browserslist: caniuse-lite is outdated`.
2. Encore pozostaje stackiem przejsciowym do czasu runtime upgrade i decyzji o docelowym asset pipeline.
3. Docker nadal siedzi na `php:8.3-fpm`, a `composer.json` nadal deklaruje `php >=8.2` i Symfony `6.4.*`.
4. W `composer.json` pozostaja pakiety wymagajace osobnego audytu przed `Symfony 8`, ale `composer/package-versions-deprecated` i `doctrine/annotations` zostaly juz zdjete.

## Priorytety na nowa sesje

### P1

- wejsc w audit runtime upgrade do Symfony 8 / PHP 8.5,
- odswiezyc backlog blockerow na bazie realnego stanu po ostatnich slice'ach,
- nie mieszac tego z nowym slice'em domenowym.

### P2

- zdecydowac pierwszy techniczny ruch: pakiety blokujace, kompatybilnosc PHP albo runtime Dockerowy,
- utrzymac Encore jako stack przejsciowy dopoki nie bedzie decyzji upgrade'owej.

### P3

- rozpisac drobny maintenance task dla `browserslist/caniuse-lite`,
- po runtime prep zdecydowac, czy wracamy do debtu `Preferention`, czy zaczynamy write-side `Measurements & Devices`.

## Zalecany punkt wejscia

1. Przeczytac `AGENTS.md`.
2. Przeczytac `.codex/session-handoff.md` i `.codex/worklog.md`.
3. Otworzyc `.codex/next-implementation-plan.md`, `.codex/upgrade-backlog.md`, `.codex/roadmap.md`, `.codex/metabolism-goals-preferences-slice-2026-04-28.md` i `.codex/measurements-dashboard-slice-2026-04-29.md`.
4. Wejsc w `composer.json`, `docker-compose.yml` oraz `docker/php/Dockerfile`.
5. Kontynuowac od audytu blockerow runtime upgrade.

## Zasady operacyjne

- pracowac przez Docker,
- nie polegac na lokalnym PHP hosta,
- nie robic big-bang rewrite,
- kazda wieksza decyzje dopisywac do `.codex/`.
