<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email - Clean City</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4fef7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 128, 0, 0.1);
            border: 1px solid #d2e7d9;
        }

        h1 {
            color: #15803d;
            margin-bottom: 16px;
        }

        p {
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background-color: #22c55e;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #777;
            text-align: center;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            color: #15803d;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            margin-bottom: 16px;
        }

        .highlight {
            color: #16a34a;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <center>
            <div class="brand">Clean City</div>
        </center>

        <h1>🌿 Confirm Your Email Address</h1>

        <p>Thanks for joining Clean City — your smart waste management partner. You're almost there! Just confirm your email address to activate your account and help us build a cleaner, greener future together.</p>

        <p>Click the button below to verify your email address:</p>

        <center>
            <a href="{{ $actionUrl }}" class="btn">Verify Email</a>
        </center>

        <p>If you didn’t create this account, no further action is required.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Clean City. All rights reserved.<br>
            Contact us: <span class="highlight">contact@cleancity.gmail.com</span> | +94 81 456 7890
        </div>
    </div>
</body>
</html>