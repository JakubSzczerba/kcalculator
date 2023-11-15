<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Prodiver\Chart\Dashboard;

class MacronutrientsProvider
{
    public function getData($summProtein, $summFat, $summCarbo): array
    {
        return [
            'labels' => [
                'Białko',
                'Tłuszcz',
                'Węglowodany'
            ],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#0099FF',
                        '#FF6633',
                        '#663300'],
                    'data' => [$summProtein, $summFat, $summCarbo],
                ]
            ]
        ];
    }
}