<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Kcalculator\Application\Command\Preferention\EditPreferenceCommand;
use Kcalculator\Application\Command\Preferention\SetPreferenceCommand;
use Kcalculator\Application\CommandHandler\Preferention\EditPreferenceHandler;
use Kcalculator\Application\CommandHandler\Preferention\SetPreferenceHandler;
use Kcalculator\Application\DTO\PreferenceDTO;
use Kcalculator\Application\Services\Preference\BasalMetabolicRateAlgorithm;
use Kcalculator\Domain\Preference\Entity\Preference;
use Kcalculator\Domain\Preference\Factory\PreferenceFactoryInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\WeightHistory\Entity\WeightHistory;
use Kcalculator\Domain\WeightHistory\Factory\WeightHistoryFactoryInterface;
use RuntimeException;

final class PreferenceContext implements Context
{
    private InMemoryPreferenceFactory $preferenceFactory;

    private InMemoryWeightHistoryFactory $weightHistoryFactory;

    private ?User $user = null;

    public function __construct()
    {
        $this->preferenceFactory = new InMemoryPreferenceFactory();
        $this->weightHistoryFactory = new InMemoryWeightHistoryFactory();
    }

    /**
     * @Given a user named :username is preparing nutrition preferences
     */
    public function aUserNamedIsPreparingNutritionPreferences(string $username): void
    {
        $this->user = new User(sprintf('%s Example', ucfirst($username)), $username, sprintf('%s@example.com', $username));
    }

    /**
     * @When the user sets preferences with gender :gender, weight :weight, height :height, age :age, activity :activity and intention :intention
     */
    public function theUserSetsPreferencesWithGenderWeightHeightAgeActivityAndIntention(
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        string $intention,
    ): void {
        $user = $this->requireUser();
        $handler = new SetPreferenceHandler(
            new BasalMetabolicRateAlgorithm(),
            $this->preferenceFactory,
            $this->weightHistoryFactory,
        );

        $handler(new SetPreferenceCommand($user, new PreferenceDTO(
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $intention,
        )));
    }

    /**
     * @Given the user already has stored preferences
     */
    public function theUserAlreadyHasStoredPreferences(): void
    {
        $user = $this->requireUser();

        $this->preferenceFactory->new(
            $user,
            'woman',
            62.0,
            168.0,
            29,
            'activity2',
            2331,
            'intension2',
            2331,
            99,
            64,
            339,
        );
    }

    /**
     * @When the user updates preferences with gender :gender, weight :weight, height :height, age :age, activity :activity and intention :intention
     */
    public function theUserUpdatesPreferencesWithGenderWeightHeightAgeActivityAndIntention(
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        string $intention,
    ): void {
        $user = $this->requireUser();
        $preference = $this->preferenceFactory->findForUser($user);

        if (!$preference instanceof Preference) {
            throw new RuntimeException('User preference must exist before editing.');
        }

        $handler = new EditPreferenceHandler(
            new BasalMetabolicRateAlgorithm(),
            $this->preferenceFactory,
            $this->weightHistoryFactory,
        );

        $handler(new EditPreferenceCommand($preference, new PreferenceDTO(
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $intention,
        )));
    }

    /**
     * @Then the stored preference should have caloric requirement :caloricRequirement, daily calories :dailyCalories, protein :protein, fat :fat and carbohydrates :carbohydrates
     */
    public function theStoredPreferenceShouldHaveCaloricRequirementDailyCaloriesProteinFatAndCarbohydrates(
        int $caloricRequirement,
        int $dailyCalories,
        int $protein,
        int $fat,
        int $carbohydrates,
    ): void {
        $preference = $this->preferenceFactory->findForUser($this->requireUser());

        if (!$preference instanceof Preference) {
            throw new RuntimeException('No preference was stored for the user.');
        }

        if ($preference->getKcal() !== $caloricRequirement
            || $preference->getKcalDay() !== $dailyCalories
            || $preference->getProteinPerDay() !== $protein
            || $preference->getFatPerDay() !== $fat
            || $preference->getCarboPerDay() !== $carbohydrates) {
            throw new RuntimeException('Stored preference has unexpected calculated values.');
        }
    }

    /**
     * @Then the latest stored weight history entry should equal :weight kg
     */
    public function theLatestStoredWeightHistoryEntryShouldEqualKg(float $weight): void
    {
        $entry = $this->weightHistoryFactory->lastEntry();

        if (!$entry instanceof WeightHistory) {
            throw new RuntimeException('No weight history entry was stored.');
        }

        if ($entry->getUserWeight() !== $weight) {
            throw new RuntimeException('Stored weight history entry has unexpected weight.');
        }
    }

    private function requireUser(): User
    {
        if (!$this->user instanceof User) {
            throw new RuntimeException('User must be prepared before this step.');
        }

        return $this->user;
    }
}

final class InMemoryPreferenceFactory implements PreferenceFactoryInterface
{
    /** @var array<int, Preference> */
    private array $preferences = [];

    public function new(
        User $user,
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        int $caloricRequirement,
        string $intentions,
        int $kcalDay,
        int $protein,
        int $fat,
        int $carbohydrates,
    ): Preference {
        $preference = new Preference();
        $preference->setUser($user);

        return $this->store(
            $user,
            $preference,
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $caloricRequirement,
            $intentions,
            $kcalDay,
            $protein,
            $fat,
            $carbohydrates,
        );
    }

    public function edit(
        Preference $userPreferention,
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        int $caloricRequirement,
        string $intentions,
        int $kcalDay,
        int $protein,
        int $fat,
        int $carbohydrates,
    ): Preference {
        $user = $userPreferention->getUser();

        if (!$user instanceof User) {
            throw new RuntimeException('Preference must be assigned to a user before editing.');
        }

        return $this->store(
            $user,
            $userPreferention,
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $caloricRequirement,
            $intentions,
            $kcalDay,
            $protein,
            $fat,
            $carbohydrates,
        );
    }

    public function findForUser(User $user): ?Preference
    {
        return $this->preferences[spl_object_id($user)] ?? null;
    }

    private function store(
        User $user,
        Preference $preference,
        string $gender,
        float $weight,
        float $height,
        int $age,
        string $activity,
        int $caloricRequirement,
        string $intentions,
        int $kcalDay,
        int $protein,
        int $fat,
        int $carbohydrates,
    ): Preference {
        $preference->setGender($gender);
        $preference->setWeight($weight);
        $preference->setHeight($height);
        $preference->setAge($age);
        $preference->setActivity($activity);
        $preference->setKcal($caloricRequirement);
        $preference->setIntentions($intentions);
        $preference->setKcalDay($kcalDay);
        $preference->setProteinPerDay($protein);
        $preference->setFatPerDay($fat);
        $preference->setCarboPerDay($carbohydrates);

        $this->preferences[spl_object_id($user)] = $preference;

        return $preference;
    }
}

final class InMemoryWeightHistoryFactory implements WeightHistoryFactoryInterface
{
    /** @var list<WeightHistory> */
    private array $entries = [];

    public function new(User $user, float $weight): WeightHistory
    {
        $weightHistory = new WeightHistory();
        $weightHistory->setUsers($user);
        $weightHistory->setUserWeight($weight);

        $this->entries[] = $weightHistory;

        return $weightHistory;
    }

    public function lastEntry(): ?WeightHistory
    {
        return $this->entries === [] ? null : $this->entries[array_key_last($this->entries)];
    }
}
