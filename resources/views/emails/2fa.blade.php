<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your 2FA Code</title>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 580px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            color: #111827;
        }

        .header {
            font-size: 22px;
            font-weight: 700;
            color: #10b981; /* emerald-500 */
            text-align: center;
            margin-bottom: 10px;
        }

        .subheading {
            font-size: 14px;
            text-align: center;
            color: #4b5563;
            margin-bottom: 28px;
        }

        .code-box {
            background-color: #ecfdf5; /* emerald-50 */
            border: 2px dashed #10b981;
            color: #065f46; /* emerald-800 */
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            padding: 18px 0;
            border-radius: 10px;
            letter-spacing: 6px;
            margin: 20px 0;
        }

        .note {
            text-align: center;
            font-size: 14px;
            margin-top: 12px;
            color: #374151;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #9ca3af;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Clean City - Smart Waste Management</div>
        <div class="subheading">Please use the verification code below to complete your login:</div>

        <div class="code-box">{{ $code }}</div>

        <div class="note">This code is valid for <strong>5 minutes</strong>.</div>

        <div class="divider"></div>

        <div class="footer">
            If you didn’t request this code, please ignore this email.<br>
            &copy; {{ now()->year }} Clean City - Smart Waste Management. All rights reserved.
        </div>
    </div>
</body>
</html>