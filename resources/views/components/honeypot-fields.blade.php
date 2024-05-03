<fieldset class="hidden">
    <input
        name="{{ config('honeypot.field_name') }}"
        type="text"
        hidden
    >

    <input
        name="{{ config('honeypot.time_field_name') }}"
        value="{{ microtime(true) }}"
        type="text"
        required
        hidden
    >
</fieldset>
