# Metabolism & Goals Preferences Slice

Data: 2026-04-28

## Cel slice'u

Domknac pierwszy bezpieczny krok w obszarze `Metabolism & Goals` bez laczenia go z pelnym rename legacy nazewnictwa i bez rozszerzania scope do przebudowy calego modelu domenowego.

## Co weszlo do zakresu

- nowy widok `templates/User/Preferentions/index.html.twig` oparty o design system,
- czytelne stany formularza, komunikaty bledow i leady pod przyszly kontekst `Metabolism & Goals`,
- poprawa flow kontrolera:
  - route create dziala na `GET` i `POST`,
  - route edit ma poprawne metody HTTP,
  - create redirectuje do edit, jesli preferencje juz istnieja,
  - edit sprawdza ownership po zalogowanym uzytkowniku,
- formularyzacja walidacji w `PreferenceType`,
- typizacja `PreferenceDTO` i `FormDataExtractor`,
- odpiecie handlerow od konkretnych fabryk przez interfejsy,
- testy jednostkowe kalkulacji i handlerow,
- scenariusze Behat dla ustawiania i edycji preferencji.

## Decyzje

### 1. Nie robimy teraz globalnego rename `Preferention`

Powod:

- rename dotyka route'ow, nazw klas, translacji i prawdopodobnie dalszych zaleznosci w legacy warstwie,
- to przekracza bezpieczny scope jednego slice'u i latwo zmieszaloby cleanup z szersza migracja.

Decyzja wykonawcza:

- utrzymujemy obecne nazwy route i namespace tam, gdzie juz istnieja,
- nowy kod porzadkujemy semantycznie na poziomie flow, walidacji i testow,
- rename traktujemy jako osobny task debt-retirement po dalszym ustabilizowaniu kontekstu.

### 2. Handler testujemy przez interfejsy fabryk

Powod:

- poprzednie zaleznosci do konkretnych klas utrudnialy testy i zwiekszaly sprzezenie z Doctrine,
- interfejsy pozwalaja utrzymac obecny runtime, ale ulatwiaja dalsza ekstrakcje bounded context.

### 3. Aktualny algorytm BMR zostaje bez zmiany

Powod:

- celem slice'u bylo domkniecie flow preferencji, nie zmiana logiki biznesowej,
- testy zostaly dopasowane do aktualnego zachowania algorytmu, zeby najpierw ustabilizowac kontrakt.

Konsekwencja:

- ewentualne korekty zaokraglen lub wzorow powinny wejsc jako osobny task domenowy z uzgodnionymi oczekiwaniami biznesowymi.

## Weryfikacja

Uruchomione w Dockerze:

- `docker compose run --rm --no-deps php vendor/bin/phpunit --configuration phpunit.dist.xml --testsuite Unit`
- `docker compose run --rm --no-deps php vendor/bin/behat --config=behat.yml.dist --colors`
- `docker compose run --rm --no-deps php php bin/console lint:twig templates/User/Preferentions/index.html.twig templates/User/Profile/index.html.twig`
- `docker compose run --rm encore yarn build`

Wynik:

- PHPUnit: `17 tests, 54 assertions`,
- Behat: `7 scenarios, 7 passed`,
- Twig lint: `All 2 Twig files contain valid syntax`,
- Encore: `webpack compiled successfully`,
- pozostaje maintenance warning `Browserslist: caniuse-lite is outdated`.

## Nastepny sensowny krok

Po tym slice'ie kolejny bezpieczny krok to read-side cleanup `WeightHistory` / dashboard jako pierwszy wycinek `Measurements & Devices`.
