<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invitation to Join URL Shortener</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F4F6F8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                         Roboto, Helvetica, Arial, sans-serif;
            color: #1F2937;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #FFFFFF;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .header {
            background-color: #84bdf2;
            color: #FFFFFF;
            padding: 28px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .content {
            padding: 30px 26px;
        }

        .content p {
            margin: 0 0 16px;
            font-size: 15px;
        }

        .role-badge {
            display: inline-block;
            background-color: #E6EEF7;
            color: #0F2A44;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 8px;
        }

        .credentials {
            background-color: #F8FAFC;
            border-left: 4px solid #1E4D8C;
            padding: 16px 18px;
            margin: 22px 0;
            border-radius: 4px;
        }

        .credentials h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #0F2A44;
        }

        .credentials p {
            margin: 6px 0;
            font-size: 14px;
        }

        .credentials code {
            background-color: #E5E7EB;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            color: #111827;
        }

        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 34px;
            background-color: #1E4D8C;
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border-radius: 4px;
        }

        .footer {
            text-align: center;
            padding: 18px 20px;
            font-size: 13px;
            color: #6B7280;
            background-color: #F8FAFC;
            border-top: 1px solid #E5E7EB;
        }

        .footer p {
            margin: 6px 0;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Invitation to Join URL Shortener</h1>
    </div>

    <div class="content">
        <p>Hello,</p>

        <p>
            You have been invited to join the URL Shortener service as a
            <strong>{{ $user->getRoleNames()->first() }}</strong>
            <span class="role-badge">{{ $user->getRoleNames()->first() }}</span>.
        </p>

        <div class="credentials">
            <h3>Your Login Credentials</h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Temporary Password:</strong> <code>{{ $tempPassword }}</code></p>
        </div>

        <p>
            <strong>Important:</strong>
            Please change your password after your first login to ensure account security.
        </p>

        <div class="button-wrapper">
            <a href="{{ $loginUrl }}" class="button">
                Login to Your Account
            </a>
        </div>

        <p>
            If you did not expect this invitation, you may safely ignore this email.
        </p>

        <p>
            Best regards,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>
    </div>

</div>

</body>
</html>
