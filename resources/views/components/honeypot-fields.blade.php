<fieldset class="hidden">
    <input
        name="{{ config('honeypot.honeypot_input_name') }}"
        type="text"
        hidden
    >

    <input
        name="{{ config('honeypot.honeypot_time_input_name') }}"
        value="{{ microtime(true) }}"
        type="text"
        required
        hidden
    >
</fieldset>
