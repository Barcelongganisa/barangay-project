<x-mail::message>
# Account Registration Declined

Hello {{ $user->name }},

We regret to inform you that your account registration has been declined.

**Reason:** {{ $reason }}

If you believe this is a mistake, please contact the administrator.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>