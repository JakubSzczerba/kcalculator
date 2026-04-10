# Roadmap

## Faza 0 - Inception

Status: completed

Cele:

- zapisac kanoniczny kontekst projektu,
- spisac bounded contexts i decyzje architektoniczne,
- przygotowac backlog migracji do PHP 8.5 i Symfony 8,
- uruchomic pierwsze fundamenty pod TDD i BDD.

## Faza 1 - Stabilizacja techniczna

Status: in progress

Cele:

- podniesc hygiene repo: nazewnictwo, katalogi, standardy kodu,
- dodac PHPUnit, Behat i minimalne scenariusze krytyczne,
- wprowadzic katalog `tests/` i `features/`,
- opisac Definition of Done dla zmian domenowych.

## Faza 2 - Nowa mapa domeny

Status: in progress

Cele:

- wydzielic bounded contexts i nowy namespace docelowy,
- odseparowac logike domenowa od kontrolerow i formularzy,
- wprowadzic kontrakty portow dla danych produktowych i urzadzen.

## Faza 3 - Nutrition Catalog

Status: in progress

Cele:

- odejsc od modelu "jedno CSV i import command",
- dodac warstwe katalogu produktow z providerami/adapters,
- przygotowac mozliwosc wielu zrodel danych, cache i synchronizacji.

## Faza 4 - Meal Journal

Status: in progress

Cele:

- przebudowac model wpisow i liczonych wartosci odzywczych,
- wprowadzic inwarianty domenowe i testy TDD,
- przygotowac scenariusze Behat dla dodawania i edycji wpisow.

## Faza 5 - Measurements & Devices

Status: not started

Cele:

- dodac model pomiaru, sesji wazenia i importu z urzadzenia,
- przygotowac adapter dla inteligentnej wagi,
- rozdzielic surowy odczyt, interpretacje AI i zatwierdzenie przez uzytkownika.

## Faza 6 - UI Modernization

Status: in progress

Cele:

- nowy layout i design system,
- wymiana jQuery na nowoczesne interakcje,
- AJAX/partial updates bez przeładowywania kluczowych ekranow.

## Faza 7 - Runtime Upgrade

Status: not started

Cele:

- domknac deprecations,
- podniesc dependency graph do Symfony 8,
- ustawic docelowy runtime PHP 8.5 w Dockerze i CI.

## Definition of Done

- logika biznesowa ma testy jednostkowe,
- krytyczne flow ma scenariusz Behat,
- decyzje przekrojowe sa zapisane w `.codex/`,
- nowy kod nie doklada zaleznosci do starego modelu warstwowego bez uzasadnienia.

## Najblizsza sesja

Priorytety:

1. Wejsc w techniczny spike toolchainu frontendowego: Dockerowy Node runtime, Encore i warning `@symfony/stimulus-bridge`.
2. Zdecydowac, czy tymczasowo zostaje Encore, czy przygotowujemy migracje do nowszego stacku assetow.
3. Doprecyzowac plan runtime upgrade do Symfony 8 / PHP 8.5 na bazie uporzadkowanego backendu `Meal Journal`.
4. Po spike'u wrocic do kolejnych ekranow legacy UI, zaczynajac od `templates/User/Preferentions/index.html.twig`.
