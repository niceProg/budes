@extends('layouts.app')

@section('title', 'Edit Materi | Admin - SMART Setjen DPR RI')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Menu</li>
                    <li class="breadcrumb-item"><a href="{{ route('materi.index') }}">Materi</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h1 class="page-title mb-0">Edit Materi</h1>
                <button type="button"
                        class="btn btn-light d-inline-flex align-items-center gap-2"
                        style="border-radius: 12px;"
                        data-bs-toggle="collapse"
                        data-bs-target="#materi_settings_collapse"
                        aria-expanded="true"
                        aria-controls="materi_settings_collapse"
                        id="materi_settings_toggle">
                    <span class="fw-600 small" id="materi_settings_toggle_text">Sembunyikan Pengaturan</span>
                    <i class="ri-arrow-up-s-line" id="materi_settings_toggle_icon"></i>
                </button>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; border: 0;">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('materi.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.materi._form', ['data' => $data, 'satker' => $satker])
        </form>
    </div>
</div>
@endsection

