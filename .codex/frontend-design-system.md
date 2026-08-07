# Frontend Design System

Data: 2026-04-10

## Cel

Utrzymac jeden spojny shell wizualny dla ekranow publicznych i zalogowanych bez powrotu do inline stylow, przypadkowych spacingow i mieszanych wzorcow formularzy.

## Zasady bazowe

- korzystamy z tokenow w `assets/styles/app.css`, a nie z lokalnych kolorow i ad-hoc klas,
- glowne kontenery widokow opieramy o `page-card`, `page-card--wide`, `eyebrow`, `page-card__lead`,
- akcje pierwszoplanowe korzystaja z `primary-button`, a drugoplanowe z `secondary-button`,
- formularze budujemy z `form-field`, `form-control`, `inline-alert` i wspolnych stanow focus,
- nie dodajemy nowego inline JS ani inline CSS,
- Bootstrap pozostaje tylko jako tymczasowe tlo kompatybilnosci, ale nowy markup nie powinien polegac na jego gridzie i utility classes.

## Public / Auth

- ekrany publiczne powinny miec wyrazny hero, jeden glowny CTA i 2-3 wspierajace bloki tresci,
- logowanie i rejestracja korzystaja z tego samego ukladu `auth-layout`,
- bledy logowania i walidacji formularzy pokazujemy jako jawne komunikaty w sekcji formularza, nie jako surowy tekst nad cala strona,
- linki pomocnicze typu "Mam juz konto" i "Zarejestruj sie" utrzymujemy w stopce panelu formularza.

## App Shell

- topbar i sidebar pozostaja wspolnym shell-em dla ekranow zalogowanych,
- glowne flow dziennika, dashboardu i profilu ma pozostac oparte o komponenty kart i sekcji, nie o stare `row/col`,
- stany empty, success i error maja korzystac z tych samych powierzchni i tokenow co reszta aplikacji.

## Responsywnosc

- mobile-first: przy szerokosci tablet/mobile sekcje siatkowe schodza do jednej kolumny,
- przyciski CTA w public/auth moga skladac sie pionowo na malych ekranach,
- focus state i czytelnosc formularzy sa wymagane rowniez na mobile.
