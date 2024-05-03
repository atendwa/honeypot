<?php

namespace Atendwa\Honeypot\Tests;

use Atendwa\Honeypot\HoneypotServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app): void
    {
    }

    protected function getPackageProviders($app): array
    {
        return [
            HoneypotServiceProvider::class,
        ];
    }
}
