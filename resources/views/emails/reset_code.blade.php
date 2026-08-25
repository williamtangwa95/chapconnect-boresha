<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h2 {
            color: #6366f1;
            margin: 0;
        }
        .content {
            padding: 20px 0;
            text-align: center;
        }
        .code-box {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #6366f1;
            background: #eef2ff;
            padding: 15px 25px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #98a2b3;
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Chap Connect</h2>
        </div>
        <div class="content">
            <h3>Password Reset Verification</h3>
            <p>You requested to reset your password. Use the verification code below to complete the password reset process:</p>
            
            <div class="code-box">
                {{ $code }}
            </div>
            
            <p style="font-size: 0.9em; color: #666666;">This verification code is valid for 30 minutes. If you did not request a password reset, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Chap Connect. All rights reserved.
        </div>
    </div>
</body>
</html>
