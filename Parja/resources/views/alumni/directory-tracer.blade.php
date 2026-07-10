@extends('parja::alumni.layouts.app')

@section('title', 'Direktori Alumni & Tracer')
@section('page-title', 'Direktori Alumni & Tracer Study')

@section('content')
    <x-parja.panel
        title="Direktori Alumni & Tracer Study"
        subtitle="Satu halaman akses cepat untuk penelusuran data alumni dan pengisian tracer study."
    >
        <x-slot:chip>
            <x-parja.chip icon="ri-compass-discover-line">Menu Alumni ke-3</x-parja.chip>
        </x-slot:chip>
    </x-parja.panel>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <x-parja.card title="Total Alumni Aktif">
                <p class="mb-0 fw-semibold">{{ $stats['total_alumni'] ?? 0 }}</p>
            </x-parja.card>
        </div>
        <div class="col-md-4">
            <x-parja.card title="Total Profil Alumni">
                <p class="mb-0 fw-semibold">{{ $stats['total_profile'] ?? 0 }}</p>
            </x-parja.card>
        </div>
        <div class="col-md-4">
            <x-parja.card title="Alumni Satu Dapil">
                <p class="mb-0 fw-semibold">{{ $stats['same_dapil'] ?? 0 }}</p>
            </x-parja.card>
        </div>
    </div>

@endsection
