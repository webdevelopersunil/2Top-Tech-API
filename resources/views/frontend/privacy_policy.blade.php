<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta name="baseUrl" content="{{env('APP_URL')}}" />
        <title>{{ config('app.name', 'Privacy Policy') }}</title>

    </head>
    <script>
        window._locale = '{{ $locale }}';
        window._translations = {!! cache('translations') !!};
    </script>
    <body>
        <div id="app">
            <h2>Privacy policy</h2>
            {!! html_entity_decode($privacyPolicy ) !!}
        </div>
    </body>
</html>
