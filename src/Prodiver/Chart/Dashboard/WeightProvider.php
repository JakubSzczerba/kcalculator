<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Prodiver\Chart\Dashboard;

class WeightProvider
{
    public function getData($months, $results): array
    {
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Zmiana wagi',
                    'backgroundColor' => '',
                    'borderColor' => 'green',
                    'data' => $results
                ]
            ]
        ];
    }
}