<x-mail::message>
# Спасибо за регистрацию!

Прежде чем начать, пожалуйста, подтвердите ваш адрес электронной почты, перейдя по ссылке



<x-mail::button :url="$url">
    Подтвердите email
</x-mail::button>


С уважением,<br>
{{ config('app.name') }}
</x-mail::message>