# Bounded Contexts

## 1. Identity & Access

Odpowiedzialnosc:

- rejestracja, logowanie, role, profil konta, polityki dostepu.

Kluczowe byty:

- `User`
- `AccountCredentials`
- `RoleAssignment`

Uwagi:

- ten kontekst powinien byc mozliwie stabilny i izolowany od nutrition/device logic.

## 2. Nutrition Catalog

Odpowiedzialnosc:

- katalog produktow, zrodla danych, wyszukiwanie, normalizacja i wersjonowanie danych zywieniowych.

Kluczowe byty:

- `FoodProduct`
- `NutritionFacts`
- `CatalogSource`
- `CatalogImportJob`

Porty:

- `FoodCatalogProvider`
- `FoodSearch`

Uwagi:

- obecny CSV powinien zostac zdegradowany do seed/fallback provider.
- docelowo mozna dodac adaptery dla zewnetrznych API lub kuratorowanych importow.

## 3. Meal Journal

Odpowiedzialnosc:

- wpisy posilkow, porcje, dzienne podsumowania, korekty manualne.

Kluczowe byty:

- `MealEntry`
- `Serving`
- `MealDay`

Porty:

- `MealEntryRepository`
- `MealJournalReadModel`

Uwagi:

- ten kontekst konsumuje dane z `Nutrition Catalog`, ale nie zarzadza katalogiem.

## 4. Metabolism & Goals

Odpowiedzialnosc:

- preferencje, zapotrzebowanie kaloryczne, cele, metryki bazowe.

Kluczowe byty:

- `UserGoal`
- `MetabolicProfile`
- `CalorieTarget`

## 5. Measurements & Devices

Odpowiedzialnosc:

- historia wagi, import pomiarow, integracje urzadzen, webhooks, BLE/API adapters.

Kluczowe byty:

- `WeightMeasurement`
- `MeasurementSession`
- `DeviceRegistration`
- `IncomingDevicePayload`

Porty:

- `ScaleDeviceGateway`
- `MeasurementIngestion`
- `MeasurementClassifier`

Uwagi:

- AI urzadzenie lub AI pipeline nie powinny miec dostepu bezposrednio do domeny posilkow.
- najpierw zapis surowego payloadu, potem interpretacja, na koncu decyzja biznesowa.

## 6. Insights & Recommendations

Odpowiedzialnosc:

- dashboardy, trendy, rekomendacje, alerty i przyszle funkcje AI.

Kluczowe byty:

- `ProgressSnapshot`
- `NutritionTrend`
- `Recommendation`

## 7. Backoffice

Odpowiedzialnosc:

- administracja, poprawki katalogu, moderacja danych, narzedzia operacyjne.

## Shared Kernel

Dozwolone tylko dla:

- podstawowych VO,
- zegara/system time abstraction,
- identyfikatorow technicznych,
- lekkich kontraktow infrastrukturalnych.

Nie wrzucamy tu:

- regul biznesowych specyficznych dla kontekstu,
- modeli read/write nalezacych do domeny produktowej lub pomiarowej.

