<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification - Clean City</title>
</head>
<body style="margin:0; padding:0; font-family: 'Segoe UI', sans-serif; background-color:#f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding: 50px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; padding:40px; box-shadow:0 4px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="font-size: 24px; color: #2e7d32; font-weight: bold;">
                            Clean City
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 18px; color: #444; padding: 20px 0 10px;">
                            Your One-Time Password (OTP)
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 0 30px;">
                            <p style="font-size: 16px; color: #666;">
                                Use the OTP below to complete your login. This code is valid for 5 minutes and should not be shared with anyone.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 30px 0;">
                            <div style="display: inline-block; background-color: #e8f5e9; padding: 20px 40px; border-radius: 8px;">
                                <span style="font-size: 36px; font-weight: bold; color: #2e7d32; letter-spacing: 2px;">
                                    {{ $otp }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 14px; color: #999; padding-top: 20px;">
                            Didn’t request this code? You can safely ignore this email.
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 12px; color: #aaa; padding-top: 30px;">
                            © {{ now()->year }} Clean City. All rights reserved.<br>
                            contact@cleancity.gmail.com | +94 81 456 7890
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>