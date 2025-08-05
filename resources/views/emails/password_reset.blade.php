<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Clean City</title>
</head>
<body style="background-color: #f9f9f9; font-family: sans-serif; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #2e7d32;">Clean City</h2>
        <h3 style="color: #333;">Reset Your Password</h3>
        <p>Hello {{ $name ?? 'User' }},</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p style="text-align: center;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #2e7d32; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none;">Reset Password</a>
        </p>
        <p>This link will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <hr>
        <p style="font-size: 12px; color: gray;">© 2025 Clean City. All rights reserved.</p>
    </div>
</body>
</html>