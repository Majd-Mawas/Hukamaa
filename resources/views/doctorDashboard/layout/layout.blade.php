<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-doctordashboard.head />

<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">

    <!-- ..::  header area start ::.. -->
    <x-doctordashboard.sidebar />
    <!-- ..::  header area end ::.. -->

    <main class="dashboard-main">

        <!-- ..::  navbar start ::.. -->
        <x-doctordashboard.navbar />
        <!-- ..::  navbar end ::.. -->
        <div class="dashboard-main-body">

            <!-- ..::  breadcrumb  start ::.. -->
            <x-doctordashboard.breadcrumb title='{{ isset($title) ? $title : '' }}'
                subTitle='{{ isset($subTitle) ? $subTitle : '' }}' />
            <!-- ..::  header area end ::.. -->

            @yield('content')

        </div>
        <!-- ..::  footer  start ::.. -->
        <x-doctordashboard.footer />
        <!-- ..::  footer area end ::.. -->

    </main>

    <!-- ..::  scripts  start ::.. -->
    <x-doctordashboard.script :script="$script ?? ''" />
    <!-- ..::  scripts  end ::.. -->

</body>

</html>
