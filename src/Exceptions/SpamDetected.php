<?php

declare(strict_types=1);

namespace Atendwa\Honeypot\Exceptions;

use Exception;

final class SpamDetected extends Exception
{
    public function render(): void
    {
        abort(429, 'Spam detected!');
    }
}
