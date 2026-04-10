# Frontend Backlog

Data: 2026-04-10

## Co jest juz zrobione

- nowy shell aplikacji w `templates/base.html.twig`,
- nowy styling i tokeny UI w `assets/styles/app.css`,
- interakcje shellu przeniesione do `assets/app.js`,
- przebudowane ekrany:
  - `Homepage/homepage.html.twig`
  - `User/Account/Login/index.html.twig`
  - `User/Account/Register/index.html.twig`
  - `User/Daily/index.html.twig`
  - `User/Daily/Products/searchedProducts.html.twig`
  - `User/Daily/Products/productDetails.html.twig`
  - `User/Dashboard/index.html.twig`
  - `User/Profile/index.html.twig`
- zasady design systemu zapisane w `.codex/frontend-design-system.md`

## Najblizsze ekrany do domkniecia

### P1

- `templates/User/Preferentions/index.html.twig`

Cel:

- przepisac ekran preferencji do nowego design systemu,
- poprawic czytelnosc pol formularza i stanow walidacyjnych,
- przygotowac UX pod przyszle kroki domenowe `Metabolism & Goals`.

### P2

- dopisac aktywne stany i komunikaty dla wyszukiwarki produktu,
- dodac lepsza prezentacje empty state i bledow formularzy,
- ograniczyc zaleznosc od starego Bootstrapa do czasu decyzji o docelowym stacku.

### P3

- techniczny spike: Node LTS, Encore i zgodnosc `@symfony/stimulus-bridge`
- decyzja o przyszlosci stacku assetow przed runtime upgrade

## Debt techniczny frontendu

1. Ostrzezenie builda: `@symfony/stimulus-bridge` jest nowsze niz zakres wspierany przez obecne Encore.
2. Dockerowy serwis `encore` nadal bazuje na starym obrazie Node.
3. Bootstrap z CDN pozostaje tymczasowym fundamentem layoutu.
4. W repo sa jeszcze stare nazwy klas i fragmenty markupu po legacy warstwie.

## Zasady na kolejna sesje

- nie przepisywac od razu wszystkich ekranow naraz,
- trzymac wspolny design system oparty o `assets/styles/app.css`,
- unikac nowego inline JS i nowych CDN-ow,
- kazdy frontendowy slice weryfikowac przez:
  - `docker compose run --rm encore yarn build`
  - `docker compose run --rm --no-deps php php bin/console lint:twig ...`
