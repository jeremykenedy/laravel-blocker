<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('template_title', 'Laravel Blocker')</title>
    @yield('inline_template_linked_css')
</head>
<body>
    @yield('content')
    @yield('inline_footer_scripts')
</body>
</html>
