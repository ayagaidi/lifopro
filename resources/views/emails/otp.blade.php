<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background:#aa5940;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .otp-code {
            background: #aa5940;
            color: white;
            font-size: 36px;
            font-weight: bold;
            padding: 20px 40px;
            border-radius: 10px;
            letter-spacing: 5px;
            margin: 30px 0;
            display: inline-block;
            font-family: 'Courier New', monospace;
        }
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">الاتحاد العام للتامين</div>
            <h1>رمز التحقق</h1>
        </div>
        
        <div class="content">
            <div class="message">
                {{ $messageText }}
            </div>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <div class="warning">
                <strong>تنبيه:</strong> هذا الرمز صالح لمدة {{ $expiresIn }} دقائق فقط. لا تشاركه مع أي شخص آخر.
            </div>
            
            <div class="message">
                إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.
            </div>
        </div>
        
        <div class="footer">
            <p>© 2025  الاتحادالعام للتامين. جميع الحقوق محفوظة.</p>
            <p>تم إرسال هذه الرسالة تلقائياً من نظام الاتحادالعام للتامين</p>
        </div>
    </div>
</body>
</html>