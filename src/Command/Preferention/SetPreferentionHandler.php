<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Command\Preferention;

use Kcalculator\Factory\Preference\PreferenceFactory;
use Kcalculator\Factory\Preference\UserWeightHistoryFactory;
use Kcalculator\Services\Preference\BasalMetabolicRateAlgorithm;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SetPreferentionHandler
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


    public function __invoke(SetPreferentionCommand $command): void
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

        /* Persist preferentions */
        $this->preferenceFactory->new(
            $command->getUser(),
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