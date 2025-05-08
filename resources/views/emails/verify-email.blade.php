<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email Address</h1>
        </div>
        <div class="content">
            <p>Hello!</p>
            <p>Thank you for registering with FandonWearShop. Please click the button below to verify your email address:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </div>
            
            <p>If you did not create an account, no further action is required.</p>
            
            <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:</p>
            <p>{{ $url }}</p>
            
            <p>Regards,<br>FandonWearShop Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} FandonWearShop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
