<?php

declare(strict_types=1);

namespace Kcalculator\Tests\Behat\Context;

use Behat\Behat\Context\Context;

final class SmokeContext implements Context
{
    /**
     * @Given Behat is configured
     */
    public function behatIsConfigured(): void
    {
    }
}
