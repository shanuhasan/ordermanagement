<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin sign in | JCUT</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('admin-assets/css/auth.css') }}?v=1">
</head>

<body class="pm-auth">
    <div class="pa-shell">
        <aside class="pa-brand">
            <div class="pa-logo">
                <span class="pa-logo-mark">J</span>
                <span>JCUT ADMIN</span>
            </div>

            <div class="pa-hero">
                <h1>Control centre for <span>every company.</span></h1>
                <p>Manage companies, users and financial years across the whole order management platform.</p>
                <ul class="pa-points">
                    <li>Create and restore companies</li>
                    <li>Manage company user access</li>
                    <li>Configure financial years</li>
                </ul>
            </div>

            <div class="pa-foot">&copy; {{ date('Y') }} Musheeda. All rights reserved.</div>
        </aside>

        <main class="pa-form-wrap">
            <div class="pa-card">
                <div class="pa-logo pa-mobile-logo">
                    <span class="pa-logo-mark" style="color:#fff">J</span>
                    <span>JCUT ADMIN</span>
                </div>
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
