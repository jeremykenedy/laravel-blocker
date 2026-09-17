<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('template_title', 'Laravel Blocker')</title>
    @php($framework = config('laravelblocker.frontend') === 'legacy' ? 'bootstrap'.config('laravelblocker.blockerBootstapVersion') : config('laravelblocker.frontend'))
    <link rel="stylesheet" href="/assets/{{ $framework }}.css">
    @if(config('laravelblocker.frontend') === 'legacy')
        <script src="/assets/jquery.js"></script>
        <script src="/assets/{{ $framework }}.js"></script>
    @endif
    @yield('blocker_css')
</head>
<body style="background:#e8edf4">
    @yield('content')
    @yield('blocker_js')
</body>
</html>
