<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\Application\CommandHandler\Preferention;

use Kcalculator\Application\Command\Preferention\EditPreferenceCommand;
use Kcalculator\Application\CommandHandler\Preferention\EditPreferenceHandler;
use Kcalculator\Application\DTO\PreferenceDTO;
use Kcalculator\Application\Services\Preference\BasalMetabolicRateAlgorithm;
use Kcalculator\Domain\Preference\Entity\Preference;
use Kcalculator\Domain\Preference\Factory\PreferenceFactoryInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\WeightHistory\Factory\WeightHistoryFactoryInterface;
use PHPUnit\Framework\TestCase;

final class EditPreferenceHandlerTest extends TestCase
{
    public function testItRecalculatesTargetsAndStoresUpdatedWeightHistory(): void
    {
        $user = new User('Jan Example', 'jan', 'jan@example.com');
        $preference = (new Preference())->setUser($user);
        $dto = new PreferenceDTO('man', 81.0, 182.0, 31, 'activity3', 'intension3');

        $preferenceFactory = $this->createMock(PreferenceFactoryInterface::class);
        $weightHistoryFactory = $this->createMock(WeightHistoryFactoryInterface::class);

        $preferenceFactory
            ->expects(self::once())
            ->method('edit')
            ->with(
                $preference,
                'man',
                81.0,
                182.0,
                31,
                'activity3',
                3595,
                'intension3',
                3895,
                149,
                108,
                580,
            );

        $weightHistoryFactory
            ->expects(self::once())
            ->method('new')
            ->with($user, 81.0);

        $handler = new EditPreferenceHandler(
            new BasalMetabolicRateAlgorithm(),
            $preferenceFactory,
            $weightHistoryFactory,
        );

        $handler(new EditPreferenceCommand($preference, $dto));

        self::addToAssertionCount(1);
    }
}
