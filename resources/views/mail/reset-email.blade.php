<x-mail::message>
# Восстановление пароля

Ссылка на восстановление Пароля

<x-mail::button :url="$token">
Восстановить пароль
</x-mail::button>

С уважением команда магазина<br>
{{ config('app.name') }}
</x-mail::message>
