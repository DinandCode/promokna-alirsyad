@extends('templates.navbar')

@section('title', 'Promokna.id')

@section('navbar_content')

    <section id="syarat-ketentuan" class="pt-24 px-5 md:px-20">
        <div class="bg-white rounded-2xl shadow-lg p-10 trix-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Syarat & Ketentuan</h2>
            {!! \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_SK) !!}
        </div>
    </section>

@endsection
