<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('css/libs/rplearning/css/rplearning.css')}}">
        {{--  <link rel="stylesheet" href="{{asset('css/all.css')}}">  --}}
        {{--  <link rel="stylesheet" href="{{asset('css/app.css')}}">  --}}
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <title>RP Learning</title>
    </head>
    <body>
        <div id='app' style="height: 100%;"></div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
