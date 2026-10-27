<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ config('app.name', 'Real Estate System') }}
    </title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <main class="min-h-screen flex items-center justify-center p-6">
        {{ $slot }}
    </main>
</body>

</html>