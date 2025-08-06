<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Changed - Clean City</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f0fdf4; color: #1a1a1a; padding: 40px;">
    <table style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background: #10b981; padding: 20px; text-align: center;">
                <h1 style="color: white; margin: 0;">Clean City</h1>
                <p style="color: #d1fae5; margin-top: 5px;">Your Waste, Our Responsibility</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <p style="font-size: 18px; font-weight: bold;">Hello {{ $user->name }},</p>
                <p style="margin: 20px 0;">
                    This is a confirmation that your password was successfully changed.
                </p>
                <p style="color: #e11d48;">
                    If you did not perform this action, please contact Clean City support immediately.
                </p>
                <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
                <p>Thank you,<br><strong>Clean City Support Team</strong></p>
            </td>
        </tr>
        <tr>
            <td style="background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280;">
                © {{ now()->year }} Clean City. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>