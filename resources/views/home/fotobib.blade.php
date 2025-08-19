<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Racepack UMP Bersepeda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="flex items-center justify-center min-h-screen bg-blue-600 px-4">

    <!-- Div putih tengah dengan background image -->
    <div class="relative w-full max-w-6xl rounded-[45px] p-12 shadow-2xl bg-white"
        style="background-image: url('images/bib-polos-ump.png'); background-size: cover; background-repeat: no-repeat; background-position: center;">

        <!-- Header: Logo dan informasi -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
            </div>
            <div class="flex gap-3 items-center">
                <!-- Contoh logo/logo pihak kanan -->
                <div class="text-center p-8">

                </div>
            </div>
        </div>

        <!-- Bagian Tengah untuk Nomor Racepack -->
        <div class="flex flex-col items-center justify-center py-20">
            <h1 class="text-[140px] font-extrabold text-black leading-none drop-shadow-lg" id="bibNumber">-</h1>
            <p class="text-6xl font-bold text-blue-700 mt-2 tracking-widest drop-shadow-md" id="fullName">-</p>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-6 left-8 text-sm text-blue-800 font-semibold">

        </div>

    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                fetch('/api/preview-bib')
                    .then(res => res.json())
                    .then(participant => {
                        if (!participant.bib) return;
                        document.querySelector('#bibNumber').innerText = "60-" + participant.bib;
                        document.querySelector('#fullName').innerText = participant.full_name;
                    });
            }, 1000);
        });
    </script>
</body>

</html>
