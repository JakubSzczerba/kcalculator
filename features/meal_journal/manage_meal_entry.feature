Feature: Manage meal entry
  In order to maintain an accurate meal journal
  As an authenticated user
  I want to edit and delete my meal entries

  Scenario: Recalculate nutrition when editing an existing entry
    Given the meal journal contains a product 10 named "Apple" with 52 kcal, 0.3 protein, 0.2 fat and 14 carbohydrates
    And the meal journal contains an entry 15 for user 7 with product 10 as "Śniadanie" with portion multiplier 1
    When user 7 edits the entry 15 to "Obiad" with portion multiplier 0.5
    Then the meal entry 15 should be stored as "Obiad" with 26 kcal, 0.15 protein, 0.1 fat and 7 carbohydrates

  Scenario: Remove an existing entry from the journal
    Given the meal journal contains a product 10 named "Apple" with 52 kcal, 0.3 protein, 0.2 fat and 14 carbohydrates
    And the meal journal contains an entry 15 for user 7 with product 10 as "Śniadanie" with portion multiplier 1
    When user 7 deletes the entry 15
    Then the meal entry 15 should no longer exist
