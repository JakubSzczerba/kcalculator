<?php

namespace Kcalculator\Application\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class ImageExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('findFood', [$this, 'findFood']),
            new TwigFunction('kcal', [$this, 'kcal']),
        ];
    }
}
