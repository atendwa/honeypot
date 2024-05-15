<?php

use Atendwa\Honeypot\Exceptions\SpamDetected;
use Atendwa\Honeypot\Honeypot;
use Atendwa\Honeypot\Http\Middleware\PreventSpam;
use Illuminate\Http\Request;

it('can throw a SpamDetected exception when enabled', function (): void {
    config(['honeypot.enabled' => true]);

    $request = new Request();

    $this->expectException(SpamDetected::class);

    (new PreventSpam())->handle($request, function (): void {
        $this->fail('Next should not be called.');
    });
});

it('does not throw a SpamDetected exception when disabled', function (): void {
    config(['honeypot.enabled' => false]);

    $request = new Request();

    $closureCalled = false;

    (new PreventSpam())->handle($request, function () use (&$closureCalled): void {
        $closureCalled = true;
    });

    $this->assertTrue($closureCalled, 'Next was called');
});

it('throws a SpamDetected exception when honeypot fields are missing', function (): void {
    config(['honeypot.enabled' => true]);

    $request = new Request();

    $this->expectException(SpamDetected::class);

    (new PreventSpam())->handle($request, function () use (&$closureCalled): void {
        $closureCalled = true;
    });
});

it('throws a SpamDetected exception when honeypot field is filled', function (): void {
    config(['honeypot.enabled' => true]);

    $request = new Request([
        config('honeypot.honeypot_input_name') => 'random input',
    ]);

    $this->expectException(SpamDetected::class);

    (new PreventSpam())->handle($request, function () use (&$closureCalled): void {
        $closureCalled = true;
    });
});

it('throws a SpamDetected exception when submitted too fast', function (): void {
    config(['honeypot.enabled' => true]);

    $request = new Request([
        config('honeypot.honeypot_input_name') => null,
        config('honeypot.honeypot_time_input_name') => microtime(true),
    ]);

    $this->expectException(SpamDetected::class);

    (new PreventSpam())->handle($request, function () use (&$closureCalled): void {
        $closureCalled = true;
    });
});

it(/**
 * @throws Throwable
 */ 'does not throw a SpamDetected exception when submitted within allowed time', function (): void {
    config(['honeypot.enabled' => true]);

    $closureCalled = false;

    $request = new Request([
        config('honeypot.honeypot_input_name') => null,
        config('honeypot.honeypot_time_input_name') => microtime(true) - 2,
    ]);

    $sleepDuration = (int) config('honeypot.minimum_submission_duration');

    $start = microtime(true);

    sleep($sleepDuration);

    $end = microtime(true);

    $this->assertGreaterThanOrEqual(
        $sleepDuration,
        $end - $start,
        'The script did not sleep for the expected duration'
    );

    (new PreventSpam())->handle($request, function () use (&$closureCalled): void {
        $closureCalled = true;
    });

    $this->assertTrue($closureCalled, 'Next was called');
});

it('throws a SpamDetected exception when submitted too fast and passes fields', function (): void {
    $this->expectException(SpamDetected::class);

    $honeypot = new Honeypot();
    $honeypot->detectSpam('random input', microtime(true));
});

it('does not throw a SpamDetected exception when submitted within allowed time and passes fields', function (): void {
    $sleepDuration = (int) config('honeypot.minimum_submission_duration');

    $start = microtime(true);

    sleep($sleepDuration);

    $sleepDuration = (int) config('honeypot.minimum_submission_duration');

    sleep($sleepDuration);

    $honeypot = new Honeypot();

    try {
        $honeypot->detectSpam('', $start);

        $detected = false;
    } catch (Exception) {
        $detected = true;
    }

    $this->assertTrue(!$detected, 'No exception should be thrown');
});
