<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Unit\Application\CommandHandler\Preferention;

use Kcalculator\Application\Command\Preferention\SetPreferenceCommand;
use Kcalculator\Application\CommandHandler\Preferention\SetPreferenceHandler;
use Kcalculator\Application\DTO\PreferenceDTO;
use Kcalculator\Application\Services\Preference\BasalMetabolicRateAlgorithm;
use Kcalculator\Domain\Preference\Factory\PreferenceFactoryInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\WeightHistory\Factory\WeightHistoryFactoryInterface;
use PHPUnit\Framework\TestCase;

final class SetPreferenceHandlerTest extends TestCase
{
    public function testItCalculatesPreferenceTargetsAndStoresWeightHistory(): void
    {
        $user = new User('Anna Example', 'anna', 'anna@example.com');
        $dto = new PreferenceDTO('woman', 62.0, 168.0, 29, 'activity2', 'intension2');

        $preferenceFactory = $this->createMock(PreferenceFactoryInterface::class);
        $weightHistoryFactory = $this->createMock(WeightHistoryFactoryInterface::class);

        $preferenceFactory
            ->expects(self::once())
            ->method('new')
            ->with(
                $user,
                'woman',
                62.0,
                168.0,
                29,
                'activity2',
                2387,
                'intension2',
                2387,
                99,
                66,
                348,
            );

        $weightHistoryFactory
            ->expects(self::once())
            ->method('new')
            ->with($user, 62.0);

        $handler = new SetPreferenceHandler(
            new BasalMetabolicRateAlgorithm(),
            $preferenceFactory,
            $weightHistoryFactory,
        );

        $handler(new SetPreferenceCommand($user, $dto));

        self::addToAssertionCount(1);
    }
}
