# Project Context

## Snapshot

Repo jest prototypem aplikacji fitness/nutrition zbudowanym jako klasyczne Symfony z warstwami `Application`, `Domain`, `Infrastructure`, ale granice sa dzisiaj glownie organizacyjne, nie architektoniczne.

Najwazniejsze obserwacje:

- kontrolery sa grube i korzystaja bezposrednio z `EntityManagerInterface` oraz konkretnych repozytoriow infrastruktury,
- CQRS jest tylko czesciowe; ten sam Messenger obsluguje command/query bez jasnych kontraktow modulowych,
- encje domenowe sa w praktyce anemiczne i wystawiaja settery zamiast pilnowac inwariantow,
- nazewnictwo jest niespojne (`Preferention`, `Prodiver`, mieszanie polskiego i angielskiego),
- dane produktowe nadal pochodza z `src/Application/Data/Products.csv`, ale import zostal juz odklejony od komendy i schowany za portem,
- frontend opiera sie o Twig, stare CDN-y i wiele wersji jQuery,
- w repo sa juz pierwsze testy jednostkowe i smoke scenariusz Behat,
- praca developerska i weryfikacja powinny byc prowadzone przez Docker, nie przez lokalne PHP hosta.

## Ryzyka techniczne

- migracja do Symfony 8 bez uporzadkowania deprecations zwiekszy koszt i ryzyko regresji,
- podniesienie do PHP 8.5 bez szerszego pokrycia testami nadal nie da wiarygodnej informacji o stabilnosci,
- obecny model produktow nie wspiera wielu zrodel danych, wersjonowania ani identyfikatorow zewnetrznych,
- brak warstwy integracyjnej utrudni podpiecie inteligentnych wag,
- UI jest trudne do rozwijania i podatne na regresje przez inline JS i mieszane zaleznosci CDN,
- obecny Dockerfile ciagnie legacy Node 18 i wymaga modernizacji rownolegle z backendem.

## Zalozenia architektoniczne

- docelowo modular monolith z wyraznym podzialem na bounded contexts,
- per context: `Domain`, `Application`, `Infrastructure`, `UI`,
- wspolne elementy tylko w `SharedKernel`, bez wrzucania tam logiki biznesowej,
- adaptery do danych o produktach i adaptery urzadzen za osobnymi interfejsami,
- preferowany kierunek frontendowy: Twig + Stimulus/Turbo lub API-first + nowoczesny klient, decyzja po stabilizacji kontraktow backendu.

## Wnioski na start

- najpierw potrzebne sa dokumentacja, plan i struktura decyzyjna,
- pierwszy duzy refaktor powinien objac `Nutrition Catalog` i `Meal Journal`,
- zrodlo danych o posilkach trzeba potraktowac jako osobny problem produktowy, nie tylko import pliku,
- integracja inteligentnych wag powinna powstac jako niezalezny bounded context z eventami i adapterami.

## Stan po pierwszej sesji wykonawczej

- `NutritionCatalog` zostal uruchomiony jako nowy modul z warstwami `Application`, `Infrastructure`, `UI`,
- komenda `food-catalog:import` dziala i ma alias zgodnosci wstecznej `csv:import`,
- `DailyController` zalezy od `ProductRepositoryInterface`, a nie od repozytorium infrastrukturalnego,
- PHPUnit 10.5 zostal wybrany jako wersja przejsciowa ze wzgledu na konflikt `symfony/maker-bundle` z `nikic/php-parser 5.x`,
- Behat jest skonfigurowany i ma pierwszy scenariusz smoke.
