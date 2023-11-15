<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Command\Preferention;

use App\Factory\Preference\PreferenceFactory;
use App\Factory\Preference\UserWeightHistoryFactory;
use App\Services\Preference\BasalMetabolicRateAlgorithm;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EditPreferentionHandler
{
    private BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm;

    private PreferenceFactory $preferenceFactory;

    private UserWeightHistoryFactory $userWeightHistoryFactory;

    public function __construct(BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm, PreferenceFactory $preferenceFactory, UserWeightHistoryFactory $userWeightHistoryFactory)
    {
        $this->basalMetabolicRateAlgorithm = $basalMetabolicRateAlgorithm;
        $this->preferenceFactory = $preferenceFactory;
        $this->userWeightHistoryFactory = $userWeightHistoryFactory;
    }

    public function __invoke(EditPreferentionCommand $command): void
    {
        $preferentionDTO = $command->getPreferentionDTO();

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
            $command->getUserPreferention(),
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