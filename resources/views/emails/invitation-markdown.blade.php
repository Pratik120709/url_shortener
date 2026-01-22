@component('mail::message')
# Invitation to Join URL Shortener

Hello!

You have been invited to join the URL Shortener service as a **{{ $user->getRoleNames()->first() }}**.

## Your Login Credentials:
- **Email:** {{ $user->email }}
- **Temporary Password:** `{{ $tempPassword }}`

@component('mail::button', ['url' => $loginUrl])
Login to Your Account
@endcomponent

**Important:** Please change your password after your first login for security.

If you did not expect this invitation, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
