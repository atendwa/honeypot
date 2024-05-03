<?php

return [
    /*
     * Determine whether the Honeypot feature is enabled or not.
     */
    'enabled' => env('HONEYPOT_ENABLED', true),

    /*
     * The name of the hidden field to be used as a honeypot.
     */
    'honeypot_input_name' => env('HONEYPOT_INPUT_NAME', 'mobile'),

    /*
     * The name of the field used to store the timestamp for the honeypot.
     */
    'honeypot_time_input_name' => env('HONEYPOT_TIME_INPUT_NAME', 'time_field'),

    /*
     * The minimum duration (in seconds) a form should take to
     * submit to avoid honeypot detection.
     */
    'minimum_submission_duration' => env('HONEYPOT_MINIMUM_SUBMISSION_DURATION', 1),
];
