<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://webfonts.huggy.cloud/development/locke/locke.css" />
        <title>Hackathon</title>
        <!-- Vite directive to load compiled assets -->
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
    </head>
    <body class="antialiased">
        <!-- Vue app will mount here -->
        <div id="app" style="height:100vh"></div>
    </body>
</html>