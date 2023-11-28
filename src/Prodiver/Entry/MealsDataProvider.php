<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Prodiver\Entry;

use App\Dictionary\Entry\MealTypeDictionary;
use App\Repository\EntriesRepository;

class MealsDataProvider
{
    private EntriesRepository $entriesRepository;

    public function __construct(EntriesRepository $entriesRepository)
    {
        $this->entriesRepository = $entriesRepository;
    }

    public function getData(\DateTime $dateTime, int $userId): array
    {
        return [
            'entry' => $this->entriesRepository->displayEntry($dateTime, $userId), //all entries per day, but products are not grouping in one row (meal),
            'snack' => $this->entriesRepository->ShowSnack($dateTime, $userId, MealTypeDictionary::SNACK), // try to fetch all entries per day for row -> Przękąski
            'breakfast' => $this->entriesRepository->ShowBreakfast($dateTime, $userId, MealTypeDictionary::BREAKFAST), // the same way ^ but with Śnaidanie!,
            'lunch' => $this->entriesRepository->ShowLunch($dateTime, $userId, MealTypeDictionary::SECOND_BREAFAST), // DRUGIE ŚNAIDANIE,
            'dinner' => $this->entriesRepository->ShowDinner($dateTime, $userId, MealTypeDictionary::LUNCH), // OBIAD
            'tea' => $this->entriesRepository->ShowTea($dateTime, $userId, MealTypeDictionary::TEA), // Podwieczorek
            'supper' => $this->entriesRepository->ShowSupper($dateTime, $userId, MealTypeDictionary::DINNER), // Koalcja
            'snackcal' => $this->entriesRepository->SummSnacksKcal($dateTime, $userId, MealTypeDictionary::SNACK), // {{ snackcal|number_format }}
            'breakcal' => $this->entriesRepository->SummBreakfast($dateTime, $userId, MealTypeDictionary::BREAKFAST), //  {{ breakcal|number_format }}
            'lunchkcal' => $this->entriesRepository->SummLunch($dateTime, $userId, MealTypeDictionary::SECOND_BREAFAST), // {{ lunchkcal|number_format }}
            'dinnerkcal' => $this->entriesRepository->SummDinner($dateTime, $userId, MealTypeDictionary::LUNCH), // {{ dinnerkcal|number_format }}
            'teakcal' => $this->entriesRepository->SummTea($dateTime, $userId, MealTypeDictionary::TEA), // {{ teakcal|number_format }}
            'supperkcal' => $this->entriesRepository->SummSupper($dateTime, $userId, MealTypeDictionary::DINNER), // {{ supperkcal|number_format }}
            'dataTest' => $dateTime,
        ];
    }
}