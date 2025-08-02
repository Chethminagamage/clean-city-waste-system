<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Clean City</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-top: 6px solid #28a745;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #28a745;
            margin: 0;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
        }
        .content strong {
            color: #111;
        }
        .cta {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            background-color: #28a745;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Welcome to Clean City</h2>
            <p>Your Waste, Our Responsibility</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>

            <p>Your collector account has been successfully created!</p>

            <p><strong>Email:</strong> {{ $email }}<br>
               <strong>Password:</strong> {{ $password }}</p>

            <p>Please use these credentials to log in and manage your waste collection duties. For security, kindly change your password after your first login.</p>
        </div>

        <div class="cta">
            <a href="{{ url('/collector/login') }}" class="btn">Log In Now</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Clean City Waste Management System. All rights reserved.
        </div>
    </div>
</body>
</html>