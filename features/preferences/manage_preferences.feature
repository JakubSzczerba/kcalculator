Feature: Manage nutrition preferences
  In order to keep a consistent metabolic profile
  As an authenticated user
  I want to create and update my nutrition preferences

  Scenario: Calculate targets for a new preference profile
    Given a user named "anna" is preparing nutrition preferences
    When the user sets preferences with gender "woman", weight 62, height 168, age 29, activity "activity2" and intention "intension2"
    Then the stored preference should have caloric requirement 2387, daily calories 2387, protein 99, fat 66 and carbohydrates 348
    And the latest stored weight history entry should equal 62 kg

  Scenario: Recalculate targets when preferences change
    Given a user named "jan" is preparing nutrition preferences
    And the user already has stored preferences
    When the user updates preferences with gender "man", weight 81, height 182, age 31, activity "activity3" and intention "intension3"
    Then the stored preference should have caloric requirement 3595, daily calories 3895, protein 149, fat 108 and carbohydrates 580
    And the latest stored weight history entry should equal 81 kg
