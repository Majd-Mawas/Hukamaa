<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="حكماء - منصة للاستشارات الطبية عبر الإنترنت. احجز مواعيد، تواصل مع الأطباء، وتابع استشاراتك بسهولة وأمان.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="حكماء - منصة للاستشارات الطبية">
    <meta property="og:description" content="احجز مواعيد، تواصل مع الأطباء، وتابع استشاراتك بسهولة وأمان.">
    <meta property="og:image" content="{{ asset('assets/landingPage/imgs/hukamaa.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="حكماء - منصة للاستشارات الطبية">
    <meta property="twitter:description" content="احجز مواعيد، تواصل مع الأطباء، وتابع استشاراتك بسهولة وأمان.">
    <meta property="twitter:image" content="{{ asset('assets/landingPage/imgs/hukamaa.png') }}">

    <!-- PAGE TITLE -->
    <title>حكماء - منصة للاستشارات الطبية</title>

    <!-- FAVICON -->
    <link rel="shortcut icon" href="{{ asset('assets/comingSoon/img/hukamaa.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/comingSoon/img/hukamaa.png') }}">

    <!-- PRELOAD FONTS -->
    <link rel="preload" href="{{ asset('assets/landingPage/fonts/Araboto-Bold.ttf') }}" as="font" type="font/ttf"
        crossorigin>
    <link rel="preload" href="{{ asset('assets/landingPage/fonts/Araboto-Normal.ttf') }}" as="font"
        type="font/ttf" crossorigin>

    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/comingSoon/css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/comingSoon/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <!-- CRITICAL CSS -->
    <style>
        /* Only include critical CSS here that's needed for initial render */
        @font-face {
            font-family: 'Araboto';
            src: url("{{ asset('assets/landingPage/fonts/Araboto-Bold.ttf') }}") format('truetype');
            font-display: swap;
        }

        @font-face {
            font-family: 'Araboto-normal';
            src: url("{{ asset('assets/landingPage/fonts/Araboto-Normal.ttf') }}") format('truetype');
            font-display: swap;
        }

        body {
            background: url("{{ asset('assets/landingPage/imgs/Layer_1.png') }}") no-repeat;
            /* background-size: cover; */
            font-family: 'Araboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .logo {
            width: 12rem;
        }
    </style>
</head>

<body>
    <!-- PRELOADER -->
    <div class="preloader" id="preloader">
        <div class="spinner">
            <div class="bounce-1"></div>
            <div class="bounce-2"></div>
            <div class="bounce-3"></div>
        </div>
    </div>

    <main class="hero">
        <header class="py-12">
            <img src="{{ asset('assets/landingPage/imgs/hukamaa.png') }}" class="logo mx-auto" alt="شعار حكماء">
        </header>

        <div class="flex justify-around flex-wrap gap-20">
            <section class="md:max-w-1/2">
                <div class="text-[#005745] font-normal text-5xl text-right leading-[150%]">
                    الاستشارات الطبية أصبحت <br> أقرب من أي وقت.
                </div>
                <div
                    class="text-[#000000] font-normal text-4xl text-right leading-[150%] font-['Araboto-normal'] my-12">
                    حمل التطبيق وابدأ رحلتك الطبية من هاتفك. <br>
                    احجز مواعيد، تواصل مع الأطباء، وتابع <br>
                    استشاراتك بسهولة وأمان.
                </div>
                <div class="mt-12 flex gap-12">
                    <a href="#" target="_blank" rel="noopener noreferrer" aria-label="تحميل التطبيق من App Store">
                        <img src="{{ asset('assets/landingPage/imgs/appstore.png') }}" class="w-72"
                            alt="تحميل من App Store">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.ahmadalbetar.hukamaa&pcampaignid=web_share"
                        target="_blank" rel="noopener noreferrer" aria-label="تحميل التطبيق من Google Play">
                        <img src="{{ asset('assets/landingPage/imgs/googleplay.png') }}" class="w-72"
                            alt="تحميل من Google Play">
                    </a>
                </div>
            </section>

            <section class="flex md:max-w-1/2 gap-12">
                <div>
                    <img src="{{ asset('assets/landingPage/imgs/Blue@2x.png') }}" class="w-80" alt="صورة تطبيق حكماء"
                        loading="lazy">
                </div>
                <div>
                    <img src="{{ asset('assets/landingPage/imgs/Blue@1x.png') }}" class="w-96" alt="صورة تطبيق حكماء"
                        loading="lazy">
                </div>
            </section>
        </div>
    </main>

    <!-- JAVASCRIPTS -->
    <script src="{{ asset('assets/comingSoon/js/plugins.js') }}" defer></script>
    <script src="{{ asset('assets/comingSoon/js/main.js') }}" defer></script>

    <!-- Inline critical JS -->
    <script>
        // Remove preloader once page is loaded
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
        });
    </script>
</body>

</html>
