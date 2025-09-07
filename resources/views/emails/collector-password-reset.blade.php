<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Clean City Collector Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
        }
        .alternative-link {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin: 30px 0;
            word-break: break-all;
            font-size: 14px;
            color: #4a5568;
        }
        .footer {
            background-color: #2d3748;
            color: #a0aec0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer .company-name {
            color: #f97316;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .security-note {
            background-color: #fed7d7;
            border-left: 4px solid #fc8181;
            padding: 15px;
            margin: 30px 0;
            border-radius: 0 6px 6px 0;
        }
        .security-note h4 {
            color: #c53030;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .security-note p {
            color: #742a2a;
            margin: 0;
            font-size: 14px;
        }
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚛 Clean City Collector Portal</h1>
            <p class="subtitle">Password Reset Request</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $collectorName ?? 'Collector' }},
            </div>
            
            <div class="message">
                We received a request to reset the password for your Clean City Collector account. If you made this request, click the button below to reset your password and regain access to your dashboard.
            </div>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔑 Reset My Password
                </a>
            </div>
            
            <div class="message">
                If the button above doesn't work, you can copy and paste the following link into your browser:
            </div>
            
            <div class="alternative-link">
                {{ $resetUrl }}
            </div>
            
            <div class="security-note">
                <h4>🔒 Security Notice</h4>
                <p>
                    This password reset link will expire in 60 minutes for your security. 
                    If you didn't request this password reset, please ignore this email. 
                    Your account will remain secure.
                </p>
            </div>
            
            <div class="message">
                Need help? Contact our collector support team at 
                <a href="mailto:collector@cleancity@gmail.com" style="color: #f97316;">collector@cleancity@gmail.com</a> 
                or call us at <strong>+94 81 456 7890</strong>.
            </div>
        </div>
        
        <div class="footer">
            <div class="company-name">Clean City Collector Portal</div>
            <p>Empowering waste collectors with smart tools for efficient collection and route management.</p>
            <p>&copy; 2025 Clean City. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
