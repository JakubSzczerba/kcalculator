<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\CommandHandler\Preferention;

use Kcalculator\Application\Command\Preferention\EditPreferenceCommand;
use Kcalculator\Application\Services\Preference\BasalMetabolicRateAlgorithm;
use Kcalculator\Domain\Preference\Factory\PreferenceFactory;
use Kcalculator\Domain\WeightHistory\Factory\WeightHistoryFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EditPreferenceHandler
{
    private BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm;

    private PreferenceFactory $preferenceFactory;

    private WeightHistoryFactory $userWeightHistoryFactory;

    public function __construct(BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm, PreferenceFactory $preferenceFactory, WeightHistoryFactory $userWeightHistoryFactory)
    {
        $this->basalMetabolicRateAlgorithm = $basalMetabolicRateAlgorithm;
        $this->preferenceFactory = $preferenceFactory;
        $this->userWeightHistoryFactory = $userWeightHistoryFactory;
    }

    public function __invoke(EditPreferenceCommand $command): void
    {
        $preferentionDTO = $command->getPreferenceDTO();

        /* Calculate Basal Metabolic Rate per user */
        $BMR = $this->basalMetabolicRateAlgorithm->calculate(
            $preferentionDTO->getGender(),
            $preferentionDTO->getWeight(),
            $preferentionDTO->getHeight(),
            $preferentionDTO->getAge(),
            $preferentionDTO->getActivity(),
            $preferentionDTO->getIntentions()
        );

        /* Edit preferentions */
        $this->preferenceFactory->edit(
            $command->getPreference(),
            $preferentionDTO->getGender(),
            $preferentionDTO->getWeight(),
            $preferentionDTO->getHeight(),
            $preferentionDTO->getAge(),
            $preferentionDTO->getActivity(),
            $BMR['caloric_requirement'],
            $preferentionDTO->getIntentions(),
            $BMR['kcal_per_day'],
            $BMR['protein'],
            $BMR['fat'],
            $BMR['carbohydrates']
        );

        /* Persist weight history */
        $this->userWeightHistoryFactory->new($command->getUser(), $preferentionDTO->getWeight());
    }
}