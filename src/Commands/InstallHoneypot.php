<?php

declare(strict_types=1);

namespace Atendwa\Honeypot\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

final class InstallHoneypot extends Command
{
    protected $signature = 'honeypot:install';

    protected $description = 'Install the Honeypot package';

    public function handle(): void
    {
        info('Installing Honeypot...');

        $configExists = $this->configExists();

        if (! $configExists) {
            $this->publishConfiguration();

            info('Published configuration.');

            return;
        }

        $overwrite = $this->shouldOverwriteConfig();

        if ($overwrite) {
            info('Overwriting configuration file...');

            $this->publishConfiguration(true);

            info('Published configuration.');

            return;
        }

        info('Existing configuration was not overwritten.');
    }

    private function configExists(): bool
    {
        return File::exists(config_path('honeypot.php'));
    }

    private function shouldOverwriteConfig(): bool
    {
        return confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration(bool $forcePublish = false): void
    {
        $params = [
            '--provider' => "Atendwa\Honeypot\HoneypotServiceProvider",
            '--tag' => 'config',
        ];

        if ($forcePublish) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
