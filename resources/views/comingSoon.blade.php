<!doctype html>

<html lang="en">


<head>


    <!-- META -->
    <meta charset="utf-8">
    <meta name="robots" content="noodp">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- PAGE TITLE -->
    <title>Hukamaa - Landing Page</title>

    <!-- FAVICON -->
    <link rel="shortcut icon" href="{{ asset('assets/comingSoon/img/hukamaa.png') }}">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&amp;subset=latin-ext" rel="stylesheet">

    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/comingSoon/css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/comingSoon/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


</head>
<style>
    @font-face {
        font-family: 'Araboto';
        src: url("{{ asset('assets/landingPage/fonts/Araboto-Bold.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'Araboto-normal';
        src: url("{{ asset('assets/landingPage/fonts/Araboto-Normal.ttf') }}") format('truetype');
    }

    body {
        background: url("{{ asset('assets/landingPage/imgs/Layer_1.png') }}");
        background-repeat: no-repeat;
        font-family: 'Araboto', sans-serif;
    }

    .logo {
        width: 12rem;
    }
</style>

<body>


    <!-- PRELOADER -->
    <div class="preloader">

        <!-- SPINNER -->
        <div class="spinner">

            <div class="bounce-1"></div>
            <div class="bounce-2"></div>
            <div class="bounce-3"></div>

        </div>
        <!-- /SPINNER -->

    </div>
    <!-- /PRELOADER -->

    <div class="hero" dir="rtl">
        <div class="py-12">
            <img src="{{ asset('assets/landingPage/imgs/hukamaa.png') }}" class="logo mx-auto" alt="">
        </div>
        <div class="flex justify-around flex-wrap gap-20">
            <div class="md:max-w-1/2">
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
                    <div>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('assets/landingPage/imgs/appstore.png') }}" class="w-72"
                                alt="">
                        </a>
                    </div>
                    <div>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('assets/landingPage/imgs/googleplay.png') }}" class="w-72"
                                alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex md:max-w-1/2 gap-12">
                <div>
                    <img src="{{ asset('assets/landingPage/imgs/Blue@2x.png') }}" class="w-80" alt="">
                </div>
                <div>
                    <img src="{{ asset('assets/landingPage/imgs/Blue@1x.png') }}" class="w-96" alt="">
                </div>
            </div>

        </div>
    </div>

    <!-- /HERO -->


    <!-- JAVASCRIPTS -->
    <script type="text/javascript" src="{{ asset('assets/comingSoon/js/plugins.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/comingSoon/js/main.js') }}"></script>


</body>


</html>
