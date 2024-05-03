<?php

declare(strict_types=1);

namespace Atendwa\Honeypot;

use Atendwa\Honeypot\Exceptions\SpamDetected;
use Illuminate\Http\Request;
use Throwable;

final class Honeypot
{
    /**
     * @var array<string>
     */
    private array $config;

    private Request $request;

    private string $honeypotInput;

    private string $timeInput;

    private int $minimumSubmissionDuration;

    public function __construct()
    {
        $this->config = config('honeypot');

        $this->honeypotInput = $this->config['honeypot_input_name'];

        $this->timeInput = $this->config['honeypot_time_input_name'];

        $key = 'minimum_submission_duration';

        $this->minimumSubmissionDuration = (int) $this->config[$key];
    }

    public function request(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Detects spam based on honeypot and submission duration.
     *
     * @throws Throwable
     */
    public function detectSpam(): void
    {
        if ( ! $this->isHoneypotEnabled()) {
            return;
        }

        $this->checkRequiredFields();

        $this->checkIfHoneypotWasFilled();

        $this->checkSubmissionDuration();
    }

    /**
     * Checks if HoneypotFields feature is enabled.
     */
    private function isHoneypotEnabled(): bool
    {
        return (bool) $this->config['enabled'];
    }

    /**
     * Checks if required fields are present in the request.
     *
     * @throws Throwable
     */
    private function checkRequiredFields(): void
    {
        $isSpam = $this->request->missing($this->honeypotInput);

        $isSpam = $isSpam || $this->request->missing($this->timeInput);

        $this->handleSpam($isSpam);
    }

    /**
     * Checks if the honeypot field was filled.
     *
     * @throws Throwable
     */
    private function checkIfHoneypotWasFilled(): void
    {
        $input = $this->request->input($this->honeypotInput) ?? '';

        $this->handleSpam(mb_strlen($input) > 0);
    }

    /**
     * Checks if the form was submitted too quickly.
     *
     * @throws Throwable
     */
    private function checkSubmissionDuration(): void
    {
        $startTime = $this->request->input($this->timeInput);

        $requestDuration = microtime(true) - $startTime;

        $this->handleSpam($requestDuration <= $this->minimumSubmissionDuration);
    }

    /**
     * Throws a SpamDetected if $detected is true.
     *
     * @throws Throwable
     */
    private function handleSpam(bool $detected): void
    {
        throw_if($detected, SpamDetected::class);
    }
}
