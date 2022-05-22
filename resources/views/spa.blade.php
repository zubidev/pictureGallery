@php
$config = [
'appName' => config('app.name'),
'locale' => $locale = app()->getLocale(),
'locales' => config('app.locales'),
'githubAuth' => config('services.github.client_id'),
];
$appJs = mix('dist/js/app.js');
$appCss = mix('dist/css/app.css');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ (str_starts_with($appCss, '//') ? 'http:' : '').$appCss }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/buefy/0.9.4/buefy.min.css"
        integrity="sha512-kYGHZRStwK4F8bgVhj/J5IEWmEjLbQ7re6mQiYx/LH5pfl8bDQ3g5SaExM/6z59mASfENR8xwVhywnm8ulVvew=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="app"></div>

    <script>
        window.config = @json($config);
    </script>

    <script src="{{ (str_starts_with($appJs, '//') ? 'http:' : '').$appJs }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
</body>

</html>