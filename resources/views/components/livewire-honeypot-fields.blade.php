<fieldset class="hidden">
    <input
        wire.model="happy_birthday"
        name="happy_birthday"
        maxlength="255"
        type="text"
        hidden
    >

    <input
        wire.model="happy_birthday_time"
        value="{{ microtime(true) }}"
        name="happy_birthday_time"
        maxlength="255"
        type="text"
        required
        hidden
    >
</fieldset>
