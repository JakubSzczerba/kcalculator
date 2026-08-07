Feature: Add meal entry
  In order to keep a meal journal
  As an authenticated user
  I want to add a product portion to my journal

  Scenario: Calculate nutrition for a selected portion
    Given the meal journal contains a product 10 named "Apple" with 52 kcal, 0.3 protein, 0.2 fat and 14 carbohydrates
    When user 7 adds the product 10 as "Śniadanie" with portion multiplier 0.5
    Then the meal entry should be stored with 26 kcal, 0.15 protein, 0.1 fat and 7 carbohydrates

  Scenario: Reject adding an entry for an unknown product
    When user 7 adds the product 404 as "Śniadanie" with portion multiplier 1
    Then adding the meal entry should fail because the product does not exist
