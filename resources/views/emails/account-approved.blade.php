<x-mail::message>
# Account Approved

Hello {{ $user->name }},

Your account registration has been approved by the administrator. You can now login to your account.

<x-mail::button :url="route('login')">
Login to Your Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>