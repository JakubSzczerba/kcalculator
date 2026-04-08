# Food Data Strategy

## Problem obecny

Aktualnie katalog produktow jest zasiany z jednego pliku CSV. To podejscie ma kilka wad:

- brak aktualizacji i wersjonowania,
- brak identyfikatorow zewnetrznych,
- brak danych o pochodzeniu i jakosci rekordu,
- brak obslugi produktow branded vs generic,
- brak wygodnej synchronizacji i wyszukiwania.

## Docelowy model

W `Nutrition Catalog` wprowadzamy port:

- `FoodCatalogProvider`

Adaptery moga byc wielokrotne:

- `CsvFoodCatalogProvider` jako seed/fallback,
- `OpenDataFoodCatalogProvider` dla publicznej bazy produktow,
- `CuratedAdminCatalogProvider` dla recznie zatwierdzonych rekordow,
- `PartnerCatalogProvider` dla komercyjnych lub urzadzeniowych integracji.

## Rekomendowana strategia wdrozenia

### Krok 1

Zostawic CSV jako zrodlo startowe, ale schowac je za interfejsem i jobem importu.

### Krok 2

Dodac model produktu z metadanymi:

- `externalId`
- `source`
- `sourceVersion`
- `locale`
- `isVerified`
- `lastSyncedAt`

### Krok 3

Dodac co najmniej dwa typy danych:

- produkty generyczne,
- produkty branded ze skanowalnym kodem.

### Krok 4

Wprowadzic warstwe normalizacji jednostek i makro per 100 g / porcja.

## Kandydaci na zrodla danych

Na poziomie architektury warto przewidziec co najmniej dwa typy providerow:

- otwarta baza produktow z API i eksportami,
- kuratorowane tabele referencyjne dla danych bardziej wiarygodnych lub lokalnych.

Przyklady sensownych kierunkow:

- Open Food Facts: duzy otwarty katalog z API i danymi o produktach branded,
- USDA FoodData Central: dobrze udokumentowane API i zbiory otwarte dla danych referencyjnych.

## Decyzja produktowa

Nie wybieramy jednego zrodla jako absolutnego source of truth. Docelowo system powinien:

- przechowywac pochodzenie rekordu,
- umiec rankowac wiarygodnosc danych,
- pozwalac na lokalne nadpisania i zatwierdzanie przez backoffice,
- utrzymywac fallback offline dla kluczowych scenariuszy.

