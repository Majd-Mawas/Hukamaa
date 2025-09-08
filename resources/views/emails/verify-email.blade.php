<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التحقق من البريد الإلكتروني</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            direction: rtl;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .code-container {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body dir="rtl">
    <div class="container" dir="rtl">
        <div class="header" dir="rtl">
            <h2 dir="rtl">مرحباً {{ $name }}!</h2>
        </div>

        <p dir="rtl">شكراً لتسجيلك. يرجى استخدام الرمز التالي للتحقق من بريدك الإلكتروني:</p>

        <div class="code-container" dir="rtl">
            {{ $code }}
        </div>

        <p dir="rtl">سينتهي صلاحية هذا الرمز خلال 60 دقيقة.</p>
        <p dir="rtl">إذا لم تقم بإنشاء حساب، فلا يلزم اتخاذ أي إجراء آخر.</p>

        <div class="footer" dir="rtl">
            <p dir="rtl">© {{ date('Y') }} حكماء. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>

</html>
