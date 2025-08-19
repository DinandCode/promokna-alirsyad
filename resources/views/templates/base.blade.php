<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-KY4pqFnh5yc1jvScdKLuTVxUCR7xK9R9Up6fJS6pWfhMpToa3q9MbvU5K23IfTwEzxGf4qgB0Y88vH5L2b+5lA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/85edc239b7.js" crossorigin="anonymous"></script>

    @if (\App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) != null)
        <link rel="shortcut icon" href="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}" type="image/*">
    @endif

    <style>
        .race-pack img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .race-pack img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .timeline {
            position: relative;
            padding-left: 0;
            border-left: none;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 0;
            width: 4px;
            height: 100%;
            background: #2C3E50;
            transform: translateX(-50%);
        }

        .timeline-item {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 0;
            width: 16px;
            height: 16px;
            background: #2C3E50;
            border-radius: 50%;
            transform: translateX(-50%);
        }

        .timeline-content {
            width: 40%;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @stack('head')
</head>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800, // durasi animasi
    once: true,    // animasi hanya sekali saat muncul
  });
</script>

<body>
    @yield('content')
    @stack('script')
</body>

</html>